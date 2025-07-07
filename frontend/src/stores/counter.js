// These imports are vital for the counter store to work correctly
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

// This consr function creates a counter store that can be used in any component
export const useCounterStore = defineStore('counter', () => {
  const count = ref(0)
  const doubleCount = computed(() => count.value * 2)
  function increment() {
    count.value++
  }

  // These rules are defined so that you can use them in other components
  return { count, doubleCount, increment }
})
