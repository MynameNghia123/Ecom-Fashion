<template>
  <div class="space-y-6">

    <!-- ══════════════════════ PAGE HEADER ══════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Phiếu nhập kho</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage and track your goods receipts</p>
      </div>
      <button
        id="btn-open-add-receipt"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm phiếu nhập mới
      </button>
    </div>

    <!-- ══════════════════════ STATS CARDS ══════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Total receipts -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng phiếu nhập</p>
          <p class="text-3xl font-bold text-slate-800">{{ totalReceipts.toLocaleString('vi-VN') }}</p>
          <p class="text-xs text-emerald-500 font-semibold mt-1">↑ 12% vs last month</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
          </svg>
        </div>
      </div>
      <!-- Total import value -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng giá trị nhập</p>
          <p class="text-3xl font-bold text-slate-800">₫4.2B</p>
          <p class="text-xs text-emerald-500 font-semibold mt-1">↑ 8.4% vs last month</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </div>
      </div>
      <!-- Pending -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Phiếu chờ xử lý</p>
          <p class="text-3xl font-bold text-slate-800">{{ pendingCount }}</p>
          <p class="text-xs text-amber-500 font-semibold mt-1">Requires immediate attention</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- ══════════════════════ ERROR BANNER ══════════════════════ -->
    <div v-if="globalError" class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ globalError }}
    </div>

    <!-- ══════════════════════ TABLE CARD ══════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <!-- Search -->
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            id="search-receipt"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Search receipts..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <div class="flex-1"></div>
        <!-- Filter Status -->
        <select
          v-model="filterStatus"
          @change="onSearch"
          class="px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        >
          <option value="">Tất cả trạng thái</option>
          <option value="pending">Chờ duyệt</option>
          <option value="approved">Đã duyệt</option>
          <option value="completed">Đã hoàn thành</option>
          <option value="cancelled">Đã huỷ</option>
        </select>
        <!-- Filter button -->
        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
          </svg>
        </button>
        <!-- Export -->
        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mã phiếu</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nhà cung cấp</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nhân viên</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng tiền</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày nhập</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <template v-if="loading">
              <tr v-for="i in 5" :key="'sk-'+i" class="animate-pulse">
                <td class="py-4 px-5"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-36"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-24 ml-auto"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
                <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-24"></div></td>
                <td class="py-4 px-4"><div class="flex justify-end gap-2"><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div></div></td>
              </tr>
            </template>

            <!-- Rows -->
            <template v-else>
              <tr
                v-for="rec in filteredReceipts"
                :key="rec.id"
                class="hover:bg-blue-50/40 transition-colors duration-100 group"
              >
                <td class="py-4 px-5">
                  <span class="font-mono text-sm font-semibold text-slate-700">{{ rec.code }}</span>
                </td>
                <td class="py-4 px-4 text-slate-700 font-medium">{{ rec.supplier_name }}</td>
                <td class="py-4 px-4 text-slate-500">{{ rec.staff_name }}</td>
                <td class="py-4 px-4 text-right font-semibold text-slate-800">{{ formatPrice(rec.total) }}</td>
                <td class="py-4 px-4 text-slate-500 text-xs">{{ rec.import_date }}</td>
                <td class="py-4 px-4">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="statusClass(rec.status)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(rec.status)"></span>
                    {{ statusLabel(rec.status) }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <button @click="openViewModal(rec)" class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                    <button @click="openEditModal(rec)" class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa" :disabled="rec.status === 'completed' || rec.status === 'cancelled'">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button @click="confirmDelete(rec)" class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150" title="Xóa">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </template>

            <!-- Empty -->
            <tr v-if="!loading && filteredReceipts.length === 0">
              <td colspan="7" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy phiếu nhập nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
        <p class="text-xs text-slate-500">
          Showing {{ filteredReceipts.length > 0 ? '1' : '0' }} to {{ filteredReceipts.length }} of {{ totalReceipts }} entries
        </p>
        <div class="flex items-center gap-1">
          <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 flex items-center justify-center transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150"
            :class="currentPage === page ? 'bg-[#0258cb] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100'"
          >{{ page }}</button>
          <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 flex items-center justify-center transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- ══════════════════════ ADD MODAL ══════════════════════ -->


    <!-- ══════════════════════ EDIT MODAL ══════════════════════ -->


    <!-- ══════════════════════ VIEW DETAIL MODAL ══════════════════════ -->

    <!-- ══════════════════════ CONFIRM DELETE MODAL ══════════════════════ -->


  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

// ─── Mock suppliers ───────────────────────────────────────────────────────────
const mockSuppliers = [
  { id: 1, name: 'Công ty TNHH Công Nghệ XYZ' },
  { id: 2, name: 'Công ty TNHH Vận tải & Thương mại ABC' },
  { id: 3, name: 'TechSource VN' },
  { id: 4, name: 'Green Logistics JSC' },
]

// ─── Mock products for search ─────────────────────────────────────────────────
const mockProducts = [
  { id: 1, name: 'Nike Air Zoom Pegasus - Đỏ, 42', sku: 'NKE-P38-001', default_price: 2500000 },
  { id: 2, name: 'Nike Air Force 1 - Trắng, 40', sku: 'NKE-AF1-002', default_price: 1800000 },
  { id: 3, name: 'Intel Core i7-13700K', sku: 'SP-CPU-001', default_price: 9500000 },
  { id: 4, name: 'ASUS ROG Strix Z790-A', sku: 'SP-MB-042', default_price: 8200000 },
  { id: 5, name: 'Corsair Vengeance RGB 32GB', sku: 'SP-RAM-018', default_price: 3100000 },
  { id: 6, name: 'Samsung 990 PRO 2TB', sku: 'SP-SSD-089', default_price: 4500000 },
  { id: 7, name: 'Smartwatch Series X Pro - Màu đen / 44mm', sku: 'SW-XP-44-BK', default_price: 2500000 },
  { id: 8, name: 'Tai nghe Bluetooth Studio ANC - Đen nhám', sku: 'TG-ANC-BK', default_price: 1200000 },
]

// ─── Mock receipts ────────────────────────────────────────────────────────────
const mockReceipts = [
  {
    id: 1, code: 'PNK-2023-10045', supplier_id: 1, supplier_name: 'Công ty TNHH Công Nghệ XYZ',
    staff_name: 'Nguyễn Tuấn Anh', delivery_person: 'Trần Văn Bình (0909 123 456)',
    import_date: '24/10/2023 14:30', status: 'completed', vat_rate: 8,
    extra_cost: 0, note: '',
    total: 333720000,
    items: [
      { sku: 'SP-CPU-001', product_name: 'Intel Core i7-13700K', variant: 'CPU Thế hệ 13', qty: 15, import_price: 9500000 },
      { sku: 'SP-MB-042', product_name: 'ASUS ROG Strix Z790-A', variant: 'Mainboard ATX', qty: 10, import_price: 8200000 },
      { sku: 'SP-RAM-018', product_name: 'Corsair Vengeance RGB 32GB', variant: 'DDR5 6000MHz (2×16GB)', qty: 20, import_price: 3100000 },
      { sku: 'SP-SSD-089', product_name: 'Samsung 990 PRO 2TB', variant: 'SSD M.2 NVMe PCIe Gen 4', qty: 5, import_price: 4500000 },
    ],
  },
  {
    id: 2, code: 'PN-202310-001', supplier_id: 3, supplier_name: 'TechSource VN',
    staff_name: 'Nguyen Van A', delivery_person: '',
    import_date: '15 Oct 2023', status: 'approved', vat_rate: 0,
    extra_cost: 0, note: 'Ưu tiên nhập hàng sớm.',
    total: 125000000,
    items: [
      { sku: 'SW-XP-44-BK', product_name: 'Smartwatch Series X Pro', variant: 'Màu đen / 44mm', qty: 50, import_price: 2500000 },
    ],
  },
  {
    id: 3, code: 'PN-202310-002', supplier_id: 3, supplier_name: 'TechSource VN',
    staff_name: 'Nguyen Van A', delivery_person: '',
    import_date: '15 Oct 2023', status: 'pending', vat_rate: 0,
    extra_cost: 0, note: '',
    total: 125000000,
    items: [
      { sku: 'TG-ANC-BK', product_name: 'Tai nghe Bluetooth Studio ANC', variant: 'Đen nhám', qty: 120, import_price: 1200000 },
    ],
  },
  {
    id: 4, code: 'PN-202310-003', supplier_id: 3, supplier_name: 'TechSource VN',
    staff_name: 'Nguyen Van A', delivery_person: '',
    import_date: '15 Oct 2023', status: 'pending', vat_rate: 0,
    extra_cost: 0, note: '',
    total: 125000000,
    items: [],
  },
  {
    id: 5, code: 'PN-202310-004', supplier_id: 3, supplier_name: 'TechSource VN',
    staff_name: 'Nguyen Van A', delivery_person: '',
    import_date: '15 Oct 2023', status: 'cancelled', vat_rate: 0,
    extra_cost: 0, note: '',
    total: 125000000,
    items: [],
  },
]

// ─── State ────────────────────────────────────────────────────────────────────
const receipts = ref([])
const loading = ref(false)
const globalError = ref('')
const searchQuery = ref('')
const filterStatus = ref('')
const currentPage = ref(1)

// ─── Computed ─────────────────────────────────────────────────────────────────
const filteredReceipts = computed(() => {
  let list = receipts.value
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(r =>
      r.code.toLowerCase().includes(q) ||
      r.supplier_name.toLowerCase().includes(q) ||
      r.staff_name.toLowerCase().includes(q)
    )
  }
  if (filterStatus.value) list = list.filter(r => r.status === filterStatus.value)
  return list
})

