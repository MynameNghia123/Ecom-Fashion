<template>
  <div class="space-y-6">

    <!-- ===== Page Header ===== -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Thuộc tính</h1>
        <p class="text-sm text-slate-500 mt-0.5">Thiết lập và quản lý các thuộc tính sản phẩm trong hệ thống.</p>
      </div>
      <button
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm thuộc tính mới
      </button>
    </div>

    <!-- ===== Table Card ===== -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Search Toolbar -->
      <div class="p-5 border-b border-slate-100">
        <div class="relative flex items-center max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo tên..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
          />
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên thuộc tính</th>
              <th class="py-3.5 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr
              v-for="attr in paginatedAttributes"
              :key="attr.id"
              class="hover:bg-blue-50/30 transition-colors duration-100"
            >
              <td class="py-4 px-6 font-mono text-sm text-slate-500 font-medium">{{ attr.id }}</td>
              <td class="py-4 px-4 font-medium text-slate-800">{{ attr.name }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center justify-end gap-1">
                  <!-- Edit -->
                  <button
                    @click="openEditModal(attr)"
                    class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150"
                    title="Chỉnh sửa"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <!-- Delete -->
                  <button
                    @click="triggerDelete(attr)"
                    class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150"
                    title="Xóa"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty state -->
            <tr v-if="paginatedAttributes.length === 0">
              <td colspan="3" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy thuộc tính nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 flex-wrap gap-3">
        <p class="text-xs text-slate-500">
          Hiển thị
          <span class="font-semibold text-slate-700">{{ paginationFrom }}-{{ paginationTo }}</span>
          trong số
          <span class="font-semibold text-slate-700">{{ filteredAttributes.length }}</span>
          thuộc tính
        </p>
        <div class="flex items-center gap-1">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button
            v-for="page in displayedPages"
            :key="page"
            @click="page !== '...' && (currentPage = page)"
            :class="[
              'w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold transition-all',
              page === currentPage
                ? 'bg-[#0258cb] text-white border border-[#0258cb] shadow-sm'
                : page === '...'
                ? 'text-slate-400 cursor-default border border-transparent'
                : 'border border-slate-200 text-slate-600 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50'
            ]"
          >{{ page }}</button>
          <button
            @click="currentPage++"
            :disabled="currentPage === totalPages"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- ============================= ADD MODAL ============================= -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showAddModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showAddModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[440px] animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
              <h2 class="text-base font-bold text-slate-800">Thêm thuộc tính mới</h2>
              <button @click="showAddModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>
            <!-- Body -->
            <div class="px-6 py-5">
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Tên thuộc tính</label>
              <input
                v-model="addForm.name"
                ref="addInputRef"
                type="text"
                placeholder="Vd: Kích thước, Màu sắc..."
                @keyup.enter="submitAdd"
                class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                :class="addErrors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
              />
              <p v-if="addErrors.name" class="text-xs text-red-500 mt-1.5">{{ addErrors.name }}</p>
            </div>
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100">
              <button @click="showAddModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy</button>
              <button @click="submitAdd" class="px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all active:scale-[0.98]">
                Thêm thuộc tính
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ============================= EDIT MODAL ============================= -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showEditModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showEditModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[440px] animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
              <h2 class="text-base font-bold text-slate-800">Chỉnh sửa thuộc tính</h2>
              <button @click="showEditModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>
            <!-- Body -->
            <div class="px-6 py-5">
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Tên thuộc tính</label>
              <input
                v-model="editForm.name"
                ref="editInputRef"
                type="text"
                placeholder="Nhập tên thuộc tính..."
                @keyup.enter="submitEdit"
                class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                :class="editErrors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
              />
              <p v-if="editErrors.name" class="text-xs text-red-500 mt-1.5">{{ editErrors.name }}</p>
            </div>
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100">
              <button @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy</button>
              <button @click="submitEdit" class="px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all active:scale-[0.98]">
                Lưu thay đổi
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ============================= CONFIRM DELETE ============================= -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xác nhận xóa thuộc tính"
      message="Bạn có chắc chắn muốn xóa thuộc tính"
      :itemName="deleteTarget?.name"
      messageSuffix="không?"
      confirmLabel="Xác nhận xóa"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, reactive, nextTick, watch } from 'vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

