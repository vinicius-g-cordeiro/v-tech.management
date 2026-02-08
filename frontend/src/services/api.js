import axios from 'axios'
import router from '@/router'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:80',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

// Request Interceptor - Adiciona token em todas as requisições
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token')

        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }

        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response Interceptor - Trata erros globalmente
api.interceptors.response.use(
    (response) => {
        return response
    },
    async (error) => {
        const originalRequest = error.config

        // Token expirado ou inválido
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true

            try {
                // Tenta renovar o token
                const response = await axios.post(
                    `${api.defaults.baseURL}/auth/refresh`,
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`
                        }
                    }
                )

                const newToken = response.data.token
                localStorage.setItem('token', newToken)

                // Refaz a requisição original com o novo token
                originalRequest.headers.Authorization = `Bearer ${newToken}`
                return api(originalRequest)
            } catch (refreshError) {
                // Se falhar ao renovar, desloga o usuário
                localStorage.removeItem('token')
                router.push({ name: 'login' })
                return Promise.reject(refreshError)
            }
        }

        // Erro 403 - Forbidden
        if (error.response?.status === 403) {
            console.error('Acesso negado')
        }

        // Erro 404 - Not Found
        if (error.response?.status === 404) {
            console.error('Recurso não encontrado')
        }

        // Erro 500 - Server Error
        if (error.response?.status >= 500) {
            console.error('Erro no servidor')
        }
        
        return Promise.reject(error)
    }

)

export default api