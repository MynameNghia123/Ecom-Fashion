<template>
  <div class="space-y-8 animate-fade-in">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title">Voucher của tôi</h1>
      <p class="text-sm text-neutral-400 mt-2 font-text">Các mã giảm giá đang còn hiệu lực bạn có thể sử dụng.</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="py-16 text-center">
      <svg class="animate-spin w-6 h-6 mx-auto text-neutral-400" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
      </svg>
    </div>

    <!-- Empty -->
    <div v-else-if="coupons.length === 0" class="py-16 text-center border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <p class="text-sm text-neutral-400">Hiện không có voucher nào khả dụng.</p>
    </div>

    <!-- Coupon grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="coupon in coupons"
        :key="coupon.id"
        class="border border-neutral-200 bg-white relative overflow-hidden group hover:border-neutral-900 transition-all duration-300"
      >
        <!-- Decorative left bar -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-neutral-900 group-hover:bg-black transition-colors"></div>

        <div class="pl-5 pr-5 py-5 flex flex-col gap-3">
          <!-- Code + badge -->
          <div class="flex items-start justify-between gap-3">
            <div>
              <p class="text-[11px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Mã giảm giá</p>
              <p class="text-xl font-bold tracking-widest text-neutral-900 font-mono">{{ coupon.code }}</p>
            </div>
            <span :class="discountBadgeClass(coupon)" class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 border flex-shrink-0">
              {{ discountText(coupon) }}
            </span>
          </div>

          <!-- Details -->
          <div class="space-y-1 text-[12px] text-neutral-500">
            <p v-if="coupon.price_min_order_value">
              Đơn tối thiểu: <span class="text-neutral-700 font-semibold">{{ formatPrice(coupon.price_min_order_value) }}</span>
            </p>
            <p v-if="coupon.max_usage">
              Sử dụng: <span class="text-neutral-700 font-semibold">{{ coupon.used_count }}/{{ coupon.max_usage }}</span>
            </p>
            <p v-if="coupon.expiry_date">
              Hạn: <span :class="isExpiringSoon(coupon) ? 'text-rose-600 font-semibold' : 'text-neutral-700 font-semibold'">{{ formatDate(coupon.expiry_date) }}</span>
            </p>
            <p v-else class="text-emerald-600 font-semibold">Vĩnh viễn</p>
          </div>

          <!-- Copy button -->
          <div class="pt-1 border-t border-neutral-100">
            <button
              @click="copyCode(coupon.code)"
              :class="['flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest transition-colors bg-transparent border-none cursor-pointer', copiedCode === coupon.code ? 'text-emerald-600' : 'text-neutral-500 hover:text-neutral-900']">
              <svg v-if="copiedCode !== coupon.code" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
              </svg>
              <svg v-else width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              {{ copiedCode === coupon.code ? 'Đã sao chép!' : 'Sao chép mã' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Hint -->
    <p class="text-[11px] text-neutral-400 text-center pt-2">
      Dán mã voucher vào trang thanh toán để áp dụng giảm giá.
    </p>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services/client/profileService'

const coupons    = ref([])
const loading    = ref(false)
const copiedCode = ref(null)

onMounted(async () => {
  loading.value = true
  try {
    const res = await profileService.getCoupons()
    if (res.data?.success) coupons.value = res.data.data
  } finally {
    loading.value = false
  }
})

const copyCode = (code) => {
  navigator.clipboard.writeText(code).then(() => {
    copiedCode.value = code
    setTimeout(() => { copiedCode.value = null }, 2500)
  })
}

const discountText = (c) => {
  return c.type === 'percentage' ? `-${c.discount_value}%` : `-${formatPrice(c.discount_value)}`
}

const discountBadgeClass = (c) => {
  return c.type === 'percentage'
    ? 'border-neutral-900 text-neutral-900 bg-white'
    : 'border-emerald-600 text-emerald-700 bg-emerald-50'
}

const isExpiringSoon = (c) => {
  if (!c.expiry_date) return false
  const diff = new Date(c.expiry_date) - new Date()
  return diff < 7 * 24 * 60 * 60 * 1000 // < 7 days
}

const formatDate = (d) => {
  if (!d) return ''
  return new Date(d).toLocaleDateString('vi-VN')
}

const formatPrice = (v) => {
  if (v == null) return ''
  return Number(v).toLocaleString('vi-VN') + '\u0111'
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
</style>