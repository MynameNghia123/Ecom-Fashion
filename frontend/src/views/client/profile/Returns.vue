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
          <span :class="getStatusClass(req.status)" class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">
            {{ getStatusText(req.status) }}
          </span>
        </div>

        <!-- Product Details -->
        <div class="p-5 flex flex-col md:flex-row gap-6">
          <div class="flex-1 flex gap-4">
            <div class="w-20 h-24 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
              <img :src="getImageUrl(req.order_detail?.product_variant?.product?.thumbnail)" alt="Product" class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ req.order_detail?.product_variant?.product?.name || 'Sản phẩm' }}</h4>
              <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                Phân loại: <span class="font-medium text-slate-700">{{ getVariantAttributes(req.order_detail?.product_variant) }}</span>
              </p>
              <div class="flex items-center gap-3 mt-2 text-xs">
                <span class="text-slate-600">Số lượng trả: <strong class="text-slate-900">{{ req.quantity }}</strong></span>
                <span class="text-rose-600 font-bold border border-rose-100 bg-rose-50 px-2 py-0.5 rounded">
                  Hoàn tiền: {{ formatPrice(req.refund_amount) }}đ
                </span>
              </div>
            </div>
          </div>
          
          <div class="w-full md:w-[35%] bg-slate-50 rounded-lg p-3 text-xs flex flex-col justify-center">
            <p class="text-slate-700 mb-1"><span class="font-medium text-slate-900">Mã đơn gốc:</span> #{{ req.order?.order_code }}</p>
            <p class="text-slate-700 mb-1"><span class="font-medium text-slate-900">Lý do:</span> {{ req.reason }}</p>
            <p v-if="req.customer_note" class="text-slate-700 italic">"{{ req.customer_note }}"</p>
          </div>
        </div>

        <!-- Admin Response -->
        <div v-if="req.admin_note" class="bg-blue-50/50 border-t border-blue-100 px-5 py-3">
          <p class="text-xs text-blue-800"><span class="font-bold">Phản hồi từ Shop:</span> {{ req.admin_note }}</p>
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

const fetchReturns = async () => {
  loading.value = true
  try {
    const res = await returnRequestService.getReturnRequests()
    if (res.data && res.data.success) {
      returnRequests.value = res.data.data
    }
  } catch (error) {
    console.error("Lỗi khi tải danh sách hoàn trả:", error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchReturns()
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('vi-VN', {
    hour: '2-digit', minute: '2-digit',
    day: '2-digit', month: '2-digit', year: 'numeric'
  }).format(date)
}

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const getVariantAttributes = (variant) => {
  if (!variant || !variant.attribute_values) return 'Mặc định'
  return variant.attribute_values.map(av => av.value).join(' - ')
}

const getStatusText = (status) => {
  const map = {
    'pending': 'Chờ xử lý',
    'approved': 'Đã chấp nhận',
    'received': 'Đã nhận hàng',
    'refunded': 'Đã hoàn tiền',
    'rejected': 'Từ chối'
  }
  return map[status] || status
}

const getStatusClass = (status) => {
  const map = {
    'pending': 'bg-amber-50 text-amber-600 border border-amber-200',
    'approved': 'bg-blue-50 text-blue-600 border border-blue-200',
    'received': 'bg-indigo-50 text-indigo-600 border border-indigo-200',
    'refunded': 'bg-emerald-50 text-emerald-600 border border-emerald-200',
    'rejected': 'bg-rose-50 text-rose-600 border border-rose-200'
  }
  return map[status] || 'bg-slate-50 text-slate-600 border border-slate-200'
}
</script>
