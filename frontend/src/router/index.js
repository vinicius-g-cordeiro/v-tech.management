import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore';



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
            },
            {
                path: 'registro/',
                name: 'register',
                component: () => import('@/views/locales/pt-br/authentication/Register.vue'),
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


router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()
    document.title = to.meta.title ? `${to.meta.title} - App ` : 'App'
    await authStore.checkAuth()
    const isAuthenticated = authStore.isAuthenticated
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const requiresGuest = to.matched.some(record => record.meta.requiresGuest)

    if(requiresAuth && !isAuthenticated){
        return next({ name: 'login' , query: { redirect: to.fullPath } })
    }

    if(requiresGuest && isAuthenticated){
        return next({ name: 'dashboard' })
    }

    next()
})
export default router