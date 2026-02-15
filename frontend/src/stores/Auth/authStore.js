import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService from '@/services/Auth/authService'


export const useAuthStore = defineStore('auth', {
    state: () => ({
        sessionUser: null,
        loading: false,
        error: null,
        hydration: false
    }),

    getters: {
        isAuthenticated: (state) => !!state.sessionUser,
    },

    actions: {
        async checkAuth() {
            this.loading = true
            try {
                const response = await authService.me()
                if (!response.ok) {
                    this.sessionUser = null
                    return
                }
                const res = await response.json()
                this.sessionUser = res.data.user
                return true;
            } catch (e) {
                this.sessionUser = null
                return false;
            } finally {
                this.loading = false
            }
        },

        async login(credentials) {
            try {
                this.loading = true
                this.error = null
                const response = await authService.login(credentials)
                this.sessionUser = response.data.user
                return true;
            } catch (e) {                
                this.error = e.response.data.message || 'Erro ao fazer login'
                return false;
            } finally {
                this.loading = false
            }
        },

        async register(userData) {
            try {
                this.loading = true
                this.error = null
                const response = await authService.register(userData)
                return true;
            } catch (e) {
                this.error = e.response.data.message || 'Erro ao registrar'
                return false;
            } finally {
                this.loading = false
            }
        },

        async logout() {
            try{
                this.loading = true
                this.error = null
                const response = await authService.logout()
                this.sessionUser = null
                return true;
            } catch (e) {
                this.error = e.response.data.message || 'Erro ao fazer logout'
                return false;
            } finally {
                this.loading = false
            }
        },

        async fetchUser() {
            try {
                this.loading = true
                const response = await authService.me()
                this.sessionUser = response.data.user
            } catch (e) {
                this.sessionUser = null
            } finally {
                this.loading = false
                this.hydration = true
            }
        }
    }
})