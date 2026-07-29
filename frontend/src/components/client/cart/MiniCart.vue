<template>
  <transition name="drawer-fade">
    <div v-if="isOpen" class="fixed inset-0 z-[999] flex justify-end">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-xs" @click="close"></div>

      <!-- Drawer Panel -->
      <div class="relative bg-white w-full max-w-[420px] h-full flex flex-col shadow-2xl z-10 animate-drawer-slide font-text">

        <!-- Header -->
        <div class="p-6 border-b border-neutral-100 flex items-center justify-between shrink-0">
          <h2 class="font-title text-[24px] font-normal text-black tracking-[0.5px]">Giỏ hàng ({{ cartStore.totalQuantity }})</h2>
          <button
            @click="close"
            class="text-neutral-400 hover:text-black transition-colors cursor-pointer bg-transparent border-none"
            aria-label="Đóng giỏ hàng"
          >
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- Items (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-thin">
          <!-- Empty State -->
          <div v-if="cartStore.isEmpty" class="text-center py-20 space-y-4">
            <svg class="w-12 h-12 text-neutral-300 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <p class="text-sm text-neutral-500">Giỏ hàng của bạn đang trống.</p>
          </div>

          <!-- Active Items -->
          <div 
            v-else
            v-for="item in cartStore.items" 
            :key="item.product_variant_id" 
            class="flex gap-4 items-center"
          >
            <div class="w-[70px] h-[90px] bg-neutral-50 overflow-hidden rounded border border-neutral-100 shrink-0">
              <img
                :src="getImageUrl(item.product_thumbnail)"
                :alt="item.product_name"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 space-y-1">
              <h4 class="text-[13px] font-bold text-black uppercase tracking-wide leading-tight">
                {{ item.product_name }}
              </h4>
              <p class="text-[11px] text-neutral-400">
                <span v-if="item.attributes && item.attributes.length > 0">
                  {{ item.attributes.map(a => a.value).join(' / ') }}
                </span>
                <span v-else>Mặc định</span>
              </p>
              <p class="text-[12px] font-medium text-neutral-600">
                {{ item.quantity }} &times; {{ formatPrice(item.price) }}đ
              </p>
              <button 
                @click="cartStore.removeItem(item.product_variant_id)"
                class="text-[10px] text-neutral-400 hover:text-black flex items-center gap-1 cursor-pointer bg-transparent border-none pt-1 transition-colors"
              >
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                XÓA
              </button>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-neutral-100 bg-[#fafafa] shrink-0 space-y-5">
          <!-- Subtotal -->
          <div class="flex justify-between items-end font-text">
            <span class="text-neutral-500 uppercase tracking-wider text-xs font-semibold">Tổng tạm tính:</span>
            <span class="text-lg font-bold text-black font-title">{{ formatPrice(cartStore.totalPrice) }}đ</span>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-4">
            <router-link
              to="/cart"
              @click="close"
              class="border border-neutral-300 hover:border-black bg-white hover:bg-neutral-50 text-black font-text text-[11px] font-bold tracking-wider py-4 uppercase transition-all text-center no-underline"
            >
              Xem giỏ hàng
            </router-link>
            <router-link
              to="/checkout"
              @click="close"
              class="bg-black hover:bg-neutral-800 text-white font-text text-[11px] font-bold tracking-wider py-4 uppercase transition-colors text-center no-underline"
            >
              Thanh toán
            </router-link>
          </div>

          <!-- Free Shipping note -->
          <div class="flex items-center justify-center gap-2 pt-1 text-[10px] text-neutral-400">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <rect x="1" y="3" width="15" height="13"></rect>
              <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
              <circle cx="5.5" cy="18.5" r="2.5"></circle>
              <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
            <span>MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC CHO ĐƠN HÀNG LỚN</span>
          </div>
        </div>

      </div>
    </div>
  </transition>
</template>

<script setup>
import { useCartStore } from '@/stores/client/cartStore'

const cartStore = useCartStore()

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const close = () => {
  emit('close')
}

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}
</script>

<style scoped>
.drawer-fade-enter-active,
.drawer-fade-leave-active {
  transition: opacity 0.3s ease;
}
.drawer-fade-enter-from,
.drawer-fade-leave-to {
  opacity: 0;
}

@keyframes drawerSlide {
  from { transform: translateX(100%); }
  to   { transform: translateX(0); }
}

.animate-drawer-slide {
  animation: drawerSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e5e5; border-radius: 4px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #ccc; }
</style>