const totalReceipts = computed(() => receipts.value.length)
const pendingCount = computed(() => receipts.value.filter(r => r.status === 'pending').length)
const totalPages = computed(() => Math.max(1, Math.ceil(filteredReceipts.value.length / 10)))

// ─── Helpers ──────────────────────────────────────────────────────────────────
const formatPrice = (val) =>
  val ? new Intl.NumberFormat('vi-VN').format(val) + ' đ' : '0 đ'

const statusLabel = (s) => ({
  pending: 'Chờ duyệt',
  approved: 'Đã duyệt',
  completed: 'Đã hoàn thành',
  cancelled: 'Đã huỷ',
}[s] ?? s)

const statusClass = (s) => ({
  pending:   'bg-amber-50 text-amber-600 border border-amber-100',
  approved:  'bg-blue-50 text-blue-600 border border-blue-100',
  completed: 'bg-emerald-50 text-emerald-600 border border-emerald-100',
  cancelled: 'bg-slate-100 text-slate-500 border border-slate-200',
}[s] ?? 'bg-slate-100 text-slate-500')

const statusDotClass = (s) => ({
  pending:   'bg-amber-500',
  approved:  'bg-blue-500',
  completed: 'bg-emerald-500',
  cancelled: 'bg-slate-400',
}[s] ?? 'bg-slate-400')

