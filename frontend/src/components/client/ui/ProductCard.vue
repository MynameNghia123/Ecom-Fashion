<template>
  <div class="group flex flex-col gap-[15px] select-none">
    <!-- Image Wrapper (Clickable) -->
    <div 
      @click="goToProduct"
      class="relative overflow-hidden bg-[#f8f8f8] aspect-[3/4] cursor-pointer"
    >
      <span v-if="discount" class="absolute top-[15px] left-[15px] bg-white text-black font-text text-[12px] font-bold px-2 py-1 z-[2] shadow-xs">{{ discount }}</span>
      <img :src="image" :alt="name" class="w-full h-full object-cover">
      
      <!-- Wishlist delete close button (for profile wishlist mode) -->
      <button 
        v-if="isWishlist" 
        @click.stop="emit('remove')" 
        class="absolute top-[15px] right-[15px] bg-white hover:bg-neutral-100 border-none w-7 h-7 rounded-full flex items-center justify-center cursor-pointer text-black transition-colors z-[3] shadow-[0_2px_5px_rgba(0,0,0,0.1)]"
        aria-label="Xóa khỏi danh sách"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <!-- Standard Hover Actions -->
      <div v-else class="absolute top-[15px] right-[15px] flex flex-col gap-2.5 opacity-0 translate-x-[10px] transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-x-0 z-[2]">
        <!-- Heart Button with Pop Animation -->
        <button 
          @click.stop="handleToggleWishlist"
          class="bg-white border-none w-9 h-9 rounded-full flex items-center justify-center cursor-pointer transition-all duration-300 shadow-[0_2px_5px_rgba(0,0,0,0.1)] hover:scale-110 active:scale-95"
          :class="[
            isWishlisted ? 'text-rose-500 bg-rose-50' : 'text-black hover:bg-black hover:text-white',
            isHeartPopping ? 'animate-heart-pop' : ''
          ]"
          :title="isWishlisted ? 'Bỏ khỏi yêu thích' : 'Thêm vào yêu thích'"
          aria-label="Yêu thích"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" :fill="isWishlisted ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
        </button>

        <!-- Quick View / Detail Button -->
        <button 
          @click.stop="goToProduct"
          class="bg-white border-none w-9 h-9 rounded-full flex items-center justify-center cursor-pointer text-black transition-all duration-300 shadow-[0_2px_5px_rgba(0,0,0,0.1)] hover:bg-black hover:text-white" 
          title="Xem chi tiết sản phẩm"
          aria-label="Xem chi tiết"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
        </button>
      </div>

      <!-- MUA NGAY sliding hover button -->
      <button
        @click.stop="goToProduct"
        class="absolute bottom-0 left-0 w-full bg-white text-black border-none py-[15px] font-text text-[13px] font-bold cursor-pointer translate-y-full transition-transform duration-300 ease-in-out group-hover:translate-y-0 z-[2] hover:bg-black hover:text-white tracking-wider uppercase"
      >
        MUA NGAY
      </button>
    </div>

    <!-- Product Details (Clickable) -->
    <div 
      @click="goToProduct"
      class="cursor-pointer"
    >
      <div v-if="isWishlist" class="flex flex-col gap-[3px]">
        <div class="flex justify-between items-start gap-4">
          <h3 class="font-text text-[13px] font-bold m-0 text-black uppercase tracking-wide leading-snug hover:underline">{{ name }}</h3>
          <span class="font-text text-[13px] font-bold text-black">{{ currentPrice }}</span>
        </div>
        <p v-if="description" class="text-[11px] font-text text-neutral-500 uppercase tracking-wider font-semibold m-0 leading-normal">{{ description }}</p>
      </div>

      <div v-else class="flex flex-col gap-[5px]">
        <h3 class="font-text text-[15px] font-medium m-0 text-black hover:underline">{{ name }}</h3>
        <div class="font-text text-[15px] flex gap-[10px] items-center">
          <span class="font-semibold text-black">{{ currentPrice }}</span>
          <span v-if="originalPrice" class="text-[#888] line-through text-[14px]">{{ originalPrice }}</span>
        </div>
        <div v-if="rating" class="flex items-center gap-2 mt-[5px]">
          <div class="text-[#ccc] text-[14px] tracking-[2px]">
            <span v-for="(star, index) in rating.stars" :key="index" :class="['inline-block', star === 'filled' ? 'text-[#ffc107]' : star === 'half-filled' ? 'relative text-[#ccc] after:content-[\'★\'] after:absolute after:left-0 after:top-0 after:text-[#ffc107] after:overflow-hidden after:w-1/2' : 'text-[#ccc]']">★</span>
          </div>
          <span class="font-text text-[12px] text-[#666] font-medium">{{ rating.score }} ({{ rating.count }})</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useWishlistStore } from '@/stores/client/wishlistStore'

const props = defineProps({
  id: { type: [Number, String], default: null },
  slug: { type: String, default: null },
  image: { type: String, required: true },
  name: { type: String, required: true },
  currentPrice: { type: String, required: true },
  originalPrice: { type: String, default: null },
  discount: { type: String, default: null },
  rating: { type: Object, default: null },
  description: { type: String, default: null },
  isWishlist: { type: Boolean, default: false }
});

const emit = defineEmits(['remove']);
const router = useRouter()
const wishlistStore = useWishlistStore()

const isHeartPopping = ref(false)

const isWishlisted = computed(() => {
  if (!props.id) return false
  return wishlistStore.isInWishlist(props.id)
})

const handleToggleWishlist = () => {
  if (!props.id) return
  
  // Trigger pop animation
  isHeartPopping.value = true
  setTimeout(() => {
    isHeartPopping.value = false
  }, 400)

  wishlistStore.toggleWishlist({
    id: props.id,
    slug: props.slug,
    name: props.name,
    currentPrice: props.currentPrice,
    originalPrice: props.originalPrice,
    image: props.image,
    description: props.description
  })
}

const goToProduct = () => {
  const target = props.slug || props.id
  if (!target) return
  router.push({ name: 'ProductDetail', params: { slug: target } })
}
</script>

<style scoped>
@keyframes heartPop {
  0%   { transform: scale(1); }
  40%  { transform: scale(1.35) rotate(-10deg); }
  70%  { transform: scale(0.9) rotate(5deg); }
  100% { transform: scale(1) rotate(0deg); }
}

.animate-heart-pop {
  animation: heartPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
</style>
