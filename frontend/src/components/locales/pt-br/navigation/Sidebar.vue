<template>
    <slot name="button" :toggle="toggleSidebar" :open="isSidebarOpen" />
    <nav class="fixed top-0 left-0 z-50 flex flex-col h-full transition duration-300 shadow-lg dark:shadow-sm w-72 bg-neutral-50 dark:bg-neutral-900 dark:shadow-neutral-100"  data-sidebar aria-label="Sidebar" v-bind:class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <slot name="closeButton" :toggle="toggleSidebar" :open="isSidebarOpen" @click="isSidebarOpen = false">
            <ul class="flex items-center justify-start gap-2 p-2 align-middle ">
                <router-link to="/pt-br/" class="flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" title="Home">
                    <img class="w-10 h-10 rounded-full" src="@/assets/logo.png" alt="Logo">
                    <span class="font-bold uppercase text-md text-emerald-500 dark:text-emerald-500 "> V-Tech Management </span>
                </router-link>
                <button type="button" class="flex items-center justify-center gap-1 transition duration-300 cursor-pointer ms-auto me-4 justify-self-end hover:border-b-2 hover:border-emerald-500" @click="toggleSidebar">
                    <i class="bi bi-x-lg"/>
                </button>
            </ul>
        </slot>

        <ul class="flex flex-col justify-start gap-2 px-4 py-2">
            <slot name="start" :items="items"  />
        </ul>

        <ul class="flex flex-col justify-end gap-2 px-4 py-2 mt-auto justify-self-end">
            <slot name="end" :items="items" />
        </ul>
    </nav>
</template>

<script setup>
import { ref } from 'vue';

const items = ref([])


const isSidebarOpen = ref(false)
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value

    localStorage.setItem('isSidebarOpen', isSidebarOpen.value);

    const sidebarEl = document.querySelector('[data-sidebar]');
    if (isSidebarOpen.value === false) {
        sidebarEl.classList.remove('fixed');
        sidebarEl.classList.add('absolute');
    } else {
        sidebarEl.classList.remove('absolute');
        sidebarEl.classList.add('fixed');
    }
}

</script>