<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Vai trò & Phân quyền</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý vai trò trong hệ thống và phân quyền chi tiết cho từng chức năng</p>
      </div>
      <button
        id="btn-open-add-role"
        type="button"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm vai trò mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng số vai trò</p>
          <p class="text-3xl font-bold text-slate-800">{{ roles.length }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng số quyền hạn</p>
          <p class="text-3xl font-bold text-slate-800">{{ allPermissions.length }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Mô-đun chức năng</p>
          <p class="text-3xl font-bold text-slate-800">{{ Object.keys(permissionsByModule).length }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="errorMessage"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ errorMessage }}
    </div>

    <!-- Success Message -->
    <div
      v-if="successMessage"
      class="flex items-center gap-3 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      {{ successMessage }}
    </div>

    <!-- Main Workspace: Split Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
      
      <!-- Left Panel: Roles List -->
      <div class="lg:col-span-5 space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Danh sách vai trò</h2>
            <span class="text-xs text-slate-400 font-medium">Chọn để phân quyền</span>
          </div>

          <!-- Loading state -->
          <div v-if="loading && roles.length === 0" class="space-y-3">
            <div v-for="i in 3" :key="i" class="h-20 bg-slate-100 rounded-xl animate-pulse"></div>
          </div>

          <!-- Roles List -->
          <div v-else class="space-y-3">
            <div
              v-for="role in roles"
              :key="role.id"
              @click="selectRole(role)"
              class="group relative p-4 rounded-xl border cursor-pointer transition-all duration-200 flex flex-col justify-between"
              :class="selectedRole?.id === role.id
                ? 'bg-blue-50/50 border-[#0258cb] ring-2 ring-[#0258cb]/10'
                : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50/40'"
            >
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="font-bold text-slate-800 text-sm group-hover:text-[#0258cb] transition-colors">
                    {{ role.name }}
                  </h3>
                  <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                    {{ role.description || 'Chưa có mô tả' }}
                  </p>
                </div>
                
                <!-- Action Buttons (hidden by default, show on hover or selected) -->
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0 ml-2">
                  <button
                    type="button"
                    @click.stop="openEditModal(role)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors"
                    title="Sửa thông tin vai trò"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button
                    v-if="role.name !== 'admin'"
                    type="button"
                    @click.stop="openDeleteModal(role)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                    title="Xóa vai trò"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Permission badges indicator -->
              <div class="mt-3 flex items-center justify-between flex-wrap gap-2 text-xs">
                <span class="inline-flex items-center gap-1 text-[#0258cb] font-semibold">
                  <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  {{ role.permissions?.length || 0 }} quyền hạn
                </span>
                
                <span class="text-slate-400 font-mono text-[10px]">ID: #{{ role.id }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Panel: Permission Matrix -->
      <div class="lg:col-span-7">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
          
          <!-- Matrix Header -->
          <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-wrap gap-4">
            <div>
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#0258cb] animate-pulse"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                  {{ selectedRole ? `Bảng gán quyền: ${selectedRole.name}` : 'Thiết lập quyền' }}
                </h2>
              </div>
              <p class="text-xs text-slate-500 mt-1">
                {{ selectedRole ? `Đang thiết lập quyền cho vai trò "${selectedRole.name}"` : 'Chọn vai trò bên trái để tiếp tục' }}
              </p>
            </div>

            <!-- Global Select All -->
            <div v-if="selectedRole" class="flex items-center gap-2">
              <label class="relative inline-flex items-center cursor-pointer group">
                <input
                  type="checkbox"
                  class="sr-only peer"
                  :checked="isAllPermissionsChecked"
                  @change="toggleAllPermissions"
                  :disabled="selectedRole.name === 'admin'"
                />
                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0258cb] peer-disabled:opacity-50"></div>
                <span class="ms-2 text-xs font-semibold text-slate-700 select-none group-hover:text-[#0258cb] transition-colors">
                  Chọn tất cả
                </span>
              </label>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="!selectedRole" class="flex-1 flex flex-col items-center justify-center p-12 text-slate-400">
            <svg class="w-16 h-16 opacity-30 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <p class="text-sm font-medium">Vui lòng chọn hoặc thêm vai trò để xem & phân quyền</p>
          </div>

          <!-- Loading state -->
          <div v-else-if="loading" class="flex-1 flex items-center justify-center p-12">
            <div class="flex flex-col items-center gap-3">
              <div class="w-10 h-10 border-4 border-[#0258cb]/20 border-t-[#0258cb] rounded-full animate-spin"></div>
              <p class="text-xs text-slate-500">Đang tải cấu hình quyền hạn...</p>
            </div>
          </div>

          <!-- Permission Matrix Form -->
          <div v-else class="flex-1 p-5 overflow-y-auto max-h-[600px] space-y-4">
            
            <!-- Warning for Superadmin -->
            <div
              v-if="selectedRole.name === 'admin'"
              class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-800"
            >
              <svg class="w-4 h-4 shrink-0 mt-0.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
              <div>
                <p class="font-bold">Vai trò Quản trị viên tối cao (admin)</p>
                <p class="mt-0.5 leading-relaxed">
                  Vai trò này mặc định được gán và giữ toàn bộ quyền hạn trong hệ thống. Bạn không cần và không thể sửa quyền trực tiếp của vai trò này.
                </p>
              </div>
            </div>

            <!-- Modules Grid -->
            <div class="space-y-4">
              <div
                v-for="(perms, moduleName) in permissionsByModule"
                :key="moduleName"
                class="border border-slate-100 rounded-xl overflow-hidden shadow-xs hover:border-slate-200 transition-colors"
              >
                <!-- Module Header -->
                <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 border-b border-slate-100">
                  <span class="text-xs font-bold text-slate-700 tracking-wide">
                     {{ getModuleLabel(moduleName) }}
                  </span>
                  
                  <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs">
                    <input
                      type="checkbox"
                      class="rounded border-slate-300 text-[#0258cb] focus:ring-[#0258cb]/20 cursor-pointer disabled:opacity-50"
                      :checked="isModuleAllChecked(moduleName)"
                      @change="toggleModule(moduleName)"
                      :disabled="selectedRole.name === 'admin'"
                    />
                    <span class="text-[11px] font-semibold text-slate-500 select-none">Tất cả</span>
                  </label>
                </div>

                <!-- Action Checkboxes -->
                <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <div
                    v-for="perm in perms"
                    :key="perm.id"
                    class="flex items-center gap-2"
                  >
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs select-none">
                      <input
                        type="checkbox"
                        class="rounded border-slate-300 text-[#0258cb] focus:ring-[#0258cb]/20 cursor-pointer disabled:opacity-50"
                        :value="perm.id"
                        v-model="selectedPermissionIds"
                        :disabled="selectedRole.name === 'admin'"
                      />
                      <span class="font-medium text-slate-700">
                        {{ getActionLabel(perm.action) }}
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Matrix Actions -->
          <div v-if="selectedRole" class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
            <button
              type="button"
              @click="resetPermissions"
              class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors disabled:opacity-50"
              :disabled="selectedRole.name === 'admin' || loading"
            >
              Đặt lại
            </button>
            <button
              type="button"
              @click="savePermissions"
              class="inline-flex items-center gap-2 px-6 py-2 bg-[#0258cb] hover:bg-[#004bb3] text-white text-xs font-bold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 disabled:opacity-70 active:scale-[0.98]"
              :disabled="selectedRole.name === 'admin' || loading"
            >
              <svg v-if="loading" class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <circle cx="12" cy="12" r="10"/><path d="M12 2v20M2 12h20"/>
              </svg>
              Lưu phân quyền
            </button>
          </div>

        </div>
      </div>

    </div>

    <!-- ========== ADD / EDIT ROLE MODAL ========== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showFormModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="closeFormModal"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[480px] animate-modal-in flex flex-col max-h-[90vh]">
            
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">
                  {{ modalMode === 'add' ? 'Thêm vai trò mới' : 'Chỉnh sửa vai trò' }}
                </h2>
              </div>
              <button type="button" @click="closeFormModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <div class="px-7 py-6 overflow-y-auto space-y-4">
              <!-- Name -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                  Tên vai trò <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-role-name"
                  v-model="form.name"
                  type="text"
                  placeholder="Ví dụ: Editor, Accountant,..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                  :disabled="modalMode === 'edit' && form.name === 'admin'"
                />
              </div>

              <!-- Description -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Mô tả vai trò</label>
                <textarea
                  id="input-role-description"
                  v-model="form.description"
                  rows="3"
                  placeholder="Ghi chú về mục đích hoặc quyền hạn của vai trò..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                ></textarea>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button
                type="button"
                @click="closeFormModal"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
              >
                Hủy
              </button>
              <button
                id="btn-submit-role"
                type="button"
                @click="submitForm"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
              >
                {{ modalMode === 'add' ? 'Thêm vai trò' : 'Lưu thay đổi' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Confirm Delete Modal -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa vai trò"
      message="Bạn có chắc chắn muốn xóa vai trò"
      :itemName="deleteTarget?.name"
      messageSuffix="không? Các tài khoản được gán vai trò này sẽ mất các quyền tương ứng."
      confirmLabel="Xóa vai trò"
      @confirm="onDeleteConfirm"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue'
import { useRoleStore } from '@/stores/admin/roleStore'
import { usePermissionStore } from '@/stores/admin/permissionStore'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

const roleStore = useRoleStore()
const permissionStore = usePermissionStore()

// State bindings from Pinia
const roles = computed(() => roleStore.roleList)
const allPermissions = computed(() => permissionStore.allPermissions)
const loading = computed(() => roleStore.loading || permissionStore.loading)
const errorMessage = computed(() => roleStore.error || permissionStore.error)

const successMessage = ref(null)

// Translations dictionaries
const moduleLabels = {
  staff: "Nhân viên",
  roles: "Vai trò & Quyền",
  permissions: "Danh sách quyền",
  customers: "Khách hàng",
  categories: "Danh mục",
  products: "Sản phẩm",
  orders: "Đơn hàng",
  coupons: "Mã giảm giá",
  reviews: "Đánh giá",
  blogs: "Bài viết / Tin tức",
  banners: "Banner quảng cáo",
  suppliers: "Nhà cung cấp",
  goods_receipts: "Nhập kho",
  system_settings: "Cấu hình hệ thống",
}

const actionLabels = {
  view: "Xem",
  create: "Thêm mới",
  update: "Cập nhật",
  delete: "Xóa",
}

const getModuleLabel = (moduleName) => {
  return moduleLabels[moduleName] || moduleName
}

const getActionLabel = (action) => {
  return actionLabels[action] || action
}

// Group permissions by module
const permissionsByModule = computed(() => {
  const groups = {}
  allPermissions.value.forEach(p => {
    if (!groups[p.module]) {
      groups[p.module] = []
    }
    groups[p.module].push(p)
  })
  return groups
})

// Selected role & permissions state
const selectedRole = ref(null)
const selectedPermissionIds = ref([])

const selectRole = (role) => {
  selectedRole.value = role
  if (role.name === 'admin') {
    // Superadmin has all permissions automatically
    selectedPermissionIds.value = allPermissions.value.map(p => p.id)
  } else {
    selectedPermissionIds.value = role.permissions ? role.permissions.map(p => p.id) : []
  }
}

// Checkbox helper methods
const isModuleAllChecked = (moduleName) => {
  const perms = permissionsByModule.value[moduleName] || []
  if (perms.length === 0) return false
  return perms.every(p => selectedPermissionIds.value.includes(p.id))
}

const toggleModule = (moduleName) => {
  const perms = permissionsByModule.value[moduleName] || []
  const ids = perms.map(p => p.id)
  
  if (isModuleAllChecked(moduleName)) {
    // Uncheck all for this module
    selectedPermissionIds.value = selectedPermissionIds.value.filter(id => !ids.includes(id))
  } else {
    // Check all for this module
    ids.forEach(id => {
      if (!selectedPermissionIds.value.includes(id)) {
        selectedPermissionIds.value.push(id)
      }
    })
  }
}

const isAllPermissionsChecked = computed(() => {
  const ids = allPermissions.value.map(p => p.id)
  if (ids.length === 0) return false
  return ids.every(id => selectedPermissionIds.value.includes(id))
})

const toggleAllPermissions = () => {
  const ids = allPermissions.value.map(p => p.id)
  if (isAllPermissionsChecked.value) {
    selectedPermissionIds.value = []
  } else {
    selectedPermissionIds.value = [...ids]
  }
}

const resetPermissions = () => {
  if (selectedRole.value) {
    selectRole(selectedRole.value)
  }
}

// Save permissions to role
const savePermissions = async () => {
  if (!selectedRole.value) return
  try {
    await roleStore.syncRolePermissions(selectedRole.value.id, selectedPermissionIds.value)
    
    // Refresh the local permissions list
    const updated = roleStore.roleList.find(r => r.id === selectedRole.value.id)
    if (updated) {
      selectedRole.value = updated
    }
    
    showSuccess("Quyền hạn của vai trò đã được đồng bộ thành công.")
  } catch (err) {
    console.error(err)
  }
}

const showSuccess = (msg) => {
  successMessage.value = msg
  setTimeout(() => {
    successMessage.value = null
  }, 4000)
}

// Modals State
const showFormModal = ref(false)
const showDeleteModal = ref(false)
const modalMode = ref('add')
const deleteTarget = ref(null)

const form = reactive({
  id: null,
  name: '',
  description: '',
})

const resetForm = () => {
  form.id = null
  form.name = ''
  form.description = ''
}

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (role) => {
  resetForm()
  form.id = role.id
  form.name = role.name
  form.description = role.description || ''
  modalMode.value = 'edit'
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
}

const openDeleteModal = (role) => {
  deleteTarget.value = role
  showDeleteModal.value = true
}

const submitForm = async () => {
  try {
    let res
    if (modalMode.value === 'add') {
      res = await roleStore.createRole({
        name: form.name,
        description: form.description
      })
      showSuccess("Vai trò mới đã được tạo.")
    } else {
      res = await roleStore.updateRole(form.id, {
        name: form.name,
        description: form.description
      })
      showSuccess("Đã cập nhật thông tin vai trò.")
    }
    showFormModal.value = false
    
    // Auto-select the newly added/edited role
    if (res?.data) {
      const match = roleStore.roleList.find(r => r.name === form.name)
      if (match) {
        selectRole(match)
      } else if (roleStore.roleList.length > 0) {
        selectRole(roleStore.roleList[0])
      }
    }
  } catch (err) {
    console.error(err)
  }
}

const onDeleteConfirm = async () => {
  if (deleteTarget.value) {
    await roleStore.deleteRole(deleteTarget.value.id)
    showSuccess("Đã xóa vai trò thành công.")
    if (selectedRole.value?.id === deleteTarget.value.id) {
      selectedRole.value = null
      selectedPermissionIds.value = []
      if (roleStore.roleList.length > 0) {
        selectRole(roleStore.roleList[0])
      }
    }
  }
  showDeleteModal.value = false
}

// Watchers
watch(roles, (newRoles) => {
  if (newRoles.length > 0 && !selectedRole.value) {
    selectRole(newRoles[0])
  } else if (newRoles.length > 0 && selectedRole.value) {
    // Refresh selected role
    const current = newRoles.find(r => r.id === selectedRole.value.id)
    if (current) {
      selectRole(current)
    }
  }
})

// Initialize
onMounted(async () => {
  await roleStore.fetchRoles()
  await permissionStore.fetchAllPermissions()
  if (roles.value.length > 0) {
    selectRole(roles.value[0])
  }
})
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
</style>
