<script setup>
import { ref } from 'vue';
import Dropdown from '@/components/shared/Dropdown.vue';
import DropdownButton from '@/components/shared/DropdownButton.vue';
import DropdownItem from '@/components/shared/DropdownItem.vue';

const darkMode = ref(localStorage.getItem('darkMode') === 'true');

const toggleDarkMode = () => {

    darkMode.value = !darkMode.value;

    if (darkMode.value) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    localStorage.setItem('darkMode', darkMode.value);
};


</script>

<template>
    <header class="header w-full p-1 bg-neutral-200 shadow-lg top-0" id="header">
        <nav class="flex items-center gap-4 w-full">
            <ul class="flex items-center justify-start w-full">
                <li>
                    <router-link class="flex justify-center" to="/pt-br/">
                        <img class="h-12" src="@/assets/logo.png" alt="Logo" />
                    </router-link>
                </li>
            </ul>
            <ul class="flex items-center gap-4 w-full justify-center">
                <li><router-link class="gap-1 flex justify-center align-center items-center hover:text-emerald-300 hover:border-b-2 hover:border-emerald-500 border-b-0 transition duration-300" to="/pt-br/"><i class="bi bi-house-fill"></i>Início</router-link></li>
                <li><router-link class="gap-1 flex justify-center align-center items-center hover:text-emerald-300 hover:border-b-2 hover:border-emerald-500 border-b-0 transition duration-300" to="/pt-br/sobre/"><i class="bi bi-info-circle-fill"></i>Sobre</router-link></li>
                <li><router-link class="gap-1 flex justify-center align-center items-center hover:text-emerald-300 hover:border-b-2 hover:border-emerald-500 border-b-0 transition duration-300" to="/pt-br/contato/"><i class="bi bi-telephone-fill"></i>Contato</router-link></li>
            </ul>
            <ul class="flex items-center gap-4">
                <li class="flex justify-center align-center items-center w-68"> <i class="bi bi-search"></i>
                    <form class="flex justify-center align-center items-center"><input class="ms-2 mr-2 p-1 bg-neutral-50 dark:bg-neutral-900 rounded-lg dark:text-neutral-50 w-62" type="text" placeholder="Pesquisar..."></form>
                </li>
                <li class="me-4">
                    <Dropdown>
                        <template #button="{ toggle, open }">
                            <DropdownButton :toggle="toggle" :open="open">
                                <img class="rounded-full w-12 h-10" src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=Vinicius+Cordeiro" alt="Avatar" />
                            </DropdownButton>
                        </template>

                        <DropdownItem to="/pt-br/perfil/">
                            Perfil
                        </DropdownItem>

                        <DropdownItem to="/pt-br/logout">
                            Logout
                        </DropdownItem>
                    </Dropdown>
                </li>
                <li><router-link class="gap-1 flex justify-center align-center items-center hover:text-emerald-300 hover:border-b-2 hover:border-emerald-500 border-b-0 transition duration-300" to="/pt-br/login"><i class="bi bi-door-open-fill "></i>Login</router-link></li>
                <li><router-link class="gap-1 flex justify-center align-center items-center hover:text-emerald-300 hover:border-b-2 hover:border-emerald-500 border-b-0 transition duration-300" to="/pt-br/registro/"><i class="bi bi-person-plus-fill"></i>Registrar</router-link></li>
            </ul>
            <ul class="flex items-center gap-4 justify-end">

                <li> <i class="bi bi-moon-fill text-neutral-50" v-if="darkMode" @click="toggleDarkMode"> </i> <i class="bi bi-sun-fill text-yellow-400" @click="toggleDarkMode" v-else> </i></li>
            </ul>
        </nav>
    </header>
</template>