<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Quản lý Trả hàng</h1>
        <p class="text-sm text-slate-500 mt-1">Quản lý và xử lý các yêu cầu đổi trả hàng từ khách hàng</p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-all shadow-sm active:scale-[0.98] cursor-pointer"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tạo đơn trả hàng mới
      </button>
    </div>

    <!-- 4 Thống kê (Stats Cards) đồng bộ với Staff.vue / Customer.vue -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="s in stats"
        :key="s.label"
        class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200"
      >
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ s.label }}</p>
          <p class="text-3xl font-bold text-slate-800">{{ s.value }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" :class="s.bgBg">
          <component :is="s.icon" class="w-6 h-6" :class="s.iconColor" />
        </div>
      </div>
    </div>

    <!-- Thanh tìm kiếm + lọc -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
      <div class="flex flex-col sm:flex-row gap-3 flex-1">
        <div class="relative flex-1 max-w-xs">
          <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/>
              <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo mã đơn hoặc lý do..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
            @input="handleSearch"
          />
        </div>

        <div class="relative">
          <select
            v-model="statusFilter"
            @change="handleFilterChange"
            class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer font-medium"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="pending">Đang chờ (Pending)</option>
            <option value="approved">Đã duyệt (Approved)</option>
            <option value="rejected">Từ chối (Rejected)</option>
            <option value="completed">Hoàn thành (Completed)</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>
      </div>
    </div>

    <!-- Bảng danh sách -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mã yêu cầu / Đơn hàng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày tạo</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Lý do</th>
              <th class="py-3.5 px-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Minh chứng</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Số tiền hoàn</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <!-- Loading skeleton -->
            <template v-if="loading">
              <tr v-for="i in meta.per_page" :key="'sk-' + i">
                <td colspan="7" class="py-4 px-5">
                  <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
                </td>
              </tr>
            </template>

            <!-- Error -->
            <tr v-else-if="error">
              <td colspan="7" class="py-4 px-5 text-center text-rose-500 font-medium">
                {{ error }}
              </td>
            </tr>

            <!-- Content items -->
            <template v-else>
              <tr
                v-for="item in returnRequests"
                :key="item.id"
                class="hover:bg-blue-50/40 transition-colors duration-100 group"
              >
                <td class="py-4 px-5">
                  <button @click="openDetailModal(item)" class="text-[#0258cb] font-semibold hover:underline text-left leading-tight">
                    #{{ item.id }} <span class="text-xs text-slate-400 font-normal">({{ item.order_code || item.order_id }})</span>
                  </button>
                </td>
                <td class="py-4 px-4 text-xs text-slate-500">{{ item.created_at || '—' }}</td>
                <td class="py-4 px-4 text-slate-600 text-sm max-w-[220px] truncate" :title="item.reason">{{ item.reason }}</td>
                <td class="py-4 px-4 text-center">
                  <span v-if="item.evidence_images && item.evidence_images.length" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                      <circle cx="8.5" cy="8.5" r="1.5"/>
                      <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    {{ item.evidence_images.length }} ảnh
                  </span>
                  <span v-else class="text-xs text-slate-300">—</span>
                </td>
                <td class="py-4 px-4 text-right font-bold text-slate-800 text-sm">{{ formatCurrency(item.refund_amount) }}</td>
                <td class="py-4 px-4">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold" :class="statusStyle(item.status)">
                    {{ statusLabel(item.status) }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <button
                      type="button"
                      @click="openDetailModal(item)"
                      class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                      title="Xem chi tiết"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                    <button
                      v-if="item.status === 'pending'"
                      type="button"
                      @click="openEditModal(item)"
                      class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150"
                      title="Chỉnh sửa"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button
                      type="button"
                      @click="openDeleteModal(item)"
                      class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all duration-150"
                      title="Xóa yêu cầu"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </template>

            <!-- Empty state -->
            <tr v-if="!loading && !error && returnRequests.length === 0">
              <td colspan="7" class="py-8 px-5 text-center text-slate-400">
                Không tìm thấy đơn trả hàng nào phù hợp.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination component -->
      <div class="px-5 py-3 border-t border-slate-100 bg-white">
        <Pagination
          :current-page="meta.current_page"
          :last-page="meta.last_page"
          :total="meta.total"
          :per-page="meta.per_page"
          :loading="loading"
          @update:current-page="onPageChange"
          @update:per-page="onPerPageChange"
        />
      </div>
    </div>

    <!-- Modals -->
    <ReturnRequestDetailModal
      :show="showDetailModal"
      :item="selectedItem"
      @close="showDetailModal = false"
      @approve="handleQuickStatus($event, 'approved')"
      @reject="handleQuickStatus($event, 'rejected')"
    />

    <ReturnRequestFormModal
      :show="showFormModal"
      :item="editingItem"
      @close="showFormModal = false"
      @save="handleSaveForm"
    />

    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xác nhận xóa đơn trả hàng"
      message="Bạn có chắc chắn muốn xóa yêu cầu trả hàng"
      :item-name="deletingItem ? `#${deletingItem.id}` : ''"
      message-suffix="không?"
      confirm-label="Xóa ngay"
      @cancel="showDeleteModal = false"
      @confirm="handleConfirmDelete"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, h } from 'vue'
import { storeToRefs } from 'pinia'
import { useReturnRequestStore } from '@/stores/admin/returnRequestStore'

import ReturnRequestDetailModal from '@/components/admin/returnRequest/ReturnRequestDetailModal.vue'
import ReturnRequestFormModal from '@/components/admin/returnRequest/ReturnRequestFormModal.vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'

// Professional Feather SVG icons for Stats Cards
const IconReturns = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('polyline', { points: '1 4 1 10 7 10' }),
  h('path', { d: 'M3.51 15a9 9 0 1 0 2.13-9.36L1 10' })
])
const IconPending = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('circle', { cx: '12', cy: '12', r: '10' }),
  h('polyline', { points: '12 6 12 12 16 14' })
])
const IconApproved = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('path', { d: 'M22 11.08V12a10 10 0 1 1-5.93-9.14' }),
  h('polyline', { points: '22 4 12 14.01 9 11.01' })
])
const IconRefund = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('line', { x1: '12', y1: '1', x2: '12', y2: '23' }),
  h('path', { d: 'M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6' })
])

