<template>
  <Transition name="fade">
    <div v-if="authStore.showCouponPopup" class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div 
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        @click="closePopup"
      ></div>

      <!-- Popup Content -->
      <div class="relative bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden animate-slide-up">
        <!-- Close button -->
        <button 
          @click="closePopup"
          class="absolute top-4 right-4 text-neutral-400 hover:text-black bg-transparent border-none cursor-pointer p-1"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>

        <!-- Header -->
        <div class="bg-black text-white p-6 text-center">
          <div class="inline-flex items-center justify-center w-12 h-12 bg-white text-black rounded-full mb-3">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
          </div>
          <h3 class="font-title text-xl font-bold uppercase tracking-wider m-0">Chào mừng trở lại!</h3>
          <p class="font-text text-sm text-neutral-300 mt-2 m-0">Chúng tôi có ưu đãi đặc biệt dành riêng cho bạn</p>
        </div>

        <!-- Body: Coupon List -->
        <div class="p-6 max-h-[60vh] overflow-y-auto bg-neutral-50 scrollbar-thin">
          <div class="space-y-4">
            <!-- Local alert message -->
            <div v-if="alert.show" :class="['p-3 rounded text-sm font-medium', alert.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
              {{ alert.message }}
            </div>

            <div 
              v-for="coupon in authStore.collectableCoupons" 
              :key="coupon.id"
              class="bg-white border border-neutral-200 rounded-lg p-4 shadow-sm flex items-center justify-between gap-4"
            >
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <span class="bg-black text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded-sm">
                    {{ coupon.code }}
                  </span>
                </div>
                <h4 class="font-text font-bold text-sm text-black m-0 mt-1">
                  Giảm {{ formatDiscount(coupon) }}
                </h4>
                <p v-if="coupon.price_min_order_value" class="text-xs text-neutral-500 m-0 mt-1">
                  Đơn tối thiểu: {{ formatPrice(coupon.price_min_order_value) }} đ
                </p>
              </div>
              
              <button 
                @click="collect(coupon.id)"
                :disabled="collectingId === coupon.id"
                class="shrink-0 px-4 py-2 bg-black text-white text-xs font-bold uppercase tracking-wider hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed border-none cursor-pointer rounded transition-colors"
              >
                {{ collectingId === coupon.id ? 'Đang lưu...' : 'Lưu mã' }}
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'
import { profileService } from '@/services/client/profileService'

const authStore = useClientAuthStore()
const collectingId = ref(null)
const alert = ref({ show: false, type: 'success', message: '' })

const showAlert = (type, message) => {
  alert.value = { show: true, type, message }
  setTimeout(() => { alert.value.show = false }, 3000)
}

const closePopup = () => {
  authStore.showCouponPopup = false
}

const formatPrice = (val) => {
  if (!val) return '0'
  return new Intl.NumberFormat('vi-VN').format(val)
}

const formatDiscount = (coupon) => {
  if (coupon.type === 'percent') {
    return coupon.discount_value + '%'
  }
  return formatPrice(coupon.discount_value) + ' đ'
}

const collect = async (id) => {
  if (collectingId.value) return
  collectingId.value = id
  try {
    const res = await profileService.collectCoupon(id)
    if (res.data && res.data.success) {
      showAlert('success', 'Lưu mã giảm giá thành công!')
      // Remove from the list
      authStore.collectableCoupons = authStore.collectableCoupons.filter(c => c.id !== id)
      
      // Auto close if no more coupons
      if (authStore.collectableCoupons.length === 0) {
        setTimeout(closePopup, 1000)
      }
    }
  } catch (err) {
    const msg = err.response?.data?.message || 'Lỗi khi lưu mã giảm giá'
    showAlert('error', msg)
  } finally {
    collectingId.value = null
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.animate-slide-up {
  animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
