import axios from 'axios'
import router from '@/router'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost',
    timeout: 10000,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            router.push({ name: 'login' })
        }

        return Promise.reject(error)
    }
)

api.interceptors.request.use(config => {
    const csrf = sessionStorage.getItem('csrf_token')

    if (csrf) {
        config.headers['X-CSRF-Token'] = csrf
    }

    return config
})

export default api