<template>
  <transition name="drawer-fade">
    <div v-if="isOpen" class="fixed inset-0 z-[999] flex justify-end">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-xs" @click="close"></div>

      <!-- Drawer Panel -->
      <div class="relative bg-white w-full max-w-[420px] h-full flex flex-col shadow-2xl z-10 animate-drawer-slide font-text">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-neutral-100 flex items-center justify-between shrink-0">
          <div>
            <h2 class="font-title text-[20px] font-normal text-black tracking-[0.5px]">
              Giỏ hàng
              
              <span class="text-[13px] text-neutral-400 font-text font-normal ml-1">({{ cartStore.totalQuantity }})</span>
            </h2>
          </div>
          <button
            @click="close"
            class="w-8 h-8 flex items-center justify-center text-neutral-400 hover:text-black hover:bg-neutral-100 rounded-full transition-all cursor-pointer bg-transparent border-none"
            aria-label="Đóng giỏ hàng"
          >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- Select All bar (chỉ hiện khi có items) -->
        <div v-if="!cartStore.isEmpty" class="px-5 py-2.5 border-b border-neutral-100 flex items-center justify-between bg-neutral-50">
          <label class="mini-checkbox cursor-pointer flex items-center gap-2 select-none">
            <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" />
            <span class="mini-checkmark"></span>
            <span class="text-[11px] text-neutral-500 uppercase tracking-wider font-semibold">
              Chọn tất cả ({{ cartStore.items.length }})
            </span>
          </label>
          <span class="text-[11px] text-neutral-400">Đã chọn: <b class="text-black">{{ selectedIds.size }}</b></span>
        </div>

        <!-- Items (Scrollable) -->
        <div class="flex-1 overflow-y-auto scrollbar-thin">
          <!-- Empty State -->
          <div v-if="cartStore.isEmpty" class="text-center py-20 space-y-4">
            <div class="w-16 h-16 bg-neutral-50 border-2 border-dashed border-neutral-200 rounded-full flex items-center justify-center mx-auto">
              <svg class="w-7 h-7 text-neutral-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
              </svg>
            </div>
            <p class="text-[13px] text-neutral-400">Giỏ hàng của bạn đang trống.</p>
            <button @click="close" class="text-[11px] font-bold uppercase tracking-wider underline text-black hover:text-neutral-600 transition-colors bg-transparent border-none cursor-pointer">
              Tiếp tục mua sắm
            </button>
          </div>

          <!-- Active Items -->
          <div
            v-for="item in cartStore.items"
            :key="item.product_variant_id"
            class="flex gap-3 items-start px-5 py-4 border-b border-neutral-100 transition-opacity"
            :class="selectedIds.has(item.product_variant_id) ? 'opacity-100' : 'opacity-45'"
          >
            <!-- Checkbox -->
            <div class="pt-1 shrink-0">
              <label class="mini-checkbox cursor-pointer">
                <input
                  type="checkbox"
                  :checked="selectedIds.has(item.product_variant_id)"
                  @change="toggleSelect(item.product_variant_id)"
                />
                <span class="mini-checkmark"></span>
              </label>
            </div>

            <!-- Thumbnail -->
            <div class="w-[62px] h-[80px] bg-neutral-50 overflow-hidden rounded-sm border border-neutral-100 shrink-0">
              <img
                :src="getImageUrl(item.product_thumbnail)"
                :alt="item.product_name"
                class="w-full h-full object-cover"
              />
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0 space-y-1.5">
              <h4 class="text-[12px] font-bold text-black uppercase tracking-wide leading-tight line-clamp-2">
                {{ item.product_name }}
              </h4>
              <p class="text-[10px] text-neutral-400">
                <span v-if="item.attributes && item.attributes.length > 0">
                  {{ item.attributes.map(a => a.value).join(' / ') }}
                </span>
                <span v-else>Mặc định</span>
              </p>

              <!-- Price + Qty controls -->
              <div class="flex items-center justify-between pt-0.5">
                <span class="text-[12px] font-bold text-black">{{ formatPrice(item.price * item.quantity) }}đ</span>

                <!-- Quantity -->
                <div class="flex items-center border border-neutral-200 rounded-sm overflow-hidden">
                  <button
                    @click="cartStore.updateQuantity(item.product_variant_id, item.quantity - 1)"
                    :disabled="item.quantity <= 1"
                    class="mini-qty-btn"
                  >−</button>
                  <span class="w-7 text-center text-[12px] font-semibold border-x border-neutral-200 py-1 leading-none">{{ item.quantity }}</span>
                  <button
                    @click="cartStore.updateQuantity(item.product_variant_id, item.quantity + 1)"
                    :disabled="item.quantity >= item.stock_quantity"
                    class="mini-qty-btn"
                  >+</button>
                </div>
              </div>

              <!-- Unit price + remove -->
              <div class="flex items-center justify-between">
                <span class="text-[10px] text-neutral-400">{{ formatPrice(item.price) }}đ / cái</span>
                <button
                  @click="cartStore.removeItem(item.product_variant_id)"
                  class="text-[10px] text-neutral-300 hover:text-red-500 flex items-center gap-0.5 cursor-pointer bg-transparent border-none transition-colors"
                >
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div v-if="!cartStore.isEmpty" class="px-5 py-5 border-t border-neutral-100 bg-[#fafafa] shrink-0 space-y-4">
          <!-- Selected total -->
          <div class="flex justify-between items-end">
            <div>
              <p class="text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-0.5">Tạm tính</p>
              <p class="text-[10px] text-neutral-400">{{ selectedIds.size }} sản phẩm được chọn</p>
            </div>
            <span class="font-title text-[20px] font-bold text-black">{{ formatPrice(selectedTotal) }}đ</span>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-3">
            <router-link
              to="/cart"
              @click="close"
              class="border border-neutral-300 hover:border-black bg-white hover:bg-neutral-50 text-black text-[11px] font-bold tracking-[1.5px] py-3.5 uppercase transition-all text-center no-underline block"
            >
              Xem giỏ hàng
            </router-link>
            <button
              @click="handleCheckout"
              :disabled="selectedIds.size === 0"
              class="bg-black hover:bg-neutral-800 disabled:bg-neutral-300 disabled:cursor-not-allowed text-white text-[11px] font-bold tracking-[1.5px] py-3.5 uppercase transition-colors text-center border-none cursor-pointer w-full"
            >
              Thanh toán
            </button>
          </div>

          <!-- Shipping note -->
          <div class="flex items-center justify-center gap-1.5 text-[10px] text-neutral-400 pt-0.5">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <rect x="1" y="3" width="15" height="13"></rect>
              <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
              <circle cx="5.5" cy="18.5" r="2.5"></circle>
              <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
            <span>Miễn phí vận chuyển toàn quốc cho đơn lớn</span>
          </div>
        </div>

      </div>
    </div>
  </transition>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useCartStore } from '@/stores/client/cartStore'

