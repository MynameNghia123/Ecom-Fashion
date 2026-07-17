<template>
  <div class="space-y-6">

    <!-- ═══ PAGE HEADER ═══════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Vai trò & Quyền hạn</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý các nhóm quyền truy cập vào hệ thống.</p>
      </div>
      <button
        @click="openAdd"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm vai trò mới
      </button>
    </div>

    <!-- ═══ TABLE CARD ════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            v-model="searchQuery"
            @keyup.enter="handleSearch"
            type="text"
            placeholder="Tìm kiếm vai trò..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
          </svg>
          Lọc
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-100">
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[80px]">ID</th>
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tên vai trò</th>
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mô tả</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[100px]">Số NV</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[140px]">Ngày tạo</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[140px]">Thao tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="loading" class="bg-white">
              <td colspan="6" class="py-8 text-center text-slate-500 text-sm">Đang tải dữ liệu...</td>
            </tr>
            <tr v-else-if="roles.length === 0" class="bg-white">
              <td colspan="6" class="py-8 text-center text-slate-500 text-sm">Không tìm thấy vai trò nào.</td>
            </tr>
            <tr v-else v-for="role in roles" :key="role.id" class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-6 font-mono text-xs text-slate-400">#{{ role.id }}</td>
              <td class="py-4 px-6">
                <span class="font-semibold text-slate-700">{{ role.name }}</span>
              </td>
              <td class="py-4 px-6 text-slate-500 text-xs max-w-[200px] truncate">{{ role.description }}</td>
              <td class="py-4 px-6 text-center font-bold text-slate-700">{{ role.staffCount || 0 }}</td>
              <td class="py-4 px-6 text-center text-xs text-slate-500">{{ role.created_at ? new Date(role.created_at).toLocaleDateString('vi-VN') : '' }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center justify-center gap-1.5">
                  <button @click="openView(role)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button @click="openEdit(role)" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button @click="openDelete(role)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="pagination.current_page"
          :perPage="pagination.per_page"
          :total="pagination.total"
          :lastPage="pagination.last_page"
          :loading="loading"
          @update:currentPage="goToPage"
          @update:perPage="handlePerPageChange"
        />
      </div>
    </div>

    <!-- ═══ MODAL COMPONENTS ══════════════════════════════════════════════════ -->
    <RoleForm
      :show="modal === 'form'"
      :isAdding="isAdding"
      :initialData="selectedRole"
      :permissionMatrix="permissionMatrix"
      @close="closeModal"
      @save="handleSave"
    />

    <RoleView
      :show="modal === 'view'"
      :data="selectedRole"
      @close="closeModal"
      @edit="openEdit"
    />

    <ConfirmDeleteModal
      :show="modal === 'delete'"
      title="Xóa vai trò?"
      message="Bạn có chắc chắn muốn xóa vai trò"
      :itemName="selectedRole?.name"
      @cancel="closeModal"
      @confirm="handleDelete(selectedRole)"
    />

  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import RoleForm from '@/components/admin/role/RoleForm.vue'
import RoleView from '@/components/admin/role/RoleView.vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'
import { permissionService } from '@/services/admin/permissionService'
import { roleService } from '@/services/admin/roleService'

// ─── State ─────────────────────────────────────
const roles = ref([])
const loading = ref(false)
const searchQuery = ref('')
const pagination = ref({
  current_page: 1,
  per_page: 10,
  total: 0,
  last_page: 1
})

const permissionMatrix = ref({})

const fetchPermissions = async () => {
  try {
    const res = await permissionService.getAll()
    // Tùy thuộc vào axios interceptor, data có thể nằm ở res.data hoặc res.data.data
    const payload = res.data?.success !== undefined ? res.data : (res.success !== undefined ? res : null);
    if (payload && payload.success) {
      permissionMatrix.value = payload.data
    } else {
      permissionMatrix.value = res.data || res;
    }
  } catch (error) {
    console.error('Lỗi khi tải permissions:', error)
  }
}

onMounted(() => {
  fetchRoles()
  fetchPermissions()
})



const fetchRoles = async () => {
  loading.value = true
  try {
    const res = await roleService.getAll({
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      search: searchQuery.value
    })
    const payload = res.data?.data !== undefined ? res.data : res
    roles.value = payload.data || []
    
    if (payload.meta) {
      pagination.value = {
        current_page: payload.meta.current_page || 1,
        per_page: payload.meta.per_page || 10,
        total: payload.meta.total || 0,
        last_page: payload.meta.last_page || 1
      }
    }
  } catch (error) {
    console.error('Lỗi tải danh sách vai trò:', error)
  } finally {
    loading.value = false
  }
}

const goToPage = (page) => {
  pagination.value.current_page = page
  fetchRoles()
}

const handlePerPageChange = (perPage) => {
  pagination.value.per_page = perPage
  goToPage(1)
}

const handleSearch = () => {
  goToPage(1)
}

const getRoleColor = (id) => {
  const colors = ['bg-violet-500', 'bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-pink-500', 'bg-indigo-500']
  return colors[id % colors.length]
}

// ─── Modal state ──────────────────────────────────────────────────────────────
const modal = ref(null)         // null | 'form' | 'view' | 'delete'
const isAdding = ref(true)
const selectedRole = ref(null)

const openAdd    = ()     => { isAdding.value = true;  selectedRole.value = null; modal.value = 'form' }
const openEdit   = (role) => { isAdding.value = false; selectedRole.value = role; modal.value = 'form' }
const openView   = (role) => { selectedRole.value = role; modal.value = 'view' }
const openDelete = (role) => { selectedRole.value = role; modal.value = 'delete' }
const closeModal = ()     => { modal.value = null }

const handleSave = async ({ formData, applyBackendErrors, focusFirstError }) => {
  try {
    if (isAdding.value) {
      await roleService.create(formData)
    } else {
      await roleService.update(selectedRole.value.id, formData)
    }
    fetchRoles()
    closeModal()
  } catch (error) {
    if (applyBackendErrors) {
      const serverMsg = applyBackendErrors(error?.response?.data || error)
      if (serverMsg) console.error(serverMsg)
      if (focusFirstError) focusFirstError()
    } else {
      console.error('Lỗi lưu role:', error)
    }
  }
}

const handleDelete = async (role) => {
  try {
    await roleService.delete(role.id)
    fetchRoles()
    closeModal()
  } catch (error) {
    console.error('Lỗi xóa role:', error)
  }
}
</script>
