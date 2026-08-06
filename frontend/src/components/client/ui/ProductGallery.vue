<template>
  <div class="lg:col-span-6 flex flex-col-reverse md:flex-row gap-4">
    <!-- Vertical Thumbnail Stack -->
    <div v-if="productImages.length > 1" class="flex md:flex-col gap-3 md:w-[100px] shrink-0">
      <button 
        v-for="(img, idx) in productImages" 
        :key="idx"
        @click="activeImageIdx = idx"
        class="w-16 h-20 md:w-full md:h-[120px] border overflow-hidden transition-all duration-200"
        :class="activeImageIdx === idx ? 'border-black opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100'"
      >
        <img :src="img" alt="Thumbnail" class="w-full h-full object-cover">
      </button>
    </div>

    <!-- Main Large Image View -->
    <div class="grow aspect-[3/4] max-h-[600px] md:max-h-[650px] relative bg-gray-50 border border-gray-100 overflow-hidden">
      <img :src="productImages[activeImageIdx]" alt="Main Product View" class="w-full h-full object-cover transition-all duration-300">
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  productImages: {
    type: Array,
    required: true,
    default: () => []
  }
})

const activeImageIdx = ref(0)

// Reset index when images change
watch(() => props.productImages, () => {
  activeImageIdx.value = 0
}, { deep: true })
</script>