// ======== Mock Data ========
const attributes = ref([
  { id: 101, name: 'Màu sắc' },
  { id: 102, name: 'Kích thước' },
  { id: 103, name: 'Chất liệu' },
  { id: 104, name: 'Thương hiệu' },
  { id: 105, name: 'Xuất xứ' },
  { id: 106, name: 'Trọng lượng' },
  { id: 107, name: 'Bảo hành' },
  { id: 108, name: 'Kiểu dáng' },
  { id: 109, name: 'Cổng kết nối' },
  { id: 110, name: 'Hệ điều hành' },
  { id: 111, name: 'RAM' },
  { id: 112, name: 'Dung lượng lưu trữ' },
])

// ======== Search ========
const searchQuery = ref('')

const filteredAttributes = computed(() =>
  attributes.value.filter(a =>
    !searchQuery.value || a.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

// ======== Pagination ========
const currentPage = ref(1)
const pageSize = 4

const totalPages = computed(() => Math.max(1, Math.ceil(filteredAttributes.value.length / pageSize)))
const paginatedAttributes = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredAttributes.value.slice(start, start + pageSize)
})
const paginationFrom = computed(() => Math.min((currentPage.value - 1) * pageSize + 1, filteredAttributes.value.length))
const paginationTo = computed(() => Math.min(currentPage.value * pageSize, filteredAttributes.value.length))

const displayedPages = computed(() => {
  const total = totalPages.value, cur = currentPage.value
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)
  const pages = [1]
  if (cur > 3) pages.push('...')
  for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i)
  if (cur < total - 2) pages.push('...')
  pages.push(total)
  return pages
})

watch(filteredAttributes, () => {
  if (currentPage.value > totalPages.value) currentPage.value = 1
})

// ======== Add Modal ========
const showAddModal = ref(false)
const addInputRef = ref(null)
const addForm = reactive({ name: '' })
const addErrors = reactive({ name: '' })

const openAddModal = () => {
  addForm.name = ''
  addErrors.name = ''
  showAddModal.value = true
  nextTick(() => addInputRef.value?.focus())
}

const submitAdd = () => {
  addErrors.name = ''
  if (!addForm.name.trim()) {
    addErrors.name = 'Tên thuộc tính không được để trống.'
    return
  }
  const maxId = attributes.value.length ? Math.max(...attributes.value.map(a => a.id)) : 100
  attributes.value.push({ id: maxId + 1, name: addForm.name.trim() })
  showAddModal.value = false
}

// ======== Edit Modal ========
const showEditModal = ref(false)
const editInputRef = ref(null)
const editForm = reactive({ id: null, name: '' })
const editErrors = reactive({ name: '' })

const openEditModal = (attr) => {
  editForm.id = attr.id
  editForm.name = attr.name
  editErrors.name = ''
  showEditModal.value = true
  nextTick(() => editInputRef.value?.focus())
}

const submitEdit = () => {
  editErrors.name = ''
  if (!editForm.name.trim()) {
    editErrors.name = 'Tên thuộc tính không được để trống.'
    return
  }
  const idx = attributes.value.findIndex(a => a.id === editForm.id)
  if (idx !== -1) attributes.value[idx].name = editForm.name.trim()
  showEditModal.value = false
}

// ======== Delete ========
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const triggerDelete = (attr) => {
  deleteTarget.value = attr
  showDeleteModal.value = true
}

const executeDelete = () => {
  attributes.value = attributes.value.filter(a => a.id !== deleteTarget.value.id)
  showDeleteModal.value = false
  deleteTarget.value = null
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.5, 0.64, 1) forwards;
}
</style>
