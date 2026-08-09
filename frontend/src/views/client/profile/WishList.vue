<template>
  <div class="space-y-10 animate-fade-in text-[#111111] select-none">
    <!-- Header -->
    <div class="border-b border-neutral-100 pb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div>
        <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title leading-tight">Danh sách yêu thích</h1>
        <p class="text-xs text-neutral-400 mt-1 font-text uppercase tracking-wider font-semibold">Quản lý bộ sưu tập cá nhân của bạn.</p>
      </div>
      <div class="flex items-center gap-6">
        <span class="text-[11px] font-text text-neutral-500 uppercase tracking-widest font-semibold">
          {{ wishlistItems.length }} sản phẩm được lưu
        </span>
        <button
          v-if="wishlistItems.length > 0"
          @click="clearAll"
          class="border border-neutral-300 hover:border-neutral-900 hover:bg-neutral-900 hover:text-white px-5 py-2.5 text-[11px] font-bold tracking-widest uppercase transition-colors duration-300 font-text cursor-pointer text-neutral-700 bg-white"
        >
          Xóa tất cả
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="wishlistStore.loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 py-12">
      <div v-for="i in 3" :key="i" class="animate-pulse space-y-3">
        <div class="aspect-[3/4] bg-neutral-100 rounded"></div>
        <div class="h-4 bg-neutral-100 w-2/3 rounded"></div>
        <div class="h-4 bg-neutral-100 w-1/3 rounded"></div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="wishlistItems.length === 0" class="text-center py-20 border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 000-7.78z"/>
      </svg>
      <p class="text-neutral-500 font-text text-sm">Danh sách yêu thích trống.</p>
      <router-link to="/" class="mt-4 inline-block bg-black text-white hover:bg-neutral-800 px-6 py-3 text-[11px] font-bold tracking-widest uppercase transition-colors font-text">
        Khám phá sản phẩm
      </router-link>
    </div>

    <!-- Product Grid -->
    <div v-else class="space-y-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
        <div
          v-for="product in wishlistItems"
          :key="product.id"
          class="group relative flex flex-col gap-3"
        >
          <!-- Image Container (Clickable) -->
          <div 
            @click="goToDetail(product)"
            class="relative overflow-hidden bg-neutral-100 aspect-[3/4] cursor-pointer"
          >
            <img
              :src="product.image"
              :alt="product.name"
              class="w-full h-full object-cover"
            />

            <!-- Remove from wishlist btn -->
            <button
              @click.stop="removeItem(product.id)"
              class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white border border-neutral-200 flex items-center justify-center hover:bg-rose-50 hover:border-rose-300 transition-colors cursor-pointer z-[3] shadow-xs"
              title="Xóa khỏi yêu thích"
            >
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-rose-500">
                <path d="M18 6L6 18M6 6l12 12"/>
              </svg>
            </button>

            <!-- MUA NGAY sliding hover button -->
            <button
              @click.stop="goToDetail(product)"
              class="absolute bottom-0 left-0 w-full bg-white text-black border-none py-[14px] font-text text-[12px] font-bold cursor-pointer translate-y-full transition-transform duration-300 ease-in-out group-hover:translate-y-0 z-[2] hover:bg-black hover:text-white tracking-wider uppercase"
            >
              MUA NGAY
            </button>
          </div>

          <!-- Info (Clickable) -->
          <div 
            @click="goToDetail(product)"
            class="cursor-pointer space-y-1"
          >
            <p class="text-[11px] uppercase tracking-widest text-neutral-400 font-bold">{{ product.description }}</p>
            <p class="text-sm font-bold text-neutral-900 font-title hover:underline leading-tight">{{ product.name }}</p>
            <p class="text-sm text-neutral-700 font-mono font-semibold">{{ product.currentPrice }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useWishlistStore } from '@/stores/client/wishlistStore'

const router = useRouter()
const wishlistStore = useWishlistStore()
const wishlistItems = computed(() => wishlistStore.items)

const goToDetail = (product) => {
  const target = product.slug || product.id
  if (target) {
    router.push({ name: 'ProductDetail', params: { slug: target } })
  }
}

const removeItem = (id) => {
  wishlistStore.removeItem(id)
}

const clearAll = () => {
  wishlistStore.clearAll()
}

onMounted(() => {
  wishlistStore.fetchWishlist()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>