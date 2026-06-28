<template>
  <div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Nhà phân phối</h1>
      <p class="text-sm text-slate-500 mt-0.5">Quản lý toàn bộ nhà phân phối / nhà cung cấp trong hệ thống</p>
    </div>
    <button
      id="btn-open-add-supplier"
      class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      @click="show = true; typeOfAction = 'add'"
    >
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Thêm nhà phân phối mới
    </button>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng nhà phân phối</p>
        <p class="text-3xl font-bold text-slate-800">150</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
          <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
        </svg>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hợp tác</p>
        <p class="text-3xl font-bold text-slate-800">120</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tạm dừng</p>
        <p class="text-3xl font-bold text-slate-800">30</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
      <p class="text-base font-bold text-slate-800 mr-2">Danh sách nhà phân phối</p>
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-lg border border-emerald-100">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
        ACTIVE NOW
      </span>
      <div class="flex-1"></div>
      <div class="relative flex items-center min-w-[220px] max-w-xs">
        <span class="absolute left-3.5 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </span>
        <input
          id="search-supplier"
          type="text"
          placeholder="Tìm tên, SĐT, email..."
          class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        />
      </div>
      <select
        class="px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
      >
        <option value="">Tất cả trạng thái</option>
        <option value="active">Đang hợp tác</option>
        <option value="paused">Tạm dừng</option>
      </select>
      <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Xuất file
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100">
            <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">ID</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên nhà phân phối</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Liên hệ (SĐT / Email)</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Địa chỉ</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Trạng thái</th>
            <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-for="supplier in supplierStore.suppliers" 
              :key="supplier.id"
           class="hover:bg-blue-50/40 transition-colors duration-100 group">
            <td class="py-4 px-5">
              <div class="w-4 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-black shrink-0">
                {{ supplier.id }}
              </div>
            </td>
            <td class="py-4 px-4">
              <p class="font-semibold text-slate-800">{{ supplier.name }}</p>
            </td>
            <td class="py-4 px-4">
              <p class="text-slate-700">{{ supplier.phone }}</p>
              <p class="text-xs text-slate-400 mt-0.5">{{ supplier.email }}</p>
            </td>
            <td class="py-4 px-4 text-slate-500 max-w-[220px]">
              <span class="line-clamp-1 text-sm">{{ supplier.address }}</span>
            </td>
            <td class="py-4 px-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Đang hợp tác
              </span>
            </td>
            <td class="py-4 px-4">
              <div class="flex items-center justify-end gap-1">
                <button class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
                <button 
                @click="show = true; typeOfAction='update'; selectedSupplier = supplier"
                class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <button 
                  @click="showDeleteModal = true; selectedSupplier = supplier"
                  class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150" title="Xóa">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
      <p class="text-xs text-slate-500">
        Hiển thị 10 / 150 nhà phân phối
      </p>
      <div class="flex items-center gap-1">
        <button class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150 bg-[#0258cb] text-white shadow-sm">1</button>
        <button class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150 text-slate-500 hover:bg-slate-100">2</button>
        <button class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150 text-slate-500 hover:bg-slate-100">3</button>
        <span class="w-8 h-8 flex items-center justify-center text-slate-400 text-sm">...</span>
        <button class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150 text-slate-500 hover:bg-slate-100">15</button>
      </div>
    </div>
  </div>


  <SupplierForm
  :show="show"
  :typeOfAction="typeOfAction"
  :selectedSupplier="selectedSupplier"
  @cancel="onCancel"
  @submit="onSubmit"
  />

  <ConfirmDeleteModal
    v-model:show="showDeleteModal"
    title="Xác nhận xóa nhà phân phối"
    message="Bạn có chắc chắn muốn xóa nhà phân phối"
    :itemName="selectedSupplier ? selectedSupplier.name : ''"
    @confirm="deleteSupplier"
    @cancel="showDeleteModal = false"
  />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue';
// import SupplierView from '@/components/admin/SupplierView.vue'
import SupplierForm from '@/components/admin/supplier/SupplierForm.vue'
import { useSupplierStore } from '@/stores/admin/supplierStore.js'

const supplierStore = useSupplierStore()



onMounted(async () => {
  await supplierStore.initialFetch()
  console.log('Suppliers fetched:', supplierStore.suppliers)
})
//update && add
const show = ref(false)
const typeOfAction = ref('');

const onCancel = () => {
  show.value = false
  selectedSupplier.value = null
}

const onSubmit = async (formData) => {
  if (typeOfAction.value === 'add') {
    await supplierStore.createSupplier(formData)
  } else if (typeOfAction.value === 'update' && selectedSupplier.value) {
    await supplierStore.updateSupplier(selectedSupplier.value.id, formData)
  }
  show.value = false
  selectedSupplier.value = null
}
// delete
const showDeleteModal = ref(false)
const selectedSupplier = ref(null)

const deleteSupplier = async () => {
  if (!selectedSupplier.value) return
  try {
    await supplierStore.deleteSupplier(selectedSupplier.value.id)
    showDeleteModal.value = false
    selectedSupplier.value = null
  } catch (error) {
    console.error('Error deleting supplier:', error)
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