const currentDateTime = computed(() => {
  const d = new Date()
  return `${d.getHours()}:${String(d.getMinutes()).padStart(2,'0')} ${d.getDate()}/${d.getMonth()+1}/${d.getFullYear()}`
})

const autoReceiptCode = computed(() => {
  const d = new Date()
  return `PN-${d.getFullYear()}${String(d.getMonth()+1).padStart(2,'0')}-${String(receipts.value.length + 1).padStart(3,'0')}`
})

// ─── Product row helpers ──────────────────────────────────────────────────────
const newProductRow = () => reactive({ product_name: '', sku: '', variant: '', qty: 1, import_price: 0, showDropdown: false, searchResults: [] })
const addProductRow = (form) => form.items.push(newProductRow())
const removeProductRow = (form, idx) => form.items.splice(idx, 1)

const onProductSearch = (item, query) => {
  if (!query.trim()) { item.searchResults = []; return }
  const q = query.toLowerCase()
  item.searchResults = mockProducts.filter(p =>
    p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q)
  ).slice(0, 6)
}

const selectProduct = (item, prod) => {
  item.product_name = prod.name
  item.sku = prod.sku
  item.import_price = prod.default_price
  item.showDropdown = false
  item.searchResults = []
}

const hideDropdown = (item) => {
  setTimeout(() => { item.showDropdown = false }, 150)
}

// ─── Search ───────────────────────────────────────────────────────────────────
let searchTimer = null
const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { currentPage.value = 1 }, 300)
}

// ─── Load data ────────────────────────────────────────────────────────────────
onMounted(() => {
  loading.value = true
  setTimeout(() => { receipts.value = mockReceipts; loading.value = false }, 600)
})

