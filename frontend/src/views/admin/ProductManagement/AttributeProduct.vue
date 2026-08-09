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
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-semibold rounded-xl shadow-md transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm thuộc tính mới
      </button>
    </div>

    <!-- ===== Error Alert ===== -->
    <Transition name="fade">
      <div v-if="store.error" class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
        <svg class="w-5 h-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>{{ store.error }}</span>
        <button @click="store.error = null" class="ml-auto text-red-400 hover:text-red-600 transition-colors">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </Transition>

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
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all"
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

            <!-- Loading Skeleton -->
            <template v-if="store.loading">
              <tr v-for="i in store.meta.per_page" :key="i" class="animate-pulse">
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

            <!-- Data Rows -->
            <template v-else>
              <tr
                v-for="attr in store.attributes"
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
              <tr v-if="store.attributes.length === 0">
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
            </template>

          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <Pagination
        :current-page="store.meta.current_page"
        :per-page="store.meta.per_page"
        @update:per-page="handlePerPageChange"
        :total="store.meta.total"
        :last-page="store.meta.last_page"
        :loading="store.loading"
        @update:current-page="handlePageChange"
        class="px-6 py-4 border-t border-slate-100"
      />
    </div>

    <!-- ============================= FORM MODAL ============================= -->
    <AttributeFormModal
      :show="showFormModal"
      :mode="formMode"
      :attribute="editTarget"
      @saved="onSaved"
      @cancel="showFormModal = false"
    />

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
import { ref, watch, onMounted } from 'vue'
import { useAttributeStore } from '@/stores/admin/attributeStore'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'
import AttributeFormModal from '@/components/admin/attribute/AttributeFormModal.vue'

const store = useAttributeStore()

// ======== Search (debounced) ========
const searchQuery = ref('')
let searchTimer = null

watch(searchQuery, (val) => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    store.fetchAttributes({ search: val, page: 1 })
  }, 400)
})

// ======== Pagination ========
const handlePageChange = (newPage) => {
  store.fetchAttributes({ search: searchQuery.value, page: newPage })
}

const handlePerPageChange = (newPerPage) => {
  store.meta.per_page = newPerPage
  store.fetchAttributes({ search: searchQuery.value, page: 1 })
}

// ======== Mount ========
onMounted(() => {
  store.initialFetch()
})

// ======== Form Modal (Add / Edit) ========
const showFormModal = ref(false)
const formMode = ref('add')      // 'add' | 'edit'
const editTarget = ref(null)

const openAddModal = () => {
  formMode.value  = 'add'
  editTarget.value = null
  showFormModal.value = true
}

const openEditModal = (attr) => {
  formMode.value   = 'edit'
  editTarget.value  = attr
  showFormModal.value = true
}

const onSaved = () => {
  showFormModal.value = false
}

// ======== Delete ========
const isSubmitting = ref(false)
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const triggerDelete = (attr) => {
  deleteTarget.value = attr
  showDeleteModal.value = true
}

const executeDelete = async () => {
  isSubmitting.value = true
  try {
    await store.deleteAttribute(deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
    store.error = e.response?.data?.message || 'Không thể xóa thuộc tính này.'
    showDeleteModal.value = false
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

.fade-enter-active,
.fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.5, 0.64, 1) forwards;
}
</style>
