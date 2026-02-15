import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/Auth/authStore';

const routes = [
    {
        path: '/pt-br/',
        component: () => import('@/components/shared/layouts/Guest.vue'),
        meta: {
            requiresGuest: true
        },
        children: [
            {
                path: '',
                name: 'welcome',
                component: () => import('@/views/locales/pt-br/Welcome.vue'),
            },
            {
                path: 'sobre/',
                name: 'about',
                component: () => import('@/views/locales/pt-br/About.vue'),
            },
            {
                path: 'contato/',
                name: 'contact',
                component: () => import('@/views/locales/pt-br/Contact.vue'),
            },
            {
                path: 'login/',
                name: 'login',
                component: () => import('@/views/locales/pt-br/authentication/Login.vue'),
                meta: { title: 'Login' },
            },
            {
                path: 'registro/',
                name: 'register',
                component: () => import('@/views/locales/pt-br/authentication/Register.vue'),
                meta: { title: 'Registro' },
            },
        ],   
    },
    {
        path: '/pt-br/',
        component: () => import('@/components/shared/layouts/Default.vue'),
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: 'dashboard/',
                name: 'dashboard',
                component: () => import('@/views/locales/pt-br/dashboard/Dashboard.vue'),
                meta: { title: 'Dashboard' },
            },
        ],
    },
    {
        // not found / errors
        path: '/pt-br/:pathMatch(.*)*',
        component: () => import('@/views/shared/errors/Error404.vue'),
        meta: {
            requiresAuth: false
        },

    },
    {
        // not found / errors
        path: '/:pathMatch(.*)*',
        component: () => import('@/views/shared/errors/Error404.vue'),
        meta: {
            requiresAuth: false
        },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to) => {
    const authStore = useAuthStore()
    document.title = to.meta.title ? `${to.meta.title} - V-Tech Sistemas` : 'V-Tech Sistemas'

    if (!authStore.hydration) {
        await authStore.fetchUser()
    }

    const isAuthenticated = authStore.isAuthenticated
    const requiresAuth = to.matched.some(r => r.meta.requiresAuth)
    const requiresGuest = to.matched.some(r => r.meta.requiresGuest)

    // Block guests from protected pages
    if (requiresAuth && !isAuthenticated) {
        return {
            name: 'login',
            query: { redirect: to.fullPath }
        }
    }

    // Block logged users from guest pages
    if (requiresGuest && isAuthenticated) {
        return { name: 'dashboard' }
    }

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return { name: 'login' }
    }

})

export default router