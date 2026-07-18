<template>
  <div class="space-y-6">

    <h1 class="text-2xl font-bold text-slate-900 mb-6">Quản lý Trả hàng</h1>

    <!-- 4 thẻ thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div
        v-for="s in stats"
        :key="s.label"
        class="bg-white rounded-xl border border-slate-200 p-5"
      >
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm text-slate-500">{{ s.label }}</span>
          <!-- Render Icon directly using dynamic component or standard inline rendering -->
          <component :is="s.icon" class="w-5 h-5" :class="s.iconColor" />
        </div>
        <div class="text-2xl font-bold text-slate-900">{{ s.value }}</div>
        <div v-if="s.trend" class="text-xs mt-1" :class="s.trendUp ? 'text-emerald-600' : 'text-rose-500'">
          {{ s.trendUp ? '↑' : '↓' }} {{ s.trend }} so với tháng trước
        </div>
      </div>
    </div>

    <!-- Thanh tìm kiếm + lọc + nút tạo mới -->
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between mb-4">
      <div class="flex flex-col sm:flex-row gap-3 flex-1">
        <div class="relative flex-1 max-w-xs">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm mã đơn hàng..."
            class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
          />
        </div>

        <select v-model="statusFilter" class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
          <option value="all">Tất cả trạng thái</option>
          <option value="Pending">Đang chờ</option>
          <option value="Approved">Đã duyệt</option>
          <option value="Rejected">Từ chối</option>
        </select>
      </div>

      <button
        @click="openCreate"
        class="inline-flex items-center gap-2 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors"
      >
        + Tạo đơn trả hàng mới
      </button>
    </div>

    <!-- Bảng danh sách -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
              <th class="text-left px-5 py-3 font-medium">Mã đơn</th>
              <th class="text-left px-5 py-3 font-medium">Ngày tạo</th>
              <th class="text-left px-5 py-3 font-medium">Lý do</th>
              <th class="text-left px-5 py-3 font-medium">Minh chứng</th>
              <th class="text-right px-5 py-3 font-medium">Số tiền hoàn</th>
              <th class="text-left px-5 py-3 font-medium">Trạng thái</th>
              <th class="text-right px-5 py-3 font-medium w-[160px]">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in filteredReturns"
              :key="item.id"
              class="border-t border-slate-100 hover:bg-slate-50/60"
            >
              <td class="px-5 py-3">
                <a href="#" @click.prevent="openDetail(item)" class="text-[#0258cb] font-medium hover:underline">
                  {{ item.id }}
                </a>
              </td>
              <td class="px-5 py-3 text-slate-500">{{ item.createdAt }}</td>
              <td class="px-5 py-3 text-slate-700 max-w-[200px] truncate" :title="item.reason">{{ item.reason }}</td>
              <td class="px-5 py-3">
                <span v-if="item.images.length" class="inline-flex items-center gap-1 text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                  🖼 +{{ item.images.length }}
                </span>
                <span v-else class="text-xs text-slate-300">—</span>
              </td>
              <td class="px-5 py-3 text-right font-medium">{{ formatCurrency(item.refundAmount) }}</td>
              <td class="px-5 py-3">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="statusStyle(item.status)">
                  {{ statusLabel(item.status) }}
                </span>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-1 text-slate-400">
                  <button
                    @click="openDetail(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                    title="Xem chi tiết"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button
                    @click="openEdit(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150"
                    title="Chỉnh sửa"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button
                    v-if="item.status === 'Pending'"
                    @click="quickApprove(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 transition-all duration-150"
                    title="Duyệt yêu cầu"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                  </button>
                  <button
                    v-if="item.status === 'Pending'"
                    @click="quickReject(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all duration-150"
                    title="Từ chối yêu cầu"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Trạng thái rỗng khi lọc/tìm không có kết quả -->
            <tr v-if="filteredReturns.length === 0">
              <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                Không tìm thấy đơn trả hàng phù hợp.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
        <span>Hiển thị {{ filteredReturns.length }} / {{ returns.length }} kết quả</span>
        <div class="flex gap-1">
          <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">Trước</button>
          <button class="px-3 py-1 bg-[#0258cb] text-white rounded">1</button>
          <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">Sau</button>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 1: TẠO ĐƠN TRẢ HÀNG MỚI                                   -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showCreate" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeAll">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100">
              <div>
                <h2 class="font-semibold text-slate-900">Tạo đơn trả hàng mới</h2>
                <p class="text-sm text-slate-500">Khởi tạo một quy trình xử lý trả hàng mới</p>
              </div>
              <button @click="closeAll" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="text-xs font-medium text-slate-600">Mã đơn hàng</label>
                  <input v-model="createForm.orderId" type="text" placeholder="VD: ORD-2023-458"
                    class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30" />
                </div>
                <div>
                  <label class="text-xs font-medium text-slate-600">Số tiền hoàn yêu cầu</label>
                  <input v-model.number="createForm.refundAmount" type="number" placeholder="0"
                    class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30" />
                </div>
              </div>

              <div>
                <label class="text-xs font-medium text-slate-600">Trạng thái</label>
                <div class="mt-1">
                  <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">Pending</span>
                </div>
              </div>

              <div>
                <label class="text-xs font-medium text-slate-600">Lý do trả hàng</label>
                <textarea v-model="createForm.reason" rows="3" placeholder="Mô tả vấn đề với sản phẩm..."
                  class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30"></textarea>
              </div>

              <div>
                <label class="text-xs font-medium text-slate-600">Ảnh minh chứng</label>
                <div class="mt-1 border-2 border-dashed border-slate-200 rounded-lg py-8 text-center text-sm text-slate-400">
                  📤 <span class="text-blue-600 font-medium">Tải file lên</span> hoặc kéo thả<br/>
                  <span class="text-xs">PNG, JPG, GIF tối đa 10MB</span>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100">
              <button @click="closeAll" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Hủy</button>
              <button @click="submitCreate" class="px-4 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3]">Gửi yêu cầu ➤</button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- ============================================================ -->
    <!-- MODAL 2: CHI TIẾT ĐƠN TRẢ HÀNG                                  -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showDetail && selected" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeAll">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100">
              <div>
                <h2 class="font-semibold text-slate-900">Chi tiết đơn trả hàng</h2>
                <p class="text-sm text-slate-500">Mã yêu cầu: #{{ selected.requestId }}</p>
              </div>
              <button @click="closeAll" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">
              <div class="flex items-center justify-between bg-slate-50 rounded-lg px-4 py-3">
                <div class="text-sm">
                  <span class="text-slate-500 text-xs block">Đơn hàng liên quan</span>
                  <div class="text-[#0258cb] font-semibold">{{ selected.id }}</div>
                </div>
                <div class="text-right text-xs text-slate-500">
                  <span class="px-2 py-0.5 rounded-full text-xs font-semibold" :class="statusStyle(selected.status)">{{ statusLabel(selected.status) }}</span>
                  <div class="mt-1">Tạo lúc: {{ selected.createdAt }}</div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <div class="text-xs text-slate-500 mb-1">Thông tin khách hàng</div>
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs flex items-center justify-center font-semibold">
                      {{ selected.customer.name.charAt(0) }}
                    </div>
                    <div class="text-sm">
                      <div class="font-medium text-slate-800">{{ selected.customer.name }}</div>
                      <div class="text-xs text-slate-500">{{ selected.customer.email }}</div>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="text-xs text-slate-500 mb-1">Lý do trả hàng</div>
                  <div class="text-sm text-rose-700 bg-rose-50 rounded-lg px-3 py-2">
                    {{ selected.reason }}
                  </div>
                </div>
              </div>

              <div>
                <div class="text-xs text-slate-500 mb-1">Số tiền hoàn yêu cầu</div>
                <div class="text-lg font-bold text-slate-800">{{ formatCurrency(selected.refundAmount) }}</div>
              </div>

              <div v-if="selected.images.length">
                <div class="text-xs text-slate-500 mb-2">Ảnh minh chứng ({{ selected.images.length }})</div>
                <div class="grid grid-cols-3 gap-2">
                  <div v-for="(img, i) in selected.images" :key="i" class="aspect-square bg-slate-100 border border-slate-200 rounded-lg flex items-center justify-center text-2xl">
                    🖼️
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100">
              <button @click="closeAll" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Đóng</button>
              <template v-if="selected.status === 'Pending'">
                <button @click="reject(selected)" class="px-4 py-2 text-sm rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50">✕ Từ chối</button>
                <button @click="approve(selected)" class="px-4 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3]">✓ Duyệt hoàn tiền</button>
              </template>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- ============================================================ -->
    <!-- MODAL 3: CHỈNH SỬA ĐƠN TRẢ HÀNG                                 -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showEdit && editForm" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeAll">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100">
              <h2 class="font-semibold text-slate-900">Chỉnh sửa đơn trả hàng</h2>
              <button @click="closeAll" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="text-xs font-medium text-slate-500 uppercase">Mã đơn hàng</label>
                  <input v-model="editForm.id" disabled type="text"
                    class="mt-1 w-full text-sm border border-slate-200 bg-slate-50 text-slate-400 rounded-lg px-3 py-2" />
                </div>
                <div>
                  <label class="text-xs font-medium text-slate-500 uppercase">Trạng thái</label>
                  <select v-model="editForm.status" class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white">
                    <option value="Pending">Pending Review</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="text-xs font-medium text-slate-500 uppercase">Số tiền hoàn</label>
                <input v-model.number="editForm.refundAmount" type="number"
                  class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2" />
              </div>

              <div>
                <label class="text-xs font-medium text-slate-500 uppercase">Lý do trả hàng</label>
                <textarea v-model="editForm.reason" rows="4"
                  class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2"></textarea>
              </div>

              <div v-if="editForm.images.length">
                <div class="flex items-center justify-between mb-2">
                  <label class="text-xs font-medium text-slate-500 uppercase">Ảnh minh chứng</label>
                  <button class="text-xs text-[#0258cb] font-semibold hover:underline">+ Thêm ảnh</button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                  <div v-for="(img, i) in editForm.images" :key="i" class="aspect-square bg-slate-100 border border-slate-200 rounded-lg flex items-center justify-center text-2xl">
                    🖼️
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100">
              <button @click="closeAll" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Hủy</button>
              <button @click="submitEdit" class="px-4 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3]">Cập nhật yêu cầu</button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, reactive, h } from 'vue'

