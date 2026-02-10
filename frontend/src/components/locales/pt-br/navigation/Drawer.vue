<template>
    <div class="fixed top-0 left-0 z-50 hidden w-64 h-full transition duration-300 transform -translate-x-full bg-neutral-50 dark:bg-neutral-900" data-drawer v-model:visible="isDrawerOpen">
    </div>
</template>

<script setup>
import { ref } from 'vue';

const isDrawerOpen = ref(false)
const toggleDrawer = () => {
    const drawer = document.querySelector('[data-drawer]');

    if (drawer.classList.contains('translate-x-0')) {
        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');
    } else {
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
    }

    isDrawerOpen.value = !isDrawerOpen.value

    localStorage.setItem('isDrawerOpen', isDrawerOpen.value);
}


</script>