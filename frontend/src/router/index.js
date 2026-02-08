import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore';

const routes = [
    {
        path: '/pt-br/',
        component: () => import('@/components/shared/layouts/Guest.vue'),
        children: [
            {
                path: '',
                component: () => import('@/views/locales/pt-br/Welcome.vue'),
            },
            {
                path: 'sobre/',
                component: () => import('@/views/locales/pt-br/About.vue'),
            },
            {
                path: 'contato/',
                component: () => import('@/views/locales/pt-br/Contact.vue'),
            },
            {
                path: 'login/',
                component: () => import('@/views/locales/pt-br/authentication/Login.vue'),
            },
            {
                path: 'registro/',
                component: () => import('@/views/locales/pt-br/authentication/Register.vue'),
            },
        ],
        
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        
        next('/login')
    } else {
        next()
    }
})

export default router