// ─── ADD MODAL ────────────────────────────────────────────────────────────────
const showAddModal = ref(false)
const addSubmitting = ref(false)
const addFormErrors = reactive({})

const addForm = reactive({
  supplier_id: '',
  delivery_person: '',
  note: '',
  extra_cost: 0,
  items: [],
})

const addSubtotal = computed(() =>
  addForm.items.reduce((s, i) => s + (i.qty || 0) * (i.import_price || 0), 0)
)

const resetAddForm = () => {
  addForm.supplier_id = ''
  addForm.delivery_person = ''
  addForm.note = ''
  addForm.extra_cost = 0
  addForm.items = []
  Object.keys(addFormErrors).forEach(k => delete addFormErrors[k])
}

const openAddModal = () => { resetAddForm(); showAddModal.value = true }

const submitAdd = async () => {
  Object.keys(addFormErrors).forEach(k => delete addFormErrors[k])
  if (!addForm.supplier_id) { addFormErrors.supplier_id = 'Vui lòng chọn nhà cung cấp.'; return }

  addSubmitting.value = true
  try {
    await new Promise(r => setTimeout(r, 800))
    const sup = mockSuppliers.find(s => s.id === addForm.supplier_id)
    const total = addSubtotal.value + (addForm.extra_cost || 0)
    const newRec = {
      id: Date.now(),
      code: autoReceiptCode.value,
      supplier_id: addForm.supplier_id,
      supplier_name: sup?.name ?? '',
      staff_name: 'Nguyễn Tuấn Anh',
      delivery_person: addForm.delivery_person,
      import_date: currentDateTime.value,
      status: 'pending',
      vat_rate: 0,
      extra_cost: addForm.extra_cost || 0,
      note: addForm.note,
      total,
      items: addForm.items.map(i => ({ ...i })),
    }
    receipts.value.unshift(newRec)
    showAddModal.value = false
  } finally {
    addSubmitting.value = false
  }
}

// ─── EDIT MODAL ───────────────────────────────────────────────────────────────
const showEditModal = ref(false)
const editSubmitting = ref(false)

const editForm = reactive({
  id: null, code: '', supplier_id: '', status: 'pending',
  extra_cost: 0, items: [],
})

const editSubtotal = computed(() =>
  editForm.items.reduce((s, i) => s + (i.qty || 0) * (i.import_price || 0), 0)
)

const openEditModal = (rec) => {
  editForm.id = rec.id
  editForm.code = rec.code
  editForm.supplier_id = rec.supplier_id
  editForm.status = rec.status
  editForm.extra_cost = rec.extra_cost || 0
  editForm.items = rec.items.map(i => reactive({ ...i, showDropdown: false, searchResults: [] }))
  showEditModal.value = true
}

const submitEdit = async () => {
  editSubmitting.value = true
  try {
    await new Promise(r => setTimeout(r, 800))
    const idx = receipts.value.findIndex(r => r.id === editForm.id)
    if (idx !== -1) {
      const sup = mockSuppliers.find(s => s.id === editForm.supplier_id)
      receipts.value[idx] = {
        ...receipts.value[idx],
        supplier_id: editForm.supplier_id,
        supplier_name: sup?.name ?? receipts.value[idx].supplier_name,
        status: editForm.status,
        extra_cost: editForm.extra_cost || 0,
        total: editSubtotal.value + (editForm.extra_cost || 0),
        items: editForm.items.map(i => ({ ...i })),
      }
    }
    showEditModal.value = false
  } finally {
    editSubmitting.value = false
  }
}

// ─── VIEW MODAL ───────────────────────────────────────────────────────────────
const showViewModal = ref(false)
const viewTarget = ref(null)

const viewSubtotal = computed(() =>
  (viewTarget.value?.items ?? []).reduce((s, i) => s + (i.qty || 0) * (i.import_price || 0), 0)
)

const openViewModal = (rec) => { viewTarget.value = rec; showViewModal.value = true }

const printReceipt = () => window.print()

// ─── DELETE MODAL ─────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (rec) => { deleteTarget.value = rec; showDeleteModal.value = true }

const executeDelete = async () => {
  try {
    await new Promise(r => setTimeout(r, 400))
    receipts.value = receipts.value.filter(r => r.id !== deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
    globalError.value = 'Xóa phiếu thất bại.'
    showDeleteModal.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
