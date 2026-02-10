<template>
    <header class="flex items-center justify-between w-full h-16 mx-auto shadow-lg dark:shadow-sm dark:shadow-neutral-100 bg-neutral-50 dark:bg-neutral-900 text-neutral-800 dark:text-neutral-400">
        <ul class="flex items-center gap-4 px-4">
            <Sidebar>
                <template #button="{ toggle, open }">
                    <ul class="flex items-center justify-start gap-0 p-2 align-middle ">
                        <router-link to="/pt-br/" class="flex items-center text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Home">
                            <img class="w-10 h-10 rounded-full" src="@/assets/logo.png" alt="Logo">
                        </router-link>
                        <button type="button" class="flex items-center justify-center w-10 h-10 gap-1 mx-1 transition duration-300 cursor-pointer hover:border-b-emerald-500 hover:border-b-2" @click="toggle">
                            <i class="bi" :class="open ? 'bi-layout-sidebar-reverse' : 'bi-layout-sidebar'" />
                        </button>
                    </ul>
                </template>


                <template #start="{ items }">
                    <router-link to="/pt-br/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Home">
                        <i class="bi bi-house-door-fill"></i>
                        Home
                    </router-link>
                    <router-link to="/pt-br/users" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Usuários">
                        <i class="bi bi-people-fill"></i>
                        Usuários
                    </router-link>
                </template>
                <template #end="{ items }">
                    <router-link to="/pt-br/login/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Login" v-if="!authStore.currentUser">
                        <i class="bi bi-door-open-fill"></i>
                        Login
                    </router-link>

                    <router-link to="/pt-br/registro/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Registrar" v-if="!authStore.currentUser">
                        <i class="bi bi-person-plus"></i>
                        Registrar
                    </router-link>
                    <hr class="h-px bg-transparent border-t-0 shadow-lg opacity-25 bg-gradient-to-r from-transparent via-neutral-500 to-transparent dark:via-neutral-400 dark:shadow-sm dark:shadow-neutral-100" />
                    <router-link to="/pt-br/perfil/" class="flex items-center gap-1 p-1 text-sm text-neutral-900 dark:text-neutral-50 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" v-if="authStore.currentUser">
                        <img class="w-10 h-10 rounded-full" :src="authStore.currentUser.avatar || 'https://ui-avatars.com/api/?name=' + authStore.currentUser.name" :alt="authStore.currentUser.name">
                        <ul class="flex flex-col justify-start gap-1 text-neutral-900 dark:text-neutral-50">
                            <li v-if="authStore.currentUser" class="font-bold">{{ authStore.currentUser.name }}</li>
                            <li v-if="authStore.currentUser" class="text-xs">{{ authStore.currentUser.email }}</li>
                        </ul>
                        <ul class="flex flex-col justify-end ms-auto justify-self-end text-neutral-900 dark:text-neutral-50">
                            <!-- options dropdown -->
                            <li>
                                <i class="bi bi-caret-up"></i>
                            </li>
                            <li>
                                <i class="bi bi-caret-down"></i>
                            </li>
                        </ul>


                    </router-link>
                </template>
            </Sidebar>
        </ul>
        <ul class="flex items-center gap-4 px-4" >

            <li class="flex items-center gap-1 border-b-0 cursor-pointer text-neutral-900 dark:text-neutral-50 hover:border-b-2 hover:border-b-emerald-500" v-if="authStore.currentUser">
                <div class="relative rounded-full">
                    <i class="bi bi-bell-fill"></i>
                    <!-- number of notifications -->
                    <span class="absolute top-0 right-0 w-4 h-4 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-500">
                        <span class="absolute inset-0 w-full h-full text-xs font-medium leading-tight text-center text-white">3</span>
                    </span>
                    <span class="sr-only">Notifications</span>
                </div>

            </li>
            <DarkmodeButton />
        </ul>
    </header>
</template>

<script setup>
import { ref } from "vue";
import DarkmodeButton from '@/components/shared/DarkmodeButton.vue'
import Sidebar from "@/components/locales/pt-br/navigation/Sidebar.vue";
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";

const authStore = useAuthStore()
const router = useRouter()



</script>
