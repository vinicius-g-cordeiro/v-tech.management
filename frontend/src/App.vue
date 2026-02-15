<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/Auth/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const user = computed(() => authStore.sessionUser)
// change the layout based on the current user
const layout = computed(() => {
    return user.value ? 'Default' : 'Guest'
})

</script>

<template>
    <component :is="layout">
        <router-view />
    </component>
</template>

<style scoped>
    
</style>