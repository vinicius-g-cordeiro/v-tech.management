<script setup>
import { ref } from 'vue'
import { computed } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const user = ref(authStore.currentUser)

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