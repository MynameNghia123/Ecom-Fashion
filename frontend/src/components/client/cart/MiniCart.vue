<template>
  <transition name="drawer-fade">
    <div v-if="isOpen" class="fixed inset-0 z-[999] flex justify-end">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-xs" @click="close"></div>

      <!-- Drawer Panel -->
      <div class="relative bg-white w-full max-w-[420px] h-full flex flex-col shadow-2xl z-10 animate-drawer-slide font-text">

        <!-- Header -->
        <div class="p-6 border-b border-neutral-100 flex items-center justify-between shrink-0">
          <h2 class="font-title text-[24px] font-normal text-black tracking-[0.5px]">Giỏ hàng</h2>
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

          <!-- Item 1 -->
          <div class="flex gap-4 items-center">
            <div class="w-5 h-5 rounded-full border border-black flex items-center justify-center shrink-0">
              <div class="w-3 h-3 rounded-full bg-black"></div>
            </div>
            <div class="w-[80px] h-[100px] bg-neutral-50 overflow-hidden rounded border border-neutral-150 shrink-0">
              <img
                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop"
                alt="Áo thun tay ngắn"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 space-y-1">
              <h4 class="text-xs font-bold text-black uppercase tracking-wide leading-tight">Áo thun tay ngắn Textured</h4>
              <p class="text-xs text-neutral-400">Size: M | Xanh dương</p>
              <p class="text-xs font-medium text-neutral-500">3 × 580.000đ</p>
              <button class="text-[11px] text-neutral-400 hover:text-black flex items-center gap-1 cursor-pointer bg-transparent border-none pt-1 transition-colors">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                XÓA
              </button>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="flex gap-4 items-center">
            <div class="w-5 h-5 rounded-full border border-black flex items-center justify-center shrink-0">
              <div class="w-3 h-3 rounded-full bg-black"></div>
            </div>
            <div class="w-[80px] h-[100px] bg-neutral-50 overflow-hidden rounded border border-neutral-150 shrink-0">
              <img
                src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=300&auto=format&fit=crop"
                alt="Áo khoác Hoodie"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 space-y-1">
              <h4 class="text-xs font-bold text-black uppercase tracking-wide leading-tight">Áo khoác Hoodie Run & Brunch</h4>
              <p class="text-xs text-neutral-400">Size: S | Trắng</p>
              <p class="text-xs font-medium text-neutral-500">1 × 1.380.000đ</p>
              <button class="text-[11px] text-neutral-400 hover:text-black flex items-center gap-1 cursor-pointer bg-transparent border-none pt-1 transition-colors">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
            <span class="text-lg font-bold text-black font-title">3.120.000đ</span>
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
            <span>MIỄN PHÍ VẬN CHUYỂN CHO ĐƠN HÀNG TRÊN 5.000.000đ</span>
          </div>
        </div>

      </div>
    </div>
  </transition>
</template>

<script setup>
// Giao diện tĩnh - chức năng sẽ kết nối API sau
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
