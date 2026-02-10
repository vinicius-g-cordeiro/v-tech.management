<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import Header from '@/components/locales/pt-br/navigation/Header.vue';
import Footer from '@/components/locales/pt-br/navigation/Footer.vue';
import Sidebar from '@/components/locales/pt-br/navigation/Sidebar.vue';

</script>


<template>
    <Header />
    <main class="min-h-screen dark:bg-neutral-900 bg-neutral-50">
        <router-view />
    </main>
    <Footer />
</template>
