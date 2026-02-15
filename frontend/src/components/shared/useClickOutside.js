import { onMounted, onBeforeMount } from 'vue'

export function useClickOutside(elementRef, callback) {
    function handler(event) {
        if (!elementRef.value) return
        if (!elementRef.value.contains(event.target)) {
            callback()
        }
    }
    onMounted(() => {
        document.addEventListener('click', handler)
    })
    onBeforeMount(() => {
        document.removeEventListener('click', handler)
    })
}