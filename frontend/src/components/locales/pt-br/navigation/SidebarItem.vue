<template>
    <a :href="item.path" class="block px-4 py-2 text-sm transition duration-300 text-neutral-900 dark:text-neutral-50 hover:border-b-2 hover:border-emerald-500 hover:bg-neutral-100 dark:hover:bg-neutral-800" aria-current="page" :title="item.title">
        <i class="bi" :class="icon"></i>
        {{ item.name }}
    </a>
</template>