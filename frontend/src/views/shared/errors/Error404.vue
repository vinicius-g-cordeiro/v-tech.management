<template>
    <Header />
    <main class="flex flex-col items-center justify-center min-h-screen align-middle">
        <h1 class="text-2xl font-bold text-center text-neutral-900 dark:text-neutral-50">Error - 404</h1>
        <i class="text-emerald-500 bi bi-emoji-frown-fill text-9xl "></i>
        <p class="text-center text-neutral-900 dark:text-neutral-50">Desculpe, essa página não foi encontrada.</p>
        <router-link to="/pt-br/" class="mt-10 text-2xl font-bold text-center transition duration-300 text-emerald-500 hover:underline">Voltar para o início</router-link>
    </main>
</template>

<script setup>
import Header from '@/components/locales/pt-br/navigation/Header.vue';

</script>
