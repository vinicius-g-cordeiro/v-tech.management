<script setup>
import { ref } from 'vue';

defineProps({
    toggleDrawer: {
        type: Function,
        required: true
    },
    isDrawerOpen: {
        type: Boolean,
        required: true
    }
})

</script>

<template>
    <button type="button" class="flex items-center justify-center gap-1 transition duration-300 cursor-pointer hover:border-b-2 hover:border-emerald-500" @click="toggleDrawer">
        <i class="bi" :class="isDrawerOpen ? 'bi-layout-sidebar-reverse' : 'bi-layout-sidebar'" />
    </button>
</template>