<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
    username: '',
    password: '',
})

const errors = ref({})

const handleSubmit = async () => {
    errors.value = {}


    // Validação básica

    if (!form.value.username) {
        errors.value.username = 'Username é obrigatório'
        return
    }

    if (!form.value.password) {
        errors.value.password = 'Senha é obrigatória'
        return
    }

    if (form.value.password.length < 6) {
        errors.value.password = 'A senha deve ter pelo menos 6 caracteres'
        return
    }
    const success = await authStore.login(form.value)

    if (success) {
        // Redireciona para a página solicitada ou dashboard
        const redirect = route.query.redirect || '/'
        router.push(redirect)
    } else {
        errors.value.general = authStore.error
    }
}
</script>

<template>
    <div class="container flex items-center justify-center h-screen mx-auto">
        <div class="w-full max-w-4xl px-8 pt-6 pb-8 mb-4 rounded-md shadow-lg auth-card bg-neutral-50 dark:bg-neutral-800">            
            <h1 class="mb-4 text-3xl font-bold text-center text-emerald-600 dark:text-emerald-200 ">Entrar</h1>
            <div class="mt-4 text-sm">
                <p class="flex items-center justify-end gap-3 text-neutral-600 dark:text-neutral-200">
                    Ainda não tem uma conta?
                    <router-link to="/pt-br/registro" class="text-emerald-600 dark:text-emerald-200 hover:underline">Registrar</router-link>
                </p>
            </div>
            <form @submit.prevent="handleSubmit" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="_method" value="POST">
                <div v-if="errors.general" class="alert alert-error">
                    {{ errors.general }}
                </div>

                
                <div class="flex flex-col items-center justify-between gap-6">
                    <div class="w-full min-w-[200px]">
                        <label for="username" class="mb-4 font-semibold">Usuário/Email/Telefone:</label>
                        <input id="username" v-model="form.username" name="username" type="text" required autocomplete="off" placeholder="Ex: joao.123, joao@gmail.com, 6199798999" class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                        <span v-if="errors.username" class="text-sm text-red-500">
                            {{ errors.username }}
                        </span>
                    </div>
                
                    <div class="w-full min-w-[200px]">
                        <label for="password" class="mb-4 font-semibold">Senha:</label>
                        <div class="relative">
                            <input id="password" v-model="form.password" name="password" type="password" required autocomplete="off" placeholder="**********"
                                class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                            <span v-if="errors.password" class="text-sm text-red-500">
                                {{ errors.password }}
                            </span>
                        </div>
                    </div>               

                    <div class="w-full min-w-[200px]">
                        <router-link to="/pt-br/resetar-senha/" class="text-sm font-semibold transition duration-300 text-emerald-500 hover:text-emerald-600 ease hover:underline">Esqueceu sua senha?</router-link>
                    </div>
                </div>
 
                <button type="submit" :disabled="authStore.loading" class="px-4 py-2 transition duration-300 rounded-md bg-emerald-500 ease hover:bg-emerald-600 text-neutral-900 dark:text-neutral-100" method="POST">
                    {{ authStore.loading ? 'Logando...' : 'Login' }}
                </button>
            </form>

        </div>
    </div>
</template>
