<template>
  <div class="max-w-[1200px] mx-auto px-5 py-10 lg:py-16">

    <!-- Breadcrumb -->
    <nav class="font-text text-[11px] text-neutral-400 uppercase tracking-wider mb-8 flex items-center gap-2">
      <router-link to="/" class="hover:text-black transition-colors no-underline text-neutral-400">Trang chủ</router-link>
      <span>/</span>
      <span class="text-black font-semibold">Giỏ hàng</span>
    </nav>

    <!-- Empty Cart state -->
    <div v-if="cartStore.isEmpty" class="text-center py-20 space-y-6 font-text">
      <div class="w-16 h-16 bg-neutral-50 text-neutral-400 rounded-full flex items-center justify-center mx-auto border border-neutral-100">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
      </div>
      <h2 class="text-lg font-bold uppercase tracking-wider text-neutral-700">Giỏ hàng của bạn đang trống</h2>
      <p class="text-sm text-neutral-500 max-w-[360px] mx-auto">Hãy thêm các sản phẩm tuyệt vời của chúng tôi vào giỏ hàng của bạn nhé.</p>
      <div class="pt-4">
        <router-link to="/" class="inline-block bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-xs px-8 py-4.5 transition-colors duration-300">
          Tiếp tục mua sắm
        </router-link>
      </div>
    </div>

    <!-- Active Cart state -->
    <div v-else class="flex flex-col xl:flex-row gap-10 xl:gap-16 items-start">

      <!-- LEFT: Product Table -->
      <div class="w-full xl:w-[65%] space-y-6">

        <!-- Table header -->
        <div class="hidden md:grid grid-cols-[1.5fr_1fr_1fr_1fr_40px] gap-4 pb-3 border-b border-neutral-200 font-text text-[10px] uppercase tracking-wider text-neutral-400 font-semibold">
          <span>Sản phẩm</span>
          <span class="text-center">Đơn giá</span>
          <span class="text-center">Số lượng</span>
          <span class="text-center">Thành tiền</span>
          <span></span>
        </div>

        <!-- Loop Cart Items -->
        <div 
          v-for="item in cartStore.items" 
          :key="item.product_variant_id" 
          class="grid grid-cols-1 md:grid-cols-[1.5fr_1fr_1fr_1fr_40px] gap-4 items-center border-b border-neutral-100 pb-6"
        >
          <div class="flex items-center gap-4">
            <div class="w-[80px] h-[100px] bg-neutral-50 overflow-hidden shrink-0 border border-neutral-100 rounded">
              <img
                :src="getImageUrl(item.product_thumbnail)"
                :alt="item.product_name"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="space-y-1.5">
              <h4 class="text-[13px] font-bold text-black uppercase tracking-wide leading-tight font-text">
                {{ item.product_name }}
              </h4>
              <p class="text-xs text-neutral-400 font-text">
                SKU: {{ item.sku }} 
                <span v-if="item.attributes && item.attributes.length > 0">
                  | {{ item.attributes.map(a => `${a.attribute}: ${a.value}`).join(' | ') }}
                </span>
              </p>
            </div>
          </div>
          
          <div class="text-center text-sm font-text text-neutral-700 font-medium">
            {{ formatPrice(item.price) }}đ
          </div>
          
          <div class="flex items-center justify-center gap-2">
            <button 
              @click="cartStore.updateQuantity(item.product_variant_id, item.quantity - 1)"
              class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none"
            >
              −
            </button>
            <span class="w-8 text-center text-sm font-text font-semibold">{{ item.quantity }}</span>
            <button 
              @click="cartStore.updateQuantity(item.product_variant_id, item.quantity + 1)"
              class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none"
            >
              +
            </button>
          </div>
          
          <div class="text-center text-sm font-text font-semibold text-black">
            {{ formatPrice(item.price * item.quantity) }}đ
          </div>
          
          <div class="flex justify-end md:justify-center">
            <button 
              @click="cartStore.removeItem(item.product_variant_id)"
              class="text-neutral-300 hover:text-black transition-colors cursor-pointer bg-transparent border-none" 
              aria-label="Xóa sản phẩm"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>

        <!-- Coupon + Clear All -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-4">
          <div></div>
          <button 
            @click="cartStore.clearCart()"
            class="border border-neutral-200 hover:border-black bg-transparent text-neutral-600 hover:text-black font-text text-[11px] font-bold tracking-wider py-3 px-6 uppercase transition-all cursor-pointer rounded"
          >
            Xóa sạch giỏ hàng
          </button>
        </div>

      </div>

      <!-- RIGHT: Cart Totals -->
      <div class="w-full xl:w-[35%] bg-[#fafafa] border border-neutral-100 p-8 rounded sticky top-[100px] space-y-6">
        <h2 class="font-title text-[22px] tracking-[1.5px] text-black uppercase font-medium border-b border-neutral-200 pb-5">Tổng giỏ hàng</h2>

        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm font-text text-neutral-600">
          <span>Tạm tính</span>
          <span class="font-semibold text-black">{{ formatPrice(cartStore.totalPrice) }}đ</span>
        </div>

        <!-- Shipping -->
        <div class="flex justify-between items-center text-sm font-text text-neutral-600">
          <span>Vận chuyển</span>
          <span class="text-neutral-500">Tính khi thanh toán</span>
        </div>

        <!-- Total -->
        <div class="border-t border-neutral-200 pt-5 flex justify-between items-end">
          <span class="font-title text-[20px] uppercase tracking-[1px] text-black">Tổng cộng</span>
          <span class="font-title text-[24px] font-bold text-black">{{ formatPrice(cartStore.totalPrice) }}đ</span>
        </div>

        <!-- Checkout button -->
        <router-link
          to="/checkout"
          class="block w-full bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4 uppercase transition-colors duration-300 text-center no-underline"
        >
          Tiến hành thanh toán
        </router-link>

        <!-- Trust icons -->
        <div class="flex justify-center gap-5 pt-2">
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="2" width="14" height="20" rx="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/client/cartStore'

const cartStore = useCartStore()

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
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
