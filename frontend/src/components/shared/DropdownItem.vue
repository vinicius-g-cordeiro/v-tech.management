<template>
    <li>
        <RouterLink :to="to" class="block px-4 py-2 text-sm text-neutral-900 dark:text-neutral-50 hover:border-b-2 hover:border-emerald-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition duration-300">
            <slot />
        </RouterLink>
    </li>
</template>

<script setup>
defineProps({
    to: {
        type: [String, Object],
        required: true
    }
})
</script>
