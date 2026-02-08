import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService from '@/services/authService'

export const useAuthStore = defineStore('auth', () => {

    const user = ref(null)
    const token = ref(localStorage.getItem('token') || null)
    const loading = ref(false)
    const error = ref(null)

    const isAuthenticated = computed(() => !!token.value && !!user.value)
    const currentUser = computed(() => user.value)


    async function login(credentials) {
        try {
            loading.value = true
            error.value = null

            const response = await authService.login(credentials)
            const response_data = response.data;
            token.value = response_data.token
            user.value = response_data.user
            
            localStorage.setItem('token', response_data.token)

            return true
        } catch (err) {
            error.value = err.message || 'Erro ao fazer login'
            return false
        } finally {
            loading.value = false
        }
    }

    async function register(userData) {
        try {
            loading.value = true
            error.value = null

            const response = await authService.register(userData)
            token.value = response.token
            user.value = response.user

            localStorage.setItem('token', response.token)

            return true
        } catch (err) {
            error.value = err.message || 'Erro ao registrar'
            return false
        } finally {
            loading.value = false
        }
    }

    async function logout() {
        try {
            loading.value = true
            await authService.logout()
            clearAuth()
        } catch (err) {
            console.error('Erro ao fazer logout:', err)
            clearAuth()
        } finally {
            loading.value = false
        }
    }

    async function checkAuth() {
        if (!token.value) {
            clearAuth()
            return false
        }

        try {
            const response = await authService.me()
            const response_data = response.data;
            user.value = response_data.user
            return true
        } catch (err) {
            clearAuth()
            return false
        }
    }

    function clearAuth() {
        user.value = null
        token.value = null
        localStorage.removeItem('token')
    }

    return {
        // State
        user,
        token,
        loading,
        error,
        // Getters
        isAuthenticated,
        currentUser,
        // Actions
        login,
        register,
        logout,
        checkAuth,
        clearAuth
    }
})