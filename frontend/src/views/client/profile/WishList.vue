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
          class="border border-neutral-900 bg-white hover:bg-neutral-900 hover:text-white px-5 py-2.5 text-[11px] font-bold tracking-widest uppercase transition-colors duration-300 font-text cursor-pointer"
        >
          Thêm tất cả vào giỏ hàng
        </button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="wishlistItems.length === 0" class="text-center py-20 border border-dashed border-neutral-200">
      <p class="text-neutral-500 font-text text-sm">Danh sách yêu thích trống.</p>
      <button
        class="mt-4 bg-black text-white hover:bg-neutral-800 px-6 py-3 text-[11px] font-bold tracking-widest uppercase transition-colors font-text cursor-pointer border-none"
      >
        Khám phá sản phẩm
      </button>
    </div>

    <!-- Product Grid -->
    <div v-else class="space-y-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
        <ProductCard
          v-for="product in wishlistItems"
          :key="product.id"
          :image="product.image"
          :name="product.name"
          :currentPrice="product.currentPrice"
          :description="product.description"
          :is-wishlist="true"
          @remove="removeItem(product.id)"
        />
      </div>

      <!-- Load More -->
      <div class="text-center pt-6 border-t border-neutral-100">
        <button class="inline-block text-black text-[11px] font-bold uppercase tracking-widest pb-1 border-b border-black hover:text-neutral-500 hover:border-neutral-500 transition-colors bg-transparent border-x-0 border-t-0 cursor-pointer">
          Xem thêm
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductCard from '@/components/client/ui/ProductCard.vue'

const wishlistItems = ref([
  {
    id: 1,
    name: 'Structured Wool Overcoat',
    currentPrice: '$1,250',
    description: 'Midnight Black • Pure Merino Wool',
    image: 'https://images.unsplash.com/photo-1617137968427-85924c800a22?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 2,
    name: 'Asymmetric Silk Drape',
    currentPrice: '$890',
    description: 'Optic White • 100% Charmeuse',
    image: 'https://images.unsplash.com/photo-1596783074918-c84cb06531ca?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 3,
    name: 'Geometric Leather Tote',
    currentPrice: '$1,100',
    description: 'Matte Obsidian • Calfskin Leather',
    image: 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 4,
    name: 'Sculptural Sneaker 01',
    currentPrice: '$450',
    description: 'Bone White • Tech Knit & Leather',
    image: 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 5,
    name: 'Heavy Texture Knit',
    currentPrice: '$620',
    description: 'Charcoal Marl • Brushed Alpaca',
    image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 6,
    name: 'Wide-Leg Architect Trouser',
    currentPrice: '$550',
    description: 'Slate Grey • Italian Gabardine',
    image: 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?q=80&w=600&auto=format&fit=crop'
  }
])

const removeItem = (id) => {
  wishlistItems.value = wishlistItems.value.filter(item => item.id !== id)
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>