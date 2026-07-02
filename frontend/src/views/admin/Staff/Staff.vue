<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Tài khoản Nhân viên</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý tài khoản và phân quyền nhân viên trong hệ thống</p>
      </div>
      <button
        id="btn-open-add-staff"
        type="button"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm nhân viên mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng nhân viên</p>
          <p class="text-3xl font-bold text-slate-800">{{ stats.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hoạt động</p>
          <p class="text-3xl font-bold text-slate-800">{{ stats.active }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Ngưng hoạt động</p>
          <p class="text-3xl font-bold text-slate-800">{{ stats.inactive }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đăng nhập hôm nay</p>
          <p class="text-3xl font-bold text-slate-800">{{ stats.loginToday }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
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

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-xs">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            id="search-staff"
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo tên, email, SĐT..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>

        <div class="relative">
          <select
            id="filter-staff-status"
            v-model="filterStatus"
            class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="1">Đang hoạt động</option>
            <option value="0">Ngưng hoạt động</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>

      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[70px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nhân viên</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[150px]">Số điện thoại</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[140px]">Đăng nhập cuối</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Ngày tạo</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <tr v-if="loading" v-for="i in paginationMeta.per_page" :key="'sk-' + i">
              <td colspan="8" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr>

            <!-- Rows -->
            <tr
              v-else
              v-for="staff in staffList"
              :key="staff.id"
              class="hover:bg-blue-50/40 transition-colors duration-100 group"
            >
              <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ staff.id }}</td>

              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold shrink-0 overflow-hidden">
                    <img v-if="staff.avatar" :src="staff.avatar" :alt="staff.full_name" class="w-full h-full object-cover" />
                    <span v-else>{{ getInitials(staff.full_name) }}</span>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">{{ staff.full_name }}</p>
                    <div v-if="staff.roles && staff.roles.length" class="flex flex-wrap gap-1 mt-1">
                      <span
                        v-for="role in staff.roles"
                        :key="role.id"
                        class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase"
                      >
                        {{ role.name }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>

              <td class="py-4 px-4 text-slate-600 text-sm">{{ staff.email }}</td>
              <td class="py-4 px-4 text-slate-600 text-sm font-mono">{{ staff.phone_number || '—' }}</td>

              <td class="py-4 px-4">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="staff.is_active
                    ? 'bg-emerald-50 text-emerald-700'
                    : 'bg-red-50 text-red-600'"
                >
                  <span
                    class="w-1.5 h-1.5 rounded-full"
                    :class="staff.is_active ? 'bg-emerald-500' : 'bg-red-400'"
                  ></span>
                  {{ staff.is_active ? 'Hoạt động' : 'Ngưng HĐ' }}
                </span>
              </td>

              <td class="py-4 px-4 text-xs text-slate-500">{{ staff.last_login_at || '—' }}</td>
              <td class="py-4 px-4 text-xs text-slate-500">{{ staff.created_at || '—' }}</td>

              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button
                    type="button"
                    @click="openViewModal(staff)"
                    class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                    title="Xem chi tiết"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button
                    type="button"
                    @click="openEditModal(staff)"
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
                    @click="openDeleteModal(staff)"
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

            <!-- Empty state -->
            <tr v-if="!loading && staffList.length === 0">
              <td colspan="8" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy nhân viên nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="paginationMeta.current_page"
          :perPage="paginationMeta.per_page"
          :total="paginationMeta.total"
          :lastPage="paginationMeta.last_page"
          :loading="loading"
          @update:currentPage="goToPage"
          @update:perPage="handlePerPageChange"
        />
      </div>
    </div>

    <!-- ========== ADD / EDIT MODAL ========== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showFormModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="closeFormModal"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[560px] animate-modal-in flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line v-if="modalMode === 'add'" x1="12" y1="5" x2="12" y2="19"/><line v-if="modalMode === 'add'" x1="5" y1="12" x2="19" y2="12"/>
                    <path v-if="modalMode === 'edit'" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path v-if="modalMode === 'edit'" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">
                  {{ modalMode === 'add' ? 'Thêm nhân viên mới' : 'Chỉnh sửa nhân viên' }}
                </h2>
              </div>
              <button type="button" @click="closeFormModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <div class="px-7 py-6 overflow-y-auto space-y-4">
              <!-- Họ và tên -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                  Họ và tên <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-staff-fullname"
                  v-model="form.full_name"
                  type="text"
                  placeholder="Nguyễn Văn A"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-staff-email"
                  v-model="form.email"
                  type="email"
                  placeholder="nhanvien@example.com"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
              </div>

              <!-- Số điện thoại -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
                <input
                  id="input-staff-phone"
                  v-model="form.phone_number"
                  type="text"
                  placeholder="090 123 4567"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
              </div>

              <!-- Mật khẩu -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                  Mật khẩu
                  <span v-if="modalMode === 'add'" class="text-red-500">*</span>
                  <span v-else class="text-xs font-normal text-slate-400 ml-1">(để trống nếu không đổi)</span>
                </label>
                <input
                  id="input-staff-password"
                  v-model="form.password"
                  type="password"
                  placeholder="••••••••"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
              </div>

              <!-- Avatar URL -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Avatar (URL)</label>
                <input
                  id="input-staff-avatar"
                  v-model="form.avatar"
                  type="text"
                  placeholder="https://example.com/avatar.jpg"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
                <div v-if="form.avatar" class="mt-2 flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 bg-slate-100">
                    <img :src="form.avatar" alt="Preview avatar" class="w-full h-full object-cover" />
                  </div>
                  <p class="text-xs text-slate-400">Xem trước avatar</p>
                </div>
              </div>

              <!-- Vai trò -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Vai trò (Roles)</label>
                <div class="grid grid-cols-2 gap-2 p-3 border border-slate-200 rounded-xl bg-slate-50">
                  <div
                    v-for="role in dropdownRoles"
                    :key="role.id"
                    class="flex items-center gap-2"
                  >
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs select-none">
                      <input
                        type="checkbox"
                        class="rounded border-slate-300 text-[#0258cb] focus:ring-[#0258cb]/20 cursor-pointer"
                        :value="role.id"
                        v-model="form.role_ids"
                      />
                      <span class="font-medium text-slate-700">{{ role.name }}</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Quyền đặc cách -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Quyền đặc cách (Permissions)</label>
                <div class="grid grid-cols-2 gap-2 p-3 border border-slate-200 rounded-xl bg-slate-50 max-h-40 overflow-y-auto">
                  <div
                    v-for="perm in allPermissions"
                    :key="perm.id"
                    class="flex items-center gap-2"
                  >
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs select-none">
                      <input
                        type="checkbox"
                        class="rounded border-slate-300 text-[#0258cb] focus:ring-[#0258cb]/20 cursor-pointer"
                        :value="perm.id"
                        v-model="form.permission_ids"
                      />
                      <span class="font-medium text-slate-700">{{ perm.module }}.{{ perm.action }}</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Trạng thái -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Trạng thái</label>
                <div class="relative">
                  <select
                    id="select-staff-status"
                    v-model="form.is_active"
                    class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                  >
                    <option :value="true">Hoạt động</option>
                    <option :value="false">Ngưng hoạt động</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </span>
                </div>
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
                id="btn-submit-staff"
                type="button"
                @click="submitForm"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
              >
                {{ modalMode === 'add' ? 'Thêm nhân viên' : 'Lưu thay đổi' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ========== VIEW DETAIL MODAL ========== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showViewModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showViewModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[520px] animate-modal-in flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Chi tiết nhân viên</h2>
              </div>
              <button type="button" @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <div class="px-7 py-6 overflow-y-auto space-y-4">
              <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white text-xl font-bold shrink-0 overflow-hidden">
                  <img v-if="viewTarget?.avatar" :src="viewTarget.avatar" :alt="viewTarget.full_name" class="w-full h-full object-cover" />
                  <span v-else>{{ getInitials(viewTarget?.full_name) }}</span>
                </div>
                <div>
                  <p class="text-base font-bold text-slate-800">{{ viewTarget?.full_name }}</p>
                  <p class="text-sm text-slate-500">{{ viewTarget?.email }}</p>
                  <span
                    class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="viewTarget?.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="viewTarget?.is_active ? 'bg-emerald-500' : 'bg-red-400'"></span>
                    {{ viewTarget?.is_active ? 'Đang hoạt động' : 'Ngưng hoạt động' }}
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
                  <p class="text-sm font-mono font-semibold text-slate-700">#{{ viewTarget?.id }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Số điện thoại</p>
                  <p class="text-sm font-mono text-slate-700">{{ viewTarget?.phone_number || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Avatar</p>
                  <p class="text-xs text-slate-600 truncate">{{ viewTarget?.avatar || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Đăng nhập cuối</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.last_login_at || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.created_at || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cập nhật</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.updated_at || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Đã xóa</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.deleted_at || '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3 col-span-2">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Vai trò (Roles)</p>
                  <div v-if="viewTarget?.roles && viewTarget.roles.length" class="flex flex-wrap gap-1 mt-1">
                    <span
                      v-for="role in viewTarget.roles"
                      :key="role.id"
                      class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 uppercase"
                    >
                      {{ role.name }}
                    </span>
                  </div>
                  <p v-else class="text-xs text-slate-500">—</p>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button type="button" @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
                Đóng
              </button>
              <button
                type="button"
                @click="openEditModal(viewTarget); showViewModal = false"
                class="px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all duration-150"
              >
                Chỉnh sửa
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ========== CONFIRM DELETE MODAL ========== -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa nhân viên"
      message="Bạn có chắc chắn muốn xóa nhân viên"
      :itemName="deleteTarget?.full_name"
      messageSuffix="không? Tài khoản sẽ bị xóa khỏi hệ thống."
      confirmLabel="Xóa nhân viên"
      @confirm="onDeleteConfirm"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue'
import { useStaffStore } from '@/stores/admin/staffStore'
import { useRoleStore } from '@/stores/admin/roleStore'
import { usePermissionStore } from '@/stores/admin/permissionStore'
import Pagination from '@/components/admin/Pagination.vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

const staffStore = useStaffStore()
const roleStore = useRoleStore()
const permissionStore = usePermissionStore()

// State liên kết Store
const staffList = computed(() => staffStore.staffList)
const dropdownRoles = computed(() => roleStore.dropdownRoles)
const allPermissions = computed(() => permissionStore.allPermissions)
const loading = computed(() => staffStore.loading || roleStore.loading || permissionStore.loading)
const errorMessage = computed(() => staffStore.error || roleStore.error || permissionStore.error)
const paginationMeta = computed(() => staffStore.meta)

const successMessage = ref(null)
const showSuccess = (msg) => {
  successMessage.value = msg
  setTimeout(() => {
    successMessage.value = null
  }, 4000)
}

// Thống kê động (Stats)
const stats = computed(() => {
  const list = staffStore.staffList
  const total = paginationMeta.value.total
  const active = list.filter(s => s.is_active).length
  const inactive = list.filter(s => !s.is_active).length
  return {
    total,
    active,
    inactive,
    loginToday: list.filter(s => s.last_login_at && s.last_login_at.includes('Hôm nay')).length || 0
  }
})

// Bộ lọc (Search & Filter)
const searchQuery = ref('')
const filterStatus = ref('')

// Tự động gọi API khi có thay đổi tìm kiếm hoặc bộ lọc (Debounce nếu cần)
watch([searchQuery, filterStatus], () => {
  goToPage(1)
})

onMounted(() => {
  staffStore.fetchStaffs()
  roleStore.fetchDropdownRoles()
  permissionStore.fetchAllPermissions()
})

const goToPage = (page) => {
  staffStore.fetchStaffs({
    search: searchQuery.value,
    status: filterStatus.value,
    page
  })
}

const handlePerPageChange = (perPage) => {
  staffStore.meta.per_page = perPage
  goToPage(1)
}

// Modals State
const showFormModal = ref(false)
const showViewModal = ref(false)
const showDeleteModal = ref(false)
const modalMode = ref('add')
const viewTarget = ref(null)
const deleteTarget = ref(null)

const form = reactive({
  id: null,
  full_name: '',
  email: '',
  phone_number: '',
  password: '',
  avatar: '',
  is_active: true,
  role_ids: [],
  permission_ids: [],
})

const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0][0].toUpperCase()
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}

const resetForm = () => {
  form.id = null
  form.full_name = ''
  form.email = ''
  form.phone_number = ''
  form.password = ''
  form.avatar = ''
  form.is_active = true
  form.role_ids = []
  form.permission_ids = []
}

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (staff) => {
  resetForm()
  form.id = staff.id
  form.full_name = staff.full_name
  form.email = staff.email
  form.phone_number = staff.phone_number || ''
  form.password = ''
  form.avatar = staff.avatar || ''
  form.is_active = staff.is_active
  form.role_ids = staff.roles ? staff.roles.map(r => r.id) : []
  form.permission_ids = staff.permissions ? staff.permissions.map(p => p.id) : []
  modalMode.value = 'edit'
  showFormModal.value = true
}

const openViewModal = (staff) => {
  viewTarget.value = staff
  showViewModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
}

const openDeleteModal = (staff) => {
  deleteTarget.value = staff
  showDeleteModal.value = true
}

const submitForm = async () => {
  try {
    const payload = { ...form }
    if (modalMode.value === 'add') {
      await staffStore.createStaff(payload)
      showSuccess("Đã thêm tài khoản nhân viên mới thành công.")
    } else {
      await staffStore.updateStaff(form.id, payload)
      showSuccess("Đã cập nhật thông tin nhân viên thành công.")
    }
    showFormModal.value = false
  } catch (err) {
    console.error(err)
  }
}

const onDeleteConfirm = async () => {
  if (deleteTarget.value) {
    await staffStore.deleteStaff(deleteTarget.value.id)
    showSuccess("Đã xóa nhân viên thành công.")
  }
  showDeleteModal.value = false
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
</style>
