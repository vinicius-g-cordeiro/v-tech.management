<template>
    <button type="button" class="flex flex-row items-center w-full h-10 gap-1 transition duration-300 cursor-pointer" @click.stop="toggle">
        <slot />
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
