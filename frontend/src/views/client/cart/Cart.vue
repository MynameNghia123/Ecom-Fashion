<template>
  <div class="max-w-[1200px] mx-auto px-5 py-10 lg:py-16 font-text">

    <!-- Breadcrumb -->
    <nav class="text-[11px] text-neutral-400 uppercase tracking-wider mb-8 flex items-center gap-2">
      <router-link to="/" class="hover:text-black transition-colors text-neutral-400">Trang chủ</router-link>
      <span>/</span>
      <span class="text-black font-semibold">Giỏ hàng</span>
    </nav>

    <!-- ── Empty Cart ── -->
    <div v-if="cartStore.isEmpty" class="text-center py-24 space-y-5">
      <div class="w-20 h-20 bg-neutral-50 border-2 border-dashed border-neutral-200 rounded-full flex items-center justify-center mx-auto">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#d4d4d4" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
      </div>
      <h2 class="text-[16px] font-bold uppercase tracking-[2px] text-neutral-700">Giỏ hàng của bạn đang trống</h2>
      <p class="text-[13px] text-neutral-400 max-w-[340px] mx-auto">Hãy thêm các sản phẩm tuyệt vời vào giỏ hàng của bạn nhé.</p>
      <div class="pt-2">
        <router-link to="/" class="inline-block bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-[2px] text-[11px] px-10 py-4 transition-colors duration-300">
          Tiếp tục mua sắm
        </router-link>
      </div>
    </div>

    <!-- ── Active Cart ── -->
    <div v-else class="flex flex-col xl:flex-row gap-10 xl:gap-14 items-start">

      <!-- LEFT: Product Table -->
      <div class="w-full xl:w-[65%]">

        <!-- Table header -->
        <div class="hidden md:grid grid-cols-[24px_1.8fr_1fr_1.2fr_1fr_40px] gap-4 pb-3 border-b border-neutral-200 text-[10px] uppercase tracking-[1.5px] text-neutral-400 font-bold">
          <!-- Checkbox all -->
          <div class="flex items-center">
            <label class="custom-checkbox cursor-pointer" title="Chọn tất cả">
              <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" />
              <span class="checkmark"></span>
            </label>
          </div>
          <span>Sản phẩm</span>
          <span class="text-center">Đơn giá</span>
          <span class="text-center">Số lượng</span>
          <span class="text-center">Thành tiền</span>
          <span></span>
        </div>

        <!-- Cart Items -->
        <div
          v-for="item in cartStore.items"
          :key="item.product_variant_id"
          class="grid grid-cols-1 md:grid-cols-[24px_1.8fr_1fr_1.2fr_1fr_40px] gap-4 items-center border-b border-neutral-100 py-5 transition-colors"
          :class="selectedIds.has(item.product_variant_id) ? 'bg-white' : 'bg-white opacity-60'"
        >
          <!-- Checkbox item -->
          <div class="flex items-center">
            <label class="custom-checkbox cursor-pointer">
              <input
                type="checkbox"
                :checked="selectedIds.has(item.product_variant_id)"
                @change="toggleSelect(item.product_variant_id)"
              />
              <span class="checkmark"></span>
            </label>
          </div>

          <!-- Product info -->
          <div class="flex items-center gap-4">

            <div
              class="w-[72px] h-[90px] bg-neutral-50 overflow-hidden shrink-0 border border-neutral-100 rounded-sm cursor-pointer"
              @click="toggleSelect(item.product_variant_id)"
            >
              <img
                :src="getImageUrl(item.product_thumbnail)"
                :alt="item.product_name"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="space-y-1 min-w-0">
              <h4 class="text-[13px] font-bold text-black uppercase tracking-wide leading-snug truncate">
                {{ item.product_name }}
              </h4>
              <p class="text-[11px] text-neutral-400 leading-relaxed">
                <span class="font-mono">{{ item.sku }}</span>
                <template v-if="item.attributes && item.attributes.length > 0">
                  <span v-for="a in item.attributes" :key="a.attribute" class="ml-1">
                    · <span class="text-neutral-500 font-medium">{{ a.attribute }}:</span> {{ a.value }}
                  </span>
                </template>
              </p>
            </div>
          </div>

          <!-- Unit price -->
          <div class="flex md:justify-center items-center gap-2">
            <span class="md:hidden text-[10px] uppercase tracking-wider text-neutral-400">Đơn giá:</span>
            <span class="text-[13px] font-medium text-neutral-700">{{ formatPrice(item.price) }}đ</span>
          </div>

          <!-- Quantity controls -->
          <div class="flex items-center md:justify-center gap-0">
            <button
              @click="cartStore.updateQuantity(item.product_variant_id, item.quantity - 1)"
              :disabled="item.quantity <= 1"
              class="qty-btn rounded-l-sm"
              aria-label="Giảm số lượng"
            >−</button>
            <input
              type="number"
              :value="item.quantity"
              @change="e => cartStore.updateQuantity(item.product_variant_id, parseInt(e.target.value) || 1)"
              class="w-10 h-8 text-center border-y border-neutral-200 text-[13px] font-semibold focus:outline-none bg-white"
              min="1"
              :max="item.stock_quantity"
            />
            <button
              @click="cartStore.updateQuantity(item.product_variant_id, item.quantity + 1)"
              :disabled="item.quantity >= item.stock_quantity"
              class="qty-btn rounded-r-sm"
              aria-label="Tăng số lượng"
            >+</button>
          </div>

          <!-- Subtotal -->
          <div class="flex md:justify-center items-center gap-2">
            <span class="md:hidden text-[10px] uppercase tracking-wider text-neutral-400">Thành tiền:</span>
            <span class="text-[13px] font-bold text-black">{{ formatPrice(item.price * item.quantity) }}đ</span>
          </div>

          <!-- Remove -->
          <div class="flex md:justify-center justify-end">
            <button
              @click="cartStore.removeItem(item.product_variant_id)"
              class="w-7 h-7 flex items-center justify-center rounded-full text-neutral-300 hover:text-white hover:bg-black transition-all duration-200 border border-transparent hover:border-black"
              aria-label="Xóa sản phẩm"
            >
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>

        <!-- Bottom bar -->
        <div class="flex items-center justify-between pt-5 gap-4 flex-wrap">
          <div class="flex items-center gap-3">
            <label class="custom-checkbox cursor-pointer flex items-center gap-2 text-[11px] text-neutral-500 uppercase tracking-wider font-semibold select-none">
              <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" />
              <span class="checkmark"></span>
              Chọn tất cả ({{ cartStore.items.length }})
            </label>
            <span class="text-neutral-200">|</span>
            <button
              v-if="selectedIds.size > 0"
              @click="removeSelected"
              class="text-[11px] text-neutral-400 hover:text-red-500 uppercase tracking-wider font-semibold transition-colors"
            >
              Xóa đã chọn ({{ selectedIds.size }})
            </button>
          </div>

          <button
            @click="cartStore.clearCart()"
            class="border border-neutral-200 hover:border-black bg-transparent text-neutral-500 hover:text-black text-[11px] font-bold tracking-[1.5px] py-2.5 px-5 uppercase transition-all rounded-sm"
          >
            Xóa tất cả
          </button>
        </div>
      </div>

      <!-- RIGHT: Totals -->
      <div class="w-full xl:w-[35%] bg-[#fafafa] border border-neutral-100 p-7 rounded-sm sticky top-[100px] space-y-5">
        <h2 class="font-title text-[18px] tracking-[2px] text-black uppercase font-semibold border-b border-neutral-200 pb-4">
          Tổng đơn hàng
        </h2>

        <!-- Selected count -->
        <div class="text-[12px] text-neutral-400 flex items-center gap-1.5">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Đã chọn <span class="font-bold text-black mx-1">{{ selectedIds.size }}</span> / {{ cartStore.items.length }} sản phẩm
        </div>

        <!-- Subtotal -->
        <div class="flex justify-between items-center text-[13px] text-neutral-600">
          <span>Tạm tính</span>
          <span class="font-semibold text-black">{{ formatPrice(selectedTotal) }}đ</span>
        </div>

        <!-- Shipping -->
        <div class="flex justify-between items-center text-[13px] text-neutral-500">
          <span>Vận chuyển</span>
          <span class="italic text-[12px]">Tính khi thanh toán</span>
        </div>

        <!-- Total -->
        <div class="border-t border-neutral-200 pt-4 flex justify-between items-end">
          <span class="font-title text-[16px] uppercase tracking-[1px] text-black">Tổng cộng</span>
          <span class="font-title text-[22px] font-bold text-black">{{ formatPrice(selectedTotal) }}đ</span>
        </div>

        <!-- Checkout button -->
        <button
          @click="handleCheckout"
          :disabled="selectedIds.size === 0"
          class="block w-full bg-black hover:bg-neutral-800 disabled:bg-neutral-300 disabled:cursor-not-allowed text-white text-[12px] font-bold tracking-[2px] py-4 uppercase transition-colors duration-300 text-center"
        >
          {{ selectedIds.size === 0 ? 'Chọn sản phẩm để thanh toán' : 'Tiến hành thanh toán' }}
        </button>

        <!-- Trust icons -->
        <div class="flex justify-center gap-5 pt-1">
          <svg class="w-6 h-6 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <svg class="w-6 h-6 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          <svg class="w-6 h-6 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useCartStore } from '@/stores/client/cartStore'