// Icons built using inline SVG objects or standard template-compatible structures
const IconReturns = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 15L4 10l5-5M4 10h11a4 4 0 010 8h-1' })
])
const IconPending = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' })
])
const IconApproved = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' })
])
const IconRefund = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M17 9V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h6M13 15h4M15 13v4' })
])

// ---------------------------------------------------------------
// 1. STATE (dữ liệu phản ứng - reactive state)
// ---------------------------------------------------------------

// Dữ liệu mẫu, đóng vai trò như "database" giả lập
const returns = ref([
  {
    id: '#ORD-2023-8901',
    requestId: 'RR-45092',
    createdAt: '24 Oct 2023',
    reason: 'Product arrived damaged',
    refundAmount: 1250000,
    status: 'Pending',
    images: ['a', 'b', 'c'],
    customer: { name: 'Jane Nguyen', email: 'jane.n@example.com' },
  },
  {
    id: '#ORD-2023-8895',
    requestId: 'RR-45081',
    createdAt: '23 Oct 2023',
    reason: 'Wrong size delivered.',
    refundAmount: 450000,
    status: 'Approved',
    images: ['a'],
    customer: { name: 'Minh Tran', email: 'minh.t@example.com' },
  },
  {
    id: '#ORD-2023-8941A',
    requestId: 'RR-45103',
    createdAt: '25 Oct 2023',
    reason: 'The item arrived with significant scratching on the back panel.',
    refundAmount: 124500,
    status: 'Pending',
    images: ['a', 'b'],
    customer: { name: 'Huy Pham', email: 'huy.pham@example.com' },
  },
])

