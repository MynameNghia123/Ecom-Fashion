<template>
  <div class="max-w-[600px] mx-auto px-5 py-20 text-center font-text">
    <div v-if="loading" class="space-y-6">
      <!-- Loading spinner -->
      <div class="inline-block w-12 h-12 border-4 border-neutral-200 border-t-black rounded-full animate-spin"></div>
      <h2 class="text-xl font-bold uppercase tracking-wider text-neutral-800">Đang xác thực giao dịch...</h2>
      <p class="text-sm text-neutral-500">Vui lòng không tắt hoặc tải lại trang này.</p>
    </div>

    <div v-else-if="success" class="space-y-6 animate-fade-in">
      <!-- Success Icon -->
      <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto border border-emerald-100">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>
      <h2 class="text-2xl font-title font-bold uppercase tracking-wider text-emerald-800">Thanh toán thành công!</h2>
      <p class="text-sm text-neutral-600 max-w-[400px] mx-auto">
        Giao dịch của bạn đã được xác nhận thành công. Đơn hàng đang được chuẩn bị để giao đến bạn.
      </p>
      <div class="pt-4">
        <router-link 
          :to="{ name: 'CheckoutSuccess', query: { code: orderCode } }"
          class="inline-block bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-xs px-8 py-4.5 transition-colors duration-300"
        >
          Xem chi tiết đơn hàng
        </router-link>
      </div>
    </div>

    <div v-else class="space-y-6 animate-fade-in">
      <!-- Error Icon -->
      <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto border border-rose-100">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </div>
      <h2 class="text-2xl font-title font-bold uppercase tracking-wider text-rose-800">Thanh toán thất bại</h2>
      <p class="text-sm text-neutral-600 max-w-[400px] mx-auto">
        {{ errorMessage || 'Đã xảy ra lỗi trong quá trình xử lý giao dịch hoặc bạn đã hủy thanh toán.' }}
      </p>
      <div class="pt-4 flex justify-center gap-4">
        <router-link 
          to="/checkout"
          class="inline-block bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-xs px-8 py-4.5 transition-colors duration-300"
        >
          Thử thanh toán lại
        </router-link>
        <router-link 
          to="/"
          class="inline-block border border-neutral-300 hover:border-black text-black font-bold uppercase tracking-wider text-xs px-8 py-4.5 transition-colors duration-300"
        >
          Quay lại trang chủ
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { orderService } from '@/services/client/orderService'
import { useCartStore } from '@/stores/client/cartStore'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()

const loading = ref(true)
const success = ref(false)
const orderCode = ref('')
const errorMessage = ref('')

onMounted(async () => {
  try {
    const res = await orderService.verifyVNPay(route.query)
    if (res.data && res.data.success) {
      success.value = true
      orderCode.value = res.data.order_code
      // Thanh toán thành công, xóa giỏ hàng
      cartStore.clearCart()
    } else {
      success.value = false
      errorMessage.value = res.data.message
    }
  } catch (err) {
    success.value = false
    errorMessage.value = err.message || 'Chữ ký giao dịch không hợp lệ hoặc đã xảy ra lỗi hệ thống.'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
