<template>
  <div class="space-y-8 animate-fade-in font-text text-slate-800">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold tracking-tight">Lịch sử Yêu cầu Hoàn/Trả hàng</h2>
        <p class="text-sm text-slate-500 mt-1">Theo dõi trạng thái các yêu cầu đổi trả sản phẩm của bạn.</p>
      </div>
      <button @click="fetchReturns" class="p-2 bg-slate-100 hover:bg-slate-200 rounded-full transition-colors">
        <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
        </svg>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-20">
      <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
      <p class="text-sm text-slate-500 mt-4">Đang tải dữ liệu...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex flex-col items-center justify-center py-16 bg-rose-50 rounded-2xl border border-rose-100">
      <svg class="w-10 h-10 text-rose-400 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <p class="text-sm font-semibold text-rose-600">{{ error }}</p>
      <button @click="fetchReturns" class="mt-4 px-4 py-2 bg-rose-600 text-white text-xs font-semibold rounded-xl hover:bg-rose-700 transition-colors">Thử lại</button>
    </div>

    <!-- Empty State -->
    <div v-else-if="returnRequests.length === 0" class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
      <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-sm">
        <svg class="w-8 h-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M21 8l-9 5-9-5M21 16v-8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
      <h3 class="text-sm font-semibold text-slate-800">Không có yêu cầu hoàn trả nào</h3>
      <p class="text-xs text-slate-500 mt-1">Bạn chưa thực hiện bất kỳ yêu cầu hoàn trả sản phẩm nào.</p>
    </div>

    <!-- Returns List -->
    <div v-else class="space-y-4">
      <div v-for="req in returnRequests" :key="req.id" class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
          <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-slate-900">{{ req.ticket_code }}</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span class="text-xs text-slate-500">{{ formatDate(req.created_at) }}</span>
          </div>
          <span :class="getStatusClass(req.status)">
            {{ getStatusText(req.status) }}
          </span>
        </div>

        <!-- Product Details -->
        <div class="p-5 flex flex-col md:flex-row gap-6">
          <div class="flex-1 flex gap-4">
            <div class="w-20 h-24 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
              <img :src="getImageUrl(req.order_detail?.product_variant?.thumbnail || req.order_detail?.product_variant?.product?.thumbnail)" alt="Product" class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ req.order_detail?.product_variant?.product?.name || 'Sản phẩm' }}</h4>
              <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                Phân loại: <span class="font-medium text-slate-700">{{ getVariantAttributes(req.order_detail?.product_variant) }}</span>
              </p>
              <div class="flex items-center gap-3 mt-2 text-xs">
                <span class="text-slate-600">Số lượng trả: <strong class="text-slate-900">{{ req.quantity || 1 }}</strong></span>
                <span class="text-slate-800 font-bold border border-slate-200 bg-slate-50 px-2 py-0.5 rounded font-mono">
                  Hoàn tiền: {{ formatPrice(getRefundAmount(req)) }} đ
                </span>
              </div>
            </div>
          </div>
          
          <div class="w-full md:w-[35%] bg-slate-50 rounded-lg p-3 text-xs flex flex-col justify-center border border-slate-100">
            <p class="text-slate-700 mb-1"><span class="font-medium text-slate-900">Mã đơn gốc:</span> #{{ req.order?.order_code || 'N/A' }}</p>
            <p class="text-slate-700 mb-1"><span class="font-medium text-slate-900">Lý do:</span> {{ getReasonLabel(req.reason) }}</p>
            <p v-if="req.customer_note" class="text-slate-700 italic">"{{ req.customer_note }}"</p>
          </div>
        </div>

        <!-- Admin Response -->
        <div v-if="req.admin_note" class="bg-slate-100/70 border-t border-slate-200 px-5 py-3">
          <p class="text-xs text-slate-800"><span class="font-bold">Phản hồi từ Shop:</span> {{ req.admin_note }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import returnRequestService from '@/services/client/returnRequestService'

const returnRequests = ref([])
const loading = ref(true)
const error = ref('')

const fetchReturns = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await returnRequestService.getReturnRequests()
    if (res.data && res.data.success) {
      returnRequests.value = res.data.data
    }
  } catch (err) {
    console.error("Lỗi khi tải danh sách hoàn trả:", err)
    error.value = err.response?.data?.message || 'Có lỗi xảy ra khi tải danh sách yêu cầu hoàn trả. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchReturns()
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  if (/^\d{2}\/\d{2}\/\d{4}/.test(dateString)) return dateString
  try {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('vi-VN', {
      hour: '2-digit', minute: '2-digit',
      day: '2-digit', month: '2-digit', year: 'numeric'
    }).format(date)
  } catch {
    return dateString
  }
}

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const getRefundAmount = (req) => {
  if (req.refund_amount && Number(req.refund_amount) > 0) {
    return Number(req.refund_amount)
  }
  const unitPrice = Number(req.order_detail?.unit_price || 0)
  const qty = Number(req.quantity || 1)
  return unitPrice * qty
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const getVariantAttributes = (variant) => {
  if (!variant || !variant.attribute_values || variant.attribute_values.length === 0) return 'Mặc định'
  return variant.attribute_values.map(av => av.value).join(' - ')
}

const getReasonLabel = (reason) => {
  const map = {
    defective: 'Sản phẩm lỗi / Rách',
    wrong_size: 'Không vừa size',
    wrong_item: 'Giao sai màu / mẫu',
    change_mind: 'Đổi ý không muốn mua'
  }
  return map[reason] || reason || 'Lý do khác'
}

const STATUS_MAP = {
  pending:  { text: 'CHỜ XỬ LÝ',    cls: 'border-amber-500 text-amber-600 bg-white' },
  approved: { text: 'ĐÃ CHẤP NHẬN', cls: 'border-blue-500 text-blue-600 bg-white' },
  received: { text: 'ĐÃ NHẬN HÀNG', cls: 'border-purple-500 text-purple-600 bg-white' },
  refunded: { text: 'ĐÃ HOÀN TIỀN', cls: 'border-emerald-600 text-emerald-700 bg-white' },
  rejected: { text: 'TỪ CHỐI',      cls: 'border-red-500 text-red-600 bg-white' },
}

const getStatusText = (status) => STATUS_MAP[status]?.text || (status || '').toUpperCase()
const getStatusClass = (status) => [
  'inline-block text-[9px] font-bold tracking-wider uppercase px-2.5 py-1 border',
  STATUS_MAP[status]?.cls || 'border-neutral-400 text-neutral-600 bg-white'
]
</script>