const searchQuery = ref('')
const statusFilter = ref('all')

// computed: tự tính lại mỗi khi "returns" thay đổi
const stats = computed(() => {
  const total = returns.value.length
  const pending = returns.value.filter(r => r.status === 'Pending').length
  const approved = returns.value.filter(r => r.status === 'Approved').length
  const refunded = returns.value
    .filter(r => r.status === 'Approved')
    .reduce((sum, r) => sum + r.refundAmount, 0)

  return [
    { label: 'Total Returns', value: total, icon: IconReturns, iconColor: 'text-slate-400', trend: '12%', trendUp: true },
    { label: 'Pending', value: pending, icon: IconPending, iconColor: 'text-amber-500', trend: '4%', trendUp: false },
    { label: 'Approved', value: approved, icon: IconApproved, iconColor: 'text-emerald-500' },
    { label: 'Refunded Amount', value: formatCurrency(refunded), icon: IconRefund, iconColor: 'text-blue-500' },
  ]
})

// computed: danh sách sau khi áp dụng tìm kiếm + lọc trạng thái
const filteredReturns = computed(() => {
  return returns.value.filter(item => {
    const matchSearch = item.id.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = statusFilter.value === 'all' || item.status === statusFilter.value
    return matchSearch && matchStatus
  })
})

