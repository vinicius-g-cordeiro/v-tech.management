<template>
    <footer class="flex items-center justify-center w-full h-10 shadow-lg bg-neutral-200 dark:bg-neutral-800">

        <main class="flex flex-row items-center">
            <p class="mx-2 text-sm text-neutral-900 dark:text-neutral-50">Desenvolvido por </p>
            <span class="font-nunito text-neutral-900 dark:text-neutral-50">Vinicius Gonçalves Cordeiro </span>
        </main>

        <aside class="flex flex-row items-center">
            <p class="mx-2 text-sm text-neutral-900 dark:text-neutral-50">|</p>
            <a href="https://github.com/vinicius-g-cordeiro" target="_blank" class="flex gap-1 font-bold text-neutral-900 dark:text-neutral-50 hover:underline"><i class="bi bi-github"></i>GitHub</a>
        </aside>
        
        <aside class="flex flex-row items-center">
            <p class="mx-2 text-sm text-neutral-900 dark:text-neutral-50">|</p>
            <a href="https://www.linkedin.com/in/vinicordeirox/" target="_blank" class="flex gap-1 font-bold text-neutral-900 dark:text-neutral-50 hover:underline"><i class="bi bi-linkedin"></i>Linkedin</a>
        </aside>

        <main class="flex flex-row items-center">
            <p class="mx-2 text-sm text-neutral-900 dark:text-neutral-50">|
                <i class="bi bi-c-circle"></i>
            </p>
            <p class="font-bold text-neutral-900 dark:text-neutral-50" v-html="new Date().getFullYear()"></p>
        </main>

    </footer>
</template>

<script setup>

</script>