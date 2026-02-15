<template>
    <li>
        <RouterLink :to="to || '#'" class="flex items-center min-w-[200px] w-full gap-2 px-4 py-2 text-sm transition duration-300 text-neutral-900 dark:text-neutral-50 hover:border-s-2 hover:border-emerald-500 hover:bg-neutral-100 dark:hover:bg-neutral-800">
            <slot />
        </RouterLink>
    </li>
</template>

<script setup>
defineProps({
    to: {
        type: [String, Object],
        required: false
    }
})
</script>