// State điều khiển việc hiện/ẩn 3 modal
const showCreate = ref(false)
const showDetail = ref(false)
const showEdit = ref(false)

const selected = ref(null)   // item đang xem chi tiết
const editForm = ref(null)   // bản sao item đang chỉnh sửa

const createForm = reactive({
  orderId: '',
  refundAmount: 0,
  reason: '',
})

// ---------------------------------------------------------------
// 2. METHODS (các hành vi / xử lý sự kiện)
// ---------------------------------------------------------------

function formatCurrency(v) {
  return new Intl.NumberFormat('vi-VN').format(v) + ' đ'
}

function statusLabel(status) {
  return { Pending: 'Pending', Approved: 'Approved', Rejected: 'Rejected' }[status] || status
}

function statusStyle(status) {
  return {
    Pending: 'bg-amber-100 text-amber-700',
    Approved: 'bg-emerald-100 text-emerald-700',
    Rejected: 'bg-rose-100 text-rose-700',
  }[status] || 'bg-slate-100 text-slate-600'
}

function closeAll() {
  showCreate.value = false
  showDetail.value = false
  showEdit.value = false
  selected.value = null
  editForm.value = null
}

function openCreate() {
  createForm.orderId = ''
  createForm.refundAmount = 0
  createForm.reason = ''
  showCreate.value = true
}

function submitCreate() {
  returns.value.unshift({
    id: '#' + (createForm.orderId || 'ORD-NEW'),
    requestId: 'RR-' + Math.floor(Math.random() * 90000 + 10000),
    createdAt: new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
    reason: createForm.reason || 'Chưa mô tả',
    refundAmount: createForm.refundAmount || 0,
    status: 'Pending',
    images: [],
    customer: { name: 'Khách hàng mới', email: 'n/a' },
  })
  closeAll()
}

function openDetail(item) {
  selected.value = item
  showDetail.value = true
}

function openEdit(item) {
  editForm.value = { ...item, images: [...item.images] }
  showEdit.value = true
}

function submitEdit() {
  const idx = returns.value.findIndex(r => r.requestId === editForm.value.requestId)
  if (idx !== -1) returns.value[idx] = { ...editForm.value }
  closeAll()
}

function approve(item) {
  item.status = 'Approved'
  closeAll()
}

function reject(item) {
  item.status = 'Rejected'
  closeAll()
}

function quickApprove(item) { item.status = 'Approved' }
function quickReject(item) { item.status = 'Rejected' }
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.18s ease; }
.pop-leave-active { transition: all 0.12s ease; }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: scale(0.96) translateY(8px); }
</style>