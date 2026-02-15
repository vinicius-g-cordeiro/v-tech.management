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
                    <router-link to="/pt-br/login/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Login" v-if="!authStore.sessionUser">
                        <i class="bi bi-door-open-fill"></i>
                        Login
                    </router-link>

                    <router-link to="/pt-br/registro/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Registrar" v-if="!authStore.sessionUser">
                        <i class="bi bi-person-plus"></i>
                        Registrar
                    </router-link>
                    <hr class="h-px bg-transparent border-t-0 shadow-lg opacity-25 bg-gradient-to-r from-transparent via-neutral-500 to-transparent dark:via-neutral-400 dark:shadow-sm dark:shadow-neutral-100" />
                    <ProfileDropdown position="top-right">
                        <template #button="{ toggle, open }">
                            <ProfileDropdownButton :toggle="toggle" :open="open" v-if="authStore.sessionUser">
                                <img class="w-8 h-8 rounded-full" :src="authStore.sessionUser.avatar || 'https://ui-avatars.com/api/?name=' + authStore.sessionUser.name" :alt="authStore.sessionUser.name">
                                <section class="flex flex-col w-full gap-1">
                                    <span class="text-xs font-semibold leading-tight text-neutral-900 dark:text-neutral-50">
                                        {{ authStore.sessionUser.name }} {{ authStore.sessionUser.lastname }}
                                    </span>
                                    <span class="text-xs font-semibold leading-tight text-neutral-900 dark:text-neutral-50">
                                        {{ authStore.sessionUser.email }}
                                    </span>
                                </section>
                                <section class="flex items-center gap-1">
                                    <i :class="open ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill'"></i>
                                </section>
                            </ProfileDropdownButton>
                        </template>
                        
                        <ProfileDropdownItem :to="'/pt-br/perfil'">
                            <i class="bi bi-person-fill"></i>
                            Perfil
                        </ProfileDropdownItem>
                        <ProfileDropdownItem :to="'/pt-br/configuracoes'">
                            <i class="bi bi-gear-fill"></i>
                            Configurações
                        </ProfileDropdownItem>
                        <ProfileDropdownItem @click="handleLogout">
                            <i class="bi bi-box-arrow-right"></i>
                            Sair
                        </ProfileDropdownItem>
                    
                    </ProfileDropdown>
                </template>
            </Sidebar>
        </ul>
        <ul class="flex items-center gap-4 px-4" >

            <li class="flex items-center gap-1 border-b-0 cursor-pointer text-neutral-900 dark:text-neutral-50 hover:border-b-2 hover:border-b-emerald-500" v-if="authStore.sessionUser">
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
import { useAuthStore } from "@/stores/Auth/authStore";
import { useRouter } from "vue-router";
import ProfileDropdown from "@/components/locales/pt-br/navigation/ProfileDropdown.vue";
import ProfileDropdownButton from "@/components/locales/pt-br/navigation/ProfileDropdownButton.vue";
import ProfileDropdownItem from "@/components/locales/pt-br/navigation/ProfileDropdownItem.vue";
const items = ref([])
const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
    await authStore.logout()
    router.replace({ name: 'login' })
}
</script>
