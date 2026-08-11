<template>
  <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl animate-slide-up">
      <!-- Header -->
      <div class="bg-gradient-to-r from-[#0055FF] to-[#00AAFF] px-6 py-5 text-white">
        <div class="flex items-center justify-between mb-1">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
            </div>
            <div>
              <h2 class="font-bold text-lg leading-tight">Thanh toán chuyển khoản</h2>
              <p class="text-blue-100 text-xs">Quét mã QR để thanh toán</p>
            </div>
          </div>
          <button @click="$emit('close')" class="text-white/70 hover:text-white transition-colors cursor-pointer bg-transparent border-none p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-6">
        <!-- Loading state -->
        <div v-if="loading" class="text-center py-10">
          <div class="inline-block w-10 h-10 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin mb-3"></div>
          <p class="text-sm text-neutral-500">Đang tải thông tin thanh toán...</p>
        </div>

        <!-- Paid state -->
        <div v-else-if="isPaid" class="text-center py-10">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <h3 class="font-bold text-xl text-green-600 mb-2">Thanh toán thành công!</h3>
          <p class="text-sm text-neutral-500 mb-6">Đơn hàng {{ orderCode }} đã được xác nhận.</p>
          <button
            @click="handleSuccess"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg text-sm uppercase tracking-wider transition-colors cursor-pointer border-none"
          >
            Xem đơn hàng
          </button>
        </div>

        <!-- QR code state -->
        <div v-else-if="paymentInfo" class="space-y-4">
          <!-- QR Image -->
          <div class="flex justify-center">
            <div class="relative">
              <img
                :src="paymentInfo.qr_url"
                :alt="`QR Code thanh toán đơn ${orderCode}`"
                class="w-52 h-52 object-contain border border-neutral-200 rounded-xl p-2 shadow-sm"
              />
            </div>
          </div>

          <!-- Bank info -->
          <div class="bg-neutral-50 rounded-xl p-4 space-y-3 border border-neutral-100">
            <div class="flex justify-between items-center">
              <span class="text-xs text-neutral-500 font-medium">Ngân hàng</span>
              <span class="text-sm font-bold text-black">{{ paymentInfo.bank_name }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs text-neutral-500 font-medium">Số tài khoản</span>
              <div class="flex items-center gap-2">
                <span class="text-sm font-mono font-bold text-black">{{ paymentInfo.bank_account }}</span>
                <button @click="copyText(paymentInfo.bank_account)" class="text-blue-500 hover:text-blue-700 cursor-pointer bg-transparent border-none p-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs text-neutral-500 font-medium">Số tiền</span>
              <span class="text-sm font-bold text-blue-600">{{ formatPrice(paymentInfo.amount) }}đ</span>
            </div>
            <div class="flex justify-between items-start">
              <span class="text-xs text-neutral-500 font-medium">Nội dung CK</span>
              <div class="flex items-center gap-2">
                <span class="text-sm font-mono font-bold text-black">{{ paymentInfo.transfer_content }}</span>
                <button @click="copyText(paymentInfo.transfer_content)" class="text-blue-500 hover:text-blue-700 cursor-pointer bg-transparent border-none p-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Status polling info -->
          <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">
            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse shrink-0"></div>
            <p class="text-xs text-blue-700">
              Đang chờ xác nhận thanh toán... (tự động cập nhật mỗi 5 giây)
            </p>
          </div>

          <!-- Copied toast -->
          <div v-if="copiedMsg" class="text-center text-xs text-green-600 font-medium animate-fade-in">
            ✓ Đã sao chép!
          </div>

          <!-- Note -->
          <p class="text-[11px] text-neutral-400 text-center leading-relaxed">
            Vui lòng chuyển khoản đúng <strong>số tiền</strong> và <strong>nội dung</strong> để đơn hàng được xác nhận tự động.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/axios'

const props = defineProps({
  orderCode: { type: String, required: true }
})

const emit = defineEmits(['close', 'success'])

const router = useRouter()
const loading = ref(true)
const paymentInfo = ref(null)
const isPaid = ref(false)
const copiedMsg = ref(false)
let pollInterval = null

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const copyText = async (text) => {
  try {
    await navigator.clipboard.writeText(text)
    copiedMsg.value = true
    setTimeout(() => { copiedMsg.value = false }, 2000)
  } catch {
    // fallback
    const el = document.createElement('textarea')
    el.value = text
    document.body.appendChild(el)
    el.select()
    document.execCommand('copy')
    document.body.removeChild(el)
    copiedMsg.value = true
    setTimeout(() => { copiedMsg.value = false }, 2000)
  }
}

const loadPaymentInfo = async () => {
  try {
    const res = await api.get(`/client/sepay/info/${props.orderCode}`)
    if (res.data?.success) {
      paymentInfo.value = res.data.data
      if (res.data.paid) {
        isPaid.value = true
        stopPolling()
      }
    }
  } catch (err) {
    console.error('[SePayQR] Load payment info error:', err)
  } finally {
    loading.value = false
  }
}

const pollStatus = async () => {
  try {
    const res = await api.get(`/client/sepay/check/${props.orderCode}`)
    if (res.data?.success && res.data?.paid) {
      isPaid.value = true
      stopPolling()
    }
  } catch (err) {
    console.error('[SePayQR] Poll status error:', err)
  }
}

const startPolling = () => {
  pollInterval = setInterval(pollStatus, 5000)
}

const stopPolling = () => {
  if (pollInterval) {
    clearInterval(pollInterval)
    pollInterval = null
  }
}

const handleSuccess = () => {
  emit('success', props.orderCode)
  router.push({ name: 'CheckoutSuccess', query: { code: props.orderCode } })
}

onMounted(async () => {
  await loadPaymentInfo()
  if (!isPaid.value) {
    startPolling()
  }
})

onUnmounted(() => {
  stopPolling()
})
</script>

<style scoped>
.animate-slide-up {
  animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(24px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}
</style>
