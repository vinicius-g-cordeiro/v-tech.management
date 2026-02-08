<template>
    <div class="relative inline-block">
        <slot name="button" :toggle="toggle" :open="open" />

        <nav v-show="open" class="absolute left-0 mt-1 bg-neutral-100 dark:bg-neutral-900 rounded-lg shadow-lg z-50">
            <ul class="py-2">
                <slot />
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const open = ref(false)

function toggle() {
    open.value = !open.value
}
</script>
