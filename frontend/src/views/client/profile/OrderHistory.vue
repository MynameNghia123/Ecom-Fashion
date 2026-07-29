<template>
  <div class="space-y-8 animate-fade-in text-[#111111]">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title leading-tight">Lịch sử đơn hàng</h1>
      <p class="text-sm text-neutral-400 mt-2 font-text leading-relaxed max-w-md">
        Xem lại và theo dõi các đơn hàng đã mua và đang mua.
      </p>
    </div>

    <!-- Tabs -->
    <div class="border-b border-neutral-200">
      <div class="flex gap-0 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="switchTab(tab.key)"
          :class="[
            'px-0 py-3 mr-8 text-[11px] font-bold uppercase tracking-widest transition-colors font-text cursor-pointer bg-transparent border-none',
            activeTab === tab.key
              ? 'text-neutral-900 border-b-2 border-neutral-900 -mb-px'
              : 'text-neutral-400 hover:text-neutral-700'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse min-w-[600px]">
        <thead>
          <tr class="border-b border-neutral-200">
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[25%]">Mã đơn hàng</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Ngày đặt</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Trạng thái</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Tổng tiền</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[15%] text-right">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
          <!-- Loading -->
          <tr v-if="loading">
            <td colspan="5" class="py-10 text-center text-sm text-neutral-400">
              <span class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Đang tải danh sách đơn hàng...
              </span>
            </td>
          </tr>
          <!-- Error -->
          <tr v-else-if="error">
            <td colspan="5" class="py-10 text-center text-sm text-rose-500">{{ error }}</td>
          </tr>
          <!-- Empty -->
          <tr v-else-if="filteredOrders.length === 0">
            <td colspan="5" class="py-12 text-center">
              <p class="text-sm text-neutral-400">Bạn chưa có đơn hàng nào trong mục này.</p>
              <router-link to="/" class="mt-3 inline-block text-[11px] font-bold uppercase tracking-widest underline text-neutral-700">
                Tiếp tục mua sắm
              </router-link>
            </td>
          </tr>
          <!-- Data rows -->
          <tr
            v-else
            v-for="order in paginatedOrders"
            :key="order.id"
            class="hover:bg-neutral-50/50 transition-colors"
          >
            <td class="py-4 text-[13px] font-bold tracking-wide text-neutral-800 font-mono">{{ order.order_code }}</td>
            <td class="py-4 text-[13px] text-neutral-500 font-normal">{{ formatDate(order.created_at) }}</td>
            <td class="py-4">
              <span :class="statusClass(order.status)">
                {{ statusText(order.status) }}
              </span>
            </td>
            <td class="py-4 text-[13px] font-medium text-neutral-800 font-mono">{{ formatPrice(order.final_amount) }}</td>
            <td class="py-4 text-right">
              <router-link
                :to="{ name: 'CheckoutSuccess', query: { code: order.order_code } }"
                class="text-[10px] font-bold uppercase tracking-wider hover:text-neutral-500 transition-colors text-neutral-900"
                style="text-decoration: underline"
              >
                Xem chi tiết
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Footer: Count + Pagination -->
    <div v-if="filteredOrders.length > 0" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-neutral-100">
      <p class="text-[11px] text-neutral-400 font-text">
        Hiển thị {{ rangeStart }} - {{ rangeEnd }} trong tổng số {{ filteredOrders.length }} đơn hàng
      </p>

      <!-- Pagination -->
      <div class="flex items-center gap-1">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="w-8 h-8 flex items-center justify-center border border-neutral-200 hover:border-neutral-900 text-neutral-500 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors bg-transparent cursor-pointer"
        >
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        </button>

        <button
          v-for="page in totalPages"
          :key="page"
          @click="currentPage = page"
          :class="[
            'w-8 h-8 flex items-center justify-center border text-[11px] font-bold transition-colors cursor-pointer',
            currentPage === page
              ? 'border-neutral-900 bg-neutral-900 text-white'
              : 'border-neutral-200 hover:border-neutral-900 text-neutral-500 hover:text-neutral-900 bg-transparent'
          ]"
        >
          {{ page }}
        </button>

        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="w-8 h-8 flex items-center justify-center border border-neutral-200 hover:border-neutral-900 text-neutral-500 hover:text-neutral-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors bg-transparent cursor-pointer"
        >
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { orderService } from '@/services/client/orderService'

const tabs = [
  { key: 'all',        label: 'Tất cả' },
  { key: 'processing', label: 'Đang xử lý' },
  { key: 'shipping',   label: 'Đang giao' },
  { key: 'completed',  label: 'Hoàn thành' },
  { key: 'cancelled',  label: 'Đã hủy' },
]

const activeTab   = ref('all')
const currentPage = ref(1)
const PAGE_SIZE   = 8

const orders  = ref([])
const loading = ref(false)
const error   = ref(null)

const fetchOrders = async () => {
  loading.value = true
  error.value   = null
  try {
    const res = await orderService.getOrders()
    if (res.data?.success) orders.value = res.data.data
  } catch (e) {
    error.value = e.response?.data?.message || 'Không thể tải đơn hàng. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchOrders)

const switchTab = (key) => {
  activeTab.value   = key
  currentPage.value = 1
}

const filteredOrders = computed(() => {
  if (activeTab.value === 'all') return orders.value
  if (activeTab.value === 'processing') return orders.value.filter(o => ['pending', 'confirmed'].includes(o.status))
  return orders.value.filter(o => o.status === activeTab.value)
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredOrders.value.length / PAGE_SIZE)))

const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * PAGE_SIZE
  return filteredOrders.value.slice(start, start + PAGE_SIZE)
})

const rangeStart = computed(() => (currentPage.value - 1) * PAGE_SIZE + 1)
const rangeEnd   = computed(() => Math.min(currentPage.value * PAGE_SIZE, filteredOrders.value.length))

watch(activeTab, () => { currentPage.value = 1 })

const formatDate = (d) => {
  if (!d) return ''
  return new Date(d).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const formatPrice = (v) => {
  if (v == null) return '0 \u0111'
  return Number(v).toLocaleString('vi-VN') + ' \u0111'
}

const STATUS_MAP = {
  pending:   { text: 'ĐANG XỬ LÝ',      cls: 'border-amber-500 text-amber-600 bg-white' },
  confirmed: { text: 'ĐÃ XÁC NHẬN',     cls: 'border-blue-500 text-blue-600 bg-white' },
  shipping:  { text: 'ĐANG VẬN CHUYỂN', cls: 'border-black bg-black text-white' },
  completed: { text: 'ĐÃ GIAO',          cls: 'border-neutral-400 text-neutral-700 bg-white' },
  cancelled: { text: 'ĐÃ HỦY',          cls: 'border-red-500 text-red-600 bg-white' },
}

const statusText  = (s) => STATUS_MAP[s]?.text  || (s || '').toUpperCase()
const statusClass = (s) => [
  'inline-block text-[9px] font-bold tracking-wider uppercase px-2.5 py-1 border',
  STATUS_MAP[s]?.cls || 'border-neutral-400 text-neutral-600 bg-white'
]
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>