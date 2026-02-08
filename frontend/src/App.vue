<script setup>
import { ref } from 'vue'
import { computed } from 'vue'

</script>

<template>
    <component :is="layout">
        <router-view />
        
    </component>
</template>

<style scoped>
    
</style>