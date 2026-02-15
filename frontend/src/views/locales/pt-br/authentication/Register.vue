<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/Auth/authStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const errors = ref({})

const handleSubmit = async () => {
    errors.value = {}


    // Validação básica

    if (!form.value.name) {
        errors.value.name = 'Nome é obrigatório'
        return
    }

    if (!form.value.email) {
        errors.value.email = 'Email é obrigatório'
        return
    }

    if (!form.value.username) {
        errors.value.username = 'Username é obrigatório'
        return
    }

    if (!form.value.password) {
        errors.value.password = 'Senha é obrigatória'
        return
    }

    if (form.value.password !== form.value.password_confirmation) {
        errors.value.password = 'As senhas devem ser iguais'
        return
    }

    if (form.value.password.length < 6) {
        errors.value.password = 'A senha deve ter pelo menos 6 caracteres'
        return
    }

    if (form.value.password.length > 30) {
        errors.value.password = 'A senha deve ter no máximo 30 caracteres'
        return
    }

    if (!form.value.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errors.value.email = 'Email inválido'
        return
    }

    const success = await authStore.register(form.value)

    if (success) {
        // Redireciona para a página solicitada ou dashboard
        const redirect = route.query.redirect || '/pt-br/login/'
        router.push(redirect)
    } else {
        errors.value.general = authStore.error
        console.log(authStore)
    }
}
</script>

<template>
    <div class="container flex items-center justify-center h-screen mx-auto">
        <div class="w-full max-w-4xl px-8 pt-6 pb-8 mb-4 rounded-md shadow-lg auth-card bg-neutral-50 dark:bg-neutral-800">
            <h1 class="mb-4 text-3xl font-bold text-center text-emerald-600 dark:text-emerald-200 ">Registrar</h1>
            <div class="mt-4 text-sm">
                <p class="flex items-center justify-end gap-1 text-neutral-600 dark:text-neutral-200">
                    Já possui uma conta?
                    <router-link to="/pt-br/login" class="text-emerald-600 dark:text-emerald-200 hover:underline">Login</router-link>
                </p>
            </div>
            <form @submit.prevent="handleSubmit" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="_method" value="POST">
                <div v-if="errors.general" class="flex w-full gap-2 px-4 py-2 mb-4 text-center text-white bg-red-500 alert alert-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ errors.general }}
                </div>


                <div class="flex grow">
                    <div class="w-full">
                        <label for="email" class="mb-4 font-semibold">Email:</label>
                        <input id="email" v-model="form.email" name="email" type="text" required autocomplete="off" placeholder="Ex: emailpessoal@email.com"
                            class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                        <span v-if="errors.email" class="text-sm text-red-500">
                            {{ errors.email }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="w-full">
                        <label for="name" class="mb-4 font-semibold">Nome:</label>
                        <input id="name" v-model="form.name" name="name" type="text" required autocomplete="off" placeholder="Ex: João"
                            class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                        <span v-if="errors.name" class="text-sm text-red-500">
                            {{ errors.name }}
                        </span>
                    </div>

                    <div class="w-full">
                        <label for="username" class="mb-4 font-semibold">Nome de Usuário:</label>
                        <input id="username" v-model="form.username" name="username" type="text" required autocomplete="off" placeholder="Ex: joao.123"
                            class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                        <span v-if="errors.username" class="text-sm text-red-500">
                            {{ errors.username }}
                        </span>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-6">
                    <div class="w-full">
                        <label for="password" class="mb-4 font-semibold">Senha:</label>
                        <div class="relative">
                            <input id="password" v-model="form.password" name="password" type="password" required autocomplete="off" placeholder="**********"
                                class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                            <p class="flex items-start mt-2 text-xs text-neutral-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-1.5">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                Use 8 ou mais caracteres com letras, números e simbolos especiais
                            </p>
                            <span v-if="errors.password" class="text-sm text-red-500">
                                {{ errors.password }}
                            </span>
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="password_confirmation" class="mb-4 font-semibold">Confirmar Senha:</label>
                        <div class="relative">
                            <input id="password_confirmation" v-model="form.password_confirmation" type="password" required autocomplete="off" placeholder="**********"
                                class="w-full py-2 pl-3 pr-3 text-sm transition duration-300 bg-transparent border rounded-md shadow-sm placeholder:text-neutral-400 text-neutral-600 border-neutral-200 ease focus:outline-none focus:border-neutral-400 hover:border-neutral-300 focus:shadow" />
                            <span v-if="errors.password_confirmation" class="text-sm text-red-500">
                                {{ errors.password_confirmation }}
                            </span>
                        </div>
                    </div>
                </div>



                <button type="submit" :disabled="authStore.loading" class="px-4 py-2 transition duration-300 rounded-md bg-emerald-500 ease hover:bg-emerald-600 text-neutral-900 dark:text-neutral-100" method="POST">
                    {{ authStore.loading ? 'Registrando...' : 'Registrar' }}
                </button>
            </form>

        </div>
    </div>
</template>
