<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Danh mục</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý toàn bộ danh mục sản phẩm trong hệ thống</p>
      </div>
      <button
        id="btn-open-add-category"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm danh mục mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Total -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng danh mục</p>
          <p class="text-3xl font-bold text-slate-800">{{ categoryStore.stats.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
      </div>
      <!-- Parent -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Danh mục cha</p>
          <p class="text-3xl font-bold text-slate-800">{{ categoryStore.stats.parent }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 10h16"/><path d="M9 14h6"/>
          </svg>
        </div>
      </div>
      <!-- Child -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Danh mục con</p>
          <p class="text-3xl font-bold text-slate-800">{{ categoryStore.stats.child }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="categoryStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ categoryStore.error }}
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <!-- Search -->
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-xs">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            id="search-category"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm theo tên hoặc slug..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
      </div>

      <!-- Table -->

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[70px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên danh mục</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Danh mục cha</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mô tả</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <!-- <tr v-if="categoryStore.loading" v-for="i in perPage" :key="'sk-'+i">
              <td colspan="6" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr> -->
            <template v-if="categoryStore.loading">
              <tr v-for="i in categoryStore.meta.per_page" :key="i" class="animate-pulse">
                <td class="py-4 px-6"><div class="h-4 bg-slate-200 rounded w-12"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-40"></div></td>
                <td class="py-4 px-6">
                  <div class="flex justify-end gap-2">
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                  </div>
                </td>
              </tr>
            </template>
            <!-- Rows -->
             <template v-else>
                <tr
                  v-for="cat in categoryStore.categories"
                  :key="cat.id"
                  class="hover:bg-blue-50/40 transition-colors duration-100 group"
                >
                  <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ cat.id }}</td>
                  <td class="py-4 px-4 font-semibold text-slate-800">{{ cat.name }}</td>
                  <td class="py-4 px-4">
                    <span class="inline-block bg-slate-100 text-slate-600 text-xs font-mono px-2.5 py-1 rounded-lg max-w-[160px] truncate">{{ cat.slug }}</span>
                  </td>
                  <td class="py-4 px-4">
                    <span v-if="cat.parent_id" class="inline-block bg-blue-50 text-[#0258cb] text-xs font-semibold px-2.5 py-1 rounded-lg max-w-[110px] truncate">
                      {{ getCategoryName(cat.parent_id) }}
                    </span>
                    <span v-else class="text-slate-400 text-sm font-medium">—</span>
                  </td>
                  <td class="py-4 px-4 text-slate-500 max-w-[240px]">
                    <span class="line-clamp-1 text-xs">{{ cat.description || '—' }}</span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center justify-end gap-1">
                      <!-- View -->
                      <button
                        @click="openViewModal(cat)"
                        class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                        title="Xem chi tiết"
                      >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                      </button>
                      <!-- Edit -->
                      <button
                        @click="openEditModal(cat)"
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
                        @click="confirmDelete(cat)"
                        class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150"
                        title="Xóa"
                      >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                          <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
             </template>

            <!-- Empty state -->
            <tr v-if="!categoryStore.loading && categoryStore.categories.length === 0">
              <td colspan="6" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    <line x1="9" y1="13" x2="15" y2="13"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy danh mục nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="categoryStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="categoryStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="categoryStore.meta.total"
          :lastPage="categoryStore.meta.last_page"
          :loading="categoryStore.loading"
        />
      </div>
    </div>

    <!-- ========== ADD / EDIT MODAL ========== -->
    <CategoryFormModal
      :show="showFormModal"
      :mode="modalMode"
      :category="editTarget"
      :filter-parents="filterParents"
      @saved="onSaved"
      @cancel="showFormModal = false"
    />

    <!-- ========== VIEW DETAIL MODAL ========== -->
    <CategoryViewModal
      :show="showViewModal"
      :category="viewTarget"
      :get-category-name="getCategoryName"
      @close="showViewModal = false"
      @edit="onViewModalEdit"
    />

    <!-- ========== CONFIRM DELETE MODAL ========== -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa danh mục"
      message="Bạn có chắc chắn muốn xóa danh mục"
      :itemName="deleteTarget?.name"
      messageSuffix="không? Các danh mục con (nếu có) sẽ bị ảnh hưởng."
      confirmLabel="Xóa danh mục"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCategoryStore } from '@/stores/admin/categoryStore'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'
import { categoryService } from '@/services/admin/categoryService'
import CategoryFormModal from '@/components/admin/category/CategoryFormModal.vue'
import CategoryViewModal from '@/components/admin/category/CategoryViewModal.vue'

const categoryStore = useCategoryStore()

// ─── Load dữ liệu khi mount ──────────────────────────────────────────────────
onMounted(() => {
  categoryStore.initialFetch()   // chỉ fetch nếu chưa có data
  fetchFilterParents()
})



const getCategoryName = (id) => {
  let cat = categoryStore.categories.find(c => c.id === id)
  if (cat) return cat.name
  cat = filterParents.value.find(c => c.id === id)
  return cat ? cat.name : `#${id}`
}

// ─── Search & Pagination ─────────────────────────────────────────────────────
const searchQuery = ref('')
const filterParents = ref([])
let searchTimer = null

const fetchFilterParents = async () => {
  try {
    const res = await categoryService.getParents()
    filterParents.value = res.data.data
  } catch (e) {
    console.error('Không thể tải danh sách danh mục cha:', e)
  }
}

const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    categoryStore.fetchCategories({ search: searchQuery.value, page: 1 })
  }, 400)
}

const goToPage = (page) => {
  if (page < 1 || page > categoryStore.meta.last_page) return
  categoryStore.fetchCategories({ search: searchQuery.value, page })
}

const handlePerPageChange = (newPerPage) => {
  categoryStore.meta.per_page = newPerPage
  categoryStore.fetchCategories({ search: searchQuery.value, page: 1 })
}

// ─── Form Modal (Add / Edit) — delegated to CategoryFormModal ────────────────
const showFormModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const editTarget = ref(null)

const openAddModal = () => {
  modalMode.value  = 'add'
  editTarget.value = null
  showFormModal.value = true
}

const openEditModal = (cat) => {
  modalMode.value  = 'edit'
  editTarget.value = cat
  showFormModal.value = true
}

const onSaved = () => {
  fetchFilterParents()
  showFormModal.value = false
}

// ─── View Modal ──────────────────────────────────────────────────────────────
const showViewModal = ref(false)
const viewTarget = ref(null)
const openViewModal = (cat) => { viewTarget.value = cat; showViewModal.value = true }

// Khi bấm "Chỉnh sửa" từ bên trong CategoryViewModal
const onViewModalEdit = (cat) => {
  showViewModal.value = false
  openEditModal(cat)
}

// ─── Delete Modal ─────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (cat) => {
  deleteTarget.value = cat
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await categoryStore.deleteCategory(deleteTarget.value.id)
    fetchFilterParents()
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
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