const cartStore = useCartStore()
const router = useRouter()

const props = defineProps({
  isOpen: { type: Boolean, default: false }
})
const emit = defineEmits(['close'])
const close = () => emit('close')

// ── Selection ── Dùng storeToRefs để giữ reactivity ──
const { selectedIds, isAllSelected, selectedTotal } = storeToRefs(cartStore)

const toggleSelect    = (id) => cartStore.toggleSelect(id)
const toggleSelectAll = ()   => cartStore.toggleSelectAll()

// ── Checkout ────────────────────────────────────────────────────────────────
const handleCheckout = () => {
  if (cartStore.selectedIds.size === 0) return
  close()
  router.push('/checkout')
}


// ── Helpers ─────────────────────────────────────────────────────────────────
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
.font-title { font-family: var(--font-title); }
.font-text  { font-family: var(--font-text); }

/* Drawer animation */
.drawer-fade-enter-active,
.drawer-fade-leave-active { transition: opacity 0.3s ease; }
.drawer-fade-enter-from,
.drawer-fade-leave-to { opacity: 0; }

@keyframes drawerSlide {
  from { transform: translateX(100%); }
  to   { transform: translateX(0); }
}
.animate-drawer-slide {
  animation: drawerSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Scrollbar */
.scrollbar-thin::-webkit-scrollbar { width: 3px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e5e5; border-radius: 4px; }

/* Qty button */
.mini-qty-btn {
  width: 26px;
  height: 26px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: #737373;
  background: #fff;
  border: none;
  cursor: pointer;
  transition: all 0.15s;
  line-height: 1;
}
.mini-qty-btn:hover:not(:disabled) { background: #f5f5f5; color: #000; }
.mini-qty-btn:disabled { opacity: 0.3; cursor: not-allowed; }

/* Mini checkbox */
.mini-checkbox {
  display: inline-flex;
  align-items: center;
  position: relative;
}
.mini-checkbox input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}
.mini-checkmark {
  display: inline-block;
  width: 15px;
  height: 15px;
  border: 1.5px solid #d4d4d4;
  background: #fff;
  border-radius: 3px;
  transition: all 0.15s ease;
  position: relative;
  flex-shrink: 0;
}
.mini-checkbox input:checked + .mini-checkmark {
  background: #000;
  border-color: #000;
}
.mini-checkbox input:checked + .mini-checkmark::after {
  content: '';
  position: absolute;
  left: 3px;
  top: 1px;
  width: 5px;
  height: 8px;
  border: 2px solid #fff;
  border-top: none;
  border-left: none;
  transform: rotate(45deg);
}
.mini-checkbox:hover .mini-checkmark { border-color: #000; }
</style>
