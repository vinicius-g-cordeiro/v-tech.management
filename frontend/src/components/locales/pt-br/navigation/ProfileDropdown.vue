<template>
    <!-- When clicked outside close the dropdown -->
    <div class="relative flex items-center gap-1 text-neutral-900 dark:text-neutral-50 hover:text-emerald-500 dark:hover:text-emerald-500 hover:border-b-emerald-500" ref="dropdownRef">
        <slot name="button" :toggle="toggle" :open="open" />
        <nav v-show="open" :class="['absolute z-50 w-full rounded-lg shadow-lg bg-neutral-200 dark:bg-neutral-800' , positionClasses ]">
            <ul class="py-2">
                <slot />
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useClickOutside } from '@/components/shared/useClickOutside'

const props = defineProps({
    position: {
        type: String,
        default: 'bottom-right'
    }
})

const positionClasses = computed(() => {
  switch (props.position) {
    case 'bottom-right':
      return 'right-0 top-full'
    case 'top-left':
      return 'left-0 bottom-full mb-2'
    case 'top-right':
      return 'right-0 bottom-full mb-2'
    case 'center':
      return 'left-1/2 -translate-x-1/2 top-full'
    default:
      return 'left-0 top-full'
  }
})

const open = ref(false)
const dropdownRef = ref(null)

function toggle() {
    open.value = !open.value
}

useClickOutside(dropdownRef, () => open.value = false)
</script>