const cartStore = useCartStore()
const router = useRouter()

// ── Selection ── Dùng storeToRefs để giữ reactivity ──────────────────────────
const { selectedIds, isAllSelected, selectedTotal } = storeToRefs(cartStore)

const toggleSelect    = (id) => cartStore.toggleSelect(id)
const toggleSelectAll = ()   => cartStore.toggleSelectAll()

const removeSelected = () => {
  cartStore.selectedIds.forEach(id => cartStore.removeItem(id))
}

// ── Checkout ──────────────────────────────────────────────────────────────────
const handleCheckout = () => {
  if (selectedIds.value.size === 0) return
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

/* ── Quantity button ── */
.qty-btn {
  width: 2rem;
  height: 2rem;
  border: 1px solid #e5e5e5;
  background: #fff;
  color: #737373;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s ease;
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
}
.qty-btn:hover:not(:disabled) {
  border-color: #000;
  color: #000;
}
.qty-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

/* ── Custom checkbox ── */
.custom-checkbox {
  display: inline-flex;
  align-items: center;
  position: relative;
}

.custom-checkbox input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.checkmark {
  display: inline-block;
  width: 17px;
  height: 17px;
  border: 1.5px solid #d4d4d4;
  background: #fff;
  border-radius: 3px;
  transition: all 0.15s ease;
  position: relative;
  flex-shrink: 0;
}

.custom-checkbox input:checked + .checkmark {
  background: #000;
  border-color: #000;
}

.custom-checkbox input:checked + .checkmark::after {
  content: '';
  position: absolute;
  left: 4px;
  top: 1px;
  width: 5px;
  height: 9px;
  border: 2px solid #fff;
  border-top: none;
  border-left: none;
  transform: rotate(45deg);
}

.custom-checkbox:hover .checkmark {
  border-color: #000;
}
</style>
