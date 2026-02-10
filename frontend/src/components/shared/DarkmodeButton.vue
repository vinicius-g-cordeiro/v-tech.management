<script setup>
import { ref } from 'vue';
const darkMode = ref(localStorage.getItem('darkMode') === 'true');

const toggleDarkMode = () => {

    darkMode.value = !darkMode.value;

    if (darkMode.value) {
        document.documentElement.classList.add('my-app-dark');
    } else {
        document.documentElement.classList.remove('my-app-dark');
    }

    localStorage.setItem('darkMode', darkMode.value);
};
</script>

<template>
    <p>
        <i class="text-yellow-400 cursor-pointer dark:text-neutral-50" :class="darkMode ? 'bi bi-moon-fill' : 'bi bi-sun-fill'" @click="toggleDarkMode"></i>
    </p>
    
</template>
