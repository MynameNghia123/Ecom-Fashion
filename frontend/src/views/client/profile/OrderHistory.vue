<template>
  <div class="space-y-8 animate-fade-in text-[#111111]">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title leading-tight">Lịch sử đơn hàng</h1>
      <p class="text-sm text-neutral-400 mt-2 font-text leading-relaxed max-w-md">
        Xem lại và theo dõi các đơn hàng đã mua và đang mua. Bạn có thể xem chi tiết đơn hàng hoặc bắt đầu trả hàng bên dưới.
      </p>
    </div>

    <!-- Tabs -->
    <div class="border-b border-neutral-200">
      <div class="flex gap-0">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
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
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Mã đơn hàng</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Ngày đặt</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Trạng thái</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%]">Tổng tiền</th>
            <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-neutral-400 w-[20%] text-right">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
          <tr
            v-for="order in paginatedOrders"
            :key="order.id"
            class="hover:bg-neutral-50/50 transition-colors"
          >
            <td class="py-4 text-[13px] font-bold tracking-wide text-neutral-800">{{ order.id }}</td>
            <td class="py-4 text-[13px] text-neutral-500 font-normal">{{ order.date }}</td>
            <td class="py-4">
              <span
                :class="[
                  'inline-block text-[9px] font-bold tracking-wider uppercase px-2.5 py-1 border',
                  order.status === 'ĐÃ GIAO'     ? 'border-neutral-400 text-neutral-700 bg-white' :
                  order.status === 'ĐANG VẬN CHUYỂN' ? 'border-black bg-black text-white' :
                  order.status === 'ĐÃ HỦY'      ? 'border-red-500 text-red-600 bg-white' :
                  order.status === 'ĐANG XỬ LÝ'  ? 'border-amber-500 text-amber-600 bg-white' :
                  'border-neutral-400 text-neutral-600 bg-white'
                ]"
              >
                {{ order.status }}
              </span>
            </td>
            <td class="py-4 text-[13px] font-medium text-neutral-800">{{ order.total }}</td>
            <td class="py-4 text-right">
              <button class="text-[10px] font-bold uppercase tracking-wider underline hover:text-neutral-500 transition-colors bg-transparent border-none cursor-pointer">
                Xem chi tiết
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Footer: Count + Pagination -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-neutral-100">
      <p class="text-[11px] text-neutral-400 font-text">
        hiện thị {{ (currentPage - 1) * pageSize + 1 }} đến {{ Math.min(currentPage * pageSize, filteredOrders.length) }} trong tổng số {{ filteredOrders.length }} đơn hàng
      </p>

      <!-- Pagination -->
      <div class="flex items-center gap-1">
        <button
          @click="currentPage = Math.max(1, currentPage - 1)"
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
          @click="currentPage = Math.min(totalPages, currentPage + 1)"
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
import { ref, computed } from 'vue'

const tabs = [
  { key: 'all',        label: 'Tất cả đơn hàng' },
  { key: 'processing', label: 'Đang xử lý' },
  { key: 'completed',  label: 'Hoàn thành' }
]

const activeTab  = ref('all')
const currentPage = ref(1)
const pageSize    = 5

// Mock data
const orders = ref([
  { id: '#NF-829103', date: '24/10/2023', status: 'ĐÃ GIAO',           total: '$1,240.00' },
  { id: '#NF-718293', date: '12/10/2023', status: 'ĐANG VẬN CHUYỂN',  total: '$450.00'   },
  { id: '#NF-625419', date: '30/09/2023', status: 'ĐÃ HỦY',            total: '$89.00'    },
  { id: '#NF-552190', date: '15/09/2023', status: 'ĐÃ GIAO',           total: '$3,120.50' },
  { id: '#NF-411209', date: '28/08/2023', status: 'ĐÃ GIAO',           total: '$210.00'   },
  { id: '#NF-398120', date: '10/08/2023', status: 'ĐANG XỬ LÝ',       total: '$680.00'   },
  { id: '#NF-374521', date: '02/08/2023', status: 'ĐÃ GIAO',           total: '$540.00'   },
  { id: '#NF-351008', date: '20/07/2023', status: 'ĐÃ GIAO',           total: '$920.00'   },
  { id: '#NF-330045', date: '05/07/2023', status: 'ĐANG XỬ LÝ',       total: '$155.00'   },
  { id: '#NF-299817', date: '18/06/2023', status: 'ĐÃ GIAO',           total: '$2,450.00' },
  { id: '#NF-278234', date: '01/06/2023', status: 'ĐÃ HỦY',            total: '$360.00'   },
  { id: '#NF-255511', date: '14/05/2023', status: 'ĐÃ GIAO',           total: '$780.00'   },
])

const filteredOrders = computed(() => {
  if (activeTab.value === 'processing') {
    return orders.value.filter(o => o.status === 'ĐANG XỬ LÝ' || o.status === 'ĐANG VẬN CHUYỂN')
  }
  if (activeTab.value === 'completed') {
    return orders.value.filter(o => o.status === 'ĐÃ GIAO')
  }
  return orders.value
})

const totalPages = computed(() => Math.ceil(filteredOrders.value.length / pageSize))

const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredOrders.value.slice(start, start + pageSize)
})

// Reset to page 1 when changing tabs
import { watch } from 'vue'
watch(activeTab, () => { currentPage.value = 1 })
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