// Store Setup
const returnRequestStore = useReturnRequestStore()
const { returnRequests, meta, loading, error } = storeToRefs(returnRequestStore)

const searchQuery = ref('')
const statusFilter = ref('')

// Modal States
const showDetailModal = ref(false)
const selectedItem = ref(null)

const showFormModal = ref(false)
const editingItem = ref(null)

const showDeleteModal = ref(false)
const deletingItem = ref(null)

// Computed Stats
const stats = computed(() => {
  const total = meta.value.total || returnRequests.value.length
  const pending = returnRequests.value.filter(r => r.status === 'pending').length
  const approved = returnRequests.value.filter(r => r.status === 'approved' || r.status === 'completed').length
  const refunded = returnRequests.value
    .filter(r => r.status === 'approved' || r.status === 'completed')
    .reduce((sum, r) => sum + (parseFloat(r.refund_amount) || 0), 0)

  return [
    { label: 'Tổng số trả hàng', value: total, icon: IconReturns, iconColor: 'text-[#0258cb]', bgBg: 'bg-blue-50' },
    { label: 'Đang chờ xử lý', value: pending, icon: IconPending, iconColor: 'text-amber-500', bgBg: 'bg-amber-50' },
    { label: 'Đã chấp nhận', value: approved, icon: IconApproved, iconColor: 'text-emerald-500', bgBg: 'bg-emerald-50' },
    { label: 'Tổng số tiền hoàn', value: formatCurrency(refunded), icon: IconRefund, iconColor: 'text-violet-500', bgBg: 'bg-violet-50' },
  ]
})

onMounted(() => {
  returnRequestStore.initialFetch()
})

// Handlers
let searchTimeout = null
function handleSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    returnRequestStore.fetchReturnRequests({
      search: searchQuery.value,
      status: statusFilter.value,
      page: 1,
    })
  }, 300)
}

function handleFilterChange() {
  returnRequestStore.fetchReturnRequests({
    search: searchQuery.value,
    status: statusFilter.value,
    page: 1,
  })
}

function onPageChange(page) {
  returnRequestStore.fetchReturnRequests({
    search: searchQuery.value,
    status: statusFilter.value,
    page,
  })
}

function onPerPageChange(perPage) {
  meta.value.per_page = perPage
  returnRequestStore.fetchReturnRequests({
    search: searchQuery.value,
    status: statusFilter.value,
    page: 1,
  })
}

function openDetailModal(item) {
  selectedItem.value = item
  showDetailModal.value = true
}

function openCreateModal() {
  editingItem.value = null
  showFormModal.value = true
}

function openEditModal(item) {
  editingItem.value = item
  showFormModal.value = true
}

async function handleSaveForm(formData) {
  try {
    if (editingItem.value) {
      await returnRequestStore.updateReturnRequest(editingItem.value.id, formData)
    } else {
      await returnRequestStore.createReturnRequest(formData)
    }
    showFormModal.value = false
  } catch (e) {
    console.error('Error saving return request:', e)
  }
}

async function handleQuickStatus(item, newStatus) {
  try {
    await returnRequestStore.updateReturnRequest(item.id, {
      reason: item.reason,
      refund_amount: item.refund_amount,
      status: newStatus,
    })
    showDetailModal.value = false
  } catch (e) {
    console.error('Error updating status:', e)
  }
}

function openDeleteModal(item) {
  deletingItem.value = item
  showDeleteModal.value = true
}

async function handleConfirmDelete() {
  if (!deletingItem.value) return
  try {
    await returnRequestStore.deleteReturnRequest(deletingItem.value.id)
    showDeleteModal.value = false
    deletingItem.value = null
  } catch (e) {
    console.error('Error deleting return request:', e)
  }
}

function formatCurrency(v) {
  if (!v && v !== 0) return '0 đ'
  return new Intl.NumberFormat('vi-VN').format(v) + ' đ'
}

function statusLabel(status) {
  const map = {
    pending: 'Đang chờ',
    approved: 'Đã duyệt',
    rejected: 'Từ chối',
    completed: 'Hoàn thành',
  }
  return map[status] || status
}

function statusStyle(status) {
  const map = {
    pending: 'bg-amber-50 text-amber-700 border border-amber-200',
    approved: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    rejected: 'bg-red-50 text-red-600 border border-red-200',
    completed: 'bg-blue-50 text-blue-700 border border-blue-200',
  }
  return map[status] || 'bg-slate-100 text-slate-600'
}
</script>