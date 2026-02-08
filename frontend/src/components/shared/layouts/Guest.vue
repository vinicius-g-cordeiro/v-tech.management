<!-- frontend/src/components/layout/Auth.vue -->
<script setup>
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

import Header from '@/components/locales/pt-br/navigation/Header.vue';
</script>

<template>
    <Header />
    <main class="dark:bg-neutral-900 bg-neutral-50 min-h-screen">
        <div class="div-content flex flex-col min-h-screen">
            <router-view />
        </div>
    </main>
</template>
