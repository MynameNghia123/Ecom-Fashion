<template>
  <div class="space-y-10 animate-fade-in text-[#111111]">
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

    <!-- Empty State -->
    <div v-if="wishlistItems.length === 0" class="text-center py-20 border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
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
          class="group relative"
        >
          <!-- Image -->
          <div class="relative overflow-hidden bg-neutral-100 aspect-[3/4]">
            <img
              :src="product.image"
              :alt="product.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            />
            <!-- Remove btn -->
            <button
              @click="removeItem(product.id)"
              class="absolute top-3 right-3 w-8 h-8 bg-white border border-neutral-200 flex items-center justify-center hover:bg-rose-50 hover:border-rose-300 transition-colors cursor-pointer"
              title="Xóa khỏi yêu thích"
            >
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-rose-500">
                <path d="M18 6L6 18M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Info -->
          <div class="mt-3 space-y-1">
            <p class="text-[11px] uppercase tracking-widest text-neutral-400 font-bold">{{ product.description }}</p>
            <p class="text-sm font-bold text-neutral-900 font-title">{{ product.name }}</p>
            <p class="text-sm text-neutral-700 font-mono">{{ product.currentPrice }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useWishlistStore } from '@/stores/client/wishlistStore'

const wishlistStore = useWishlistStore()
const wishlistItems = computed(() => wishlistStore.items)

const removeItem = (id) => {
  wishlistStore.removeItem(id)
}

const clearAll = () => {
  wishlistStore.clearAll()
}
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