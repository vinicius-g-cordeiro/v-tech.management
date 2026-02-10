<template>
    <button type="button" class="flex items-center w-10 h-10 gap-1 transition duration-300 cursor-pointer hover:border-b-2 hover:border-emerald-500" @click.stop="toggle">
        <slot />
        <i class="bi" :class="open ? 'bi-caret-down-fill' : 'bi-caret-right-fill'" />
    </button>
</template>

<script setup>
defineProps({
    toggle: {
        type: Function,
        required: true
    },
    open: {
        type: Boolean,
        required: true
    }
})
</script>
