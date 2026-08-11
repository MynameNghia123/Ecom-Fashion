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
        <img :src="fullUrl(img)" alt="Thumbnail" class="w-full h-full object-cover">
      </button>
    </div>

    <!-- Main Large Image View — fixed container, zoom only inside -->
    <div class="grow" style="position: relative; aspect-ratio: 3/4; max-height: 650px; background: #f9f9f9; border: 1px solid #e5e7eb; overflow: hidden;">
      <img
        v-if="productImages && productImages.length > 0"
        :src="fullUrl(productImages[activeImageIdx])"
        alt="Main Product View"
        class="gallery-main-img"
        :class="{ 'zoomed': isZoomed }"
        :style="isZoomed ? { transformOrigin: zoomOrigin, transform: 'scale(2)' } : {}"
        @mouseenter="isZoomed = true"
        @mouseleave="isZoomed = false"
        @mousemove="onMouseMove"
      />
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
const isZoomed = ref(false)
const zoomOrigin = ref('50% 50%')

const fullUrl = (path) => {
  if (!path) return ''
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const onMouseMove = (e) => {
  const rect = e.currentTarget.getBoundingClientRect()
  const x = ((e.clientX - rect.left) / rect.width) * 100
  const y = ((e.clientY - rect.top) / rect.height) * 100
  zoomOrigin.value = `${x}% ${y}%`
}

// Reset index when images change
watch(() => props.productImages, () => {
  activeImageIdx.value = 0
}, { deep: true })
</script>

<style scoped>
.gallery-main-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  cursor: zoom-in;
  transition: transform 0.15s ease;
  will-change: transform;
}

.gallery-main-img.zoomed {
  cursor: zoom-in;
}
</style>
