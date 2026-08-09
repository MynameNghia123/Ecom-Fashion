<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Khách hàng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Xem và quản lý toàn bộ khách hàng trong hệ thống</p>
      </div>
      <!-- Add customer button -->
      <button
        id="btn-open-add-customer"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-semibold rounded-xl shadow-md shadow-neutral-200 hover:shadow-neutral-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm khách hàng mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <!-- Tổng khách hàng -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng khách hàng</p>
          <p class="text-3xl font-bold text-slate-800">{{ customerStore.meta.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
      </div>

      <!-- Đang hoạt động -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hoạt động</p>
          <p class="text-3xl font-bold text-slate-800">{{ activeCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>

      <!-- Bị khóa -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Bị khóa</p>
          <p class="text-3xl font-bold text-slate-800">{{ bannedCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
        </div>
      </div>

      <!-- Đăng ký hôm nay -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Mới hôm nay</p>
          <p class="text-3xl font-bold text-slate-800">{{ newTodayCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="customerStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ customerStore.error }}
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
            id="search-customer"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm theo tên, email, SĐT..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
          />
        </div>

        <!-- Filter trạng thái -->
        <div class="relative">
          <select
            id="filter-customer-status"
            v-model="filterStatus"
            @change="onFilterChange"
            class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 cursor-pointer"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="1">Đang hoạt động</option>
            <option value="0">Bị khóa</option>
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
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khách hàng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Số điện thoại</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Ngày đăng ký</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <tr v-if="customerStore.loading" v-for="i in customerStore.meta.per_page || 10" :key="'sk-'+i">
              <td colspan="7" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr>

            <!-- Rows -->
            <tr
              v-else
              v-for="customer in customerStore.customers"
              :key="customer.id"
              class="hover:bg-neutral-100/40 transition-colors duration-100 group"
            >
              <!-- ID -->
              <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ customer.id }}</td>

              <!-- Khách hàng (avatar + tên) -->
              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-violet-500 flex items-center justify-center text-white text-xs font-bold shrink-0 overflow-hidden">
                    <img v-if="customer.avatar" :src="customer.avatar" :alt="getFullName(customer)" class="w-full h-full object-cover" />
                    <span v-else>{{ getInitials(customer.first_name, customer.last_name) }}</span>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">{{ getFullName(customer) }}</p>
                  </div>
                </div>
              </td>

              <!-- Email -->
              <td class="py-4 px-4 text-slate-600 text-sm">{{ customer.email }}</td>

              <!-- Số điện thoại -->
              <td class="py-4 px-4 text-slate-600 text-sm font-mono">{{ customer.phone_number || '—' }}</td>

              <!-- Trạng thái -->
              <td class="py-4 px-4">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="customer.status === 1
                    ? 'bg-emerald-50 text-emerald-700'
                    : 'bg-red-50 text-red-600'"
                >
                  <span
                    class="w-1.5 h-1.5 rounded-full"
                    :class="customer.status === 1 ? 'bg-emerald-500' : 'bg-red-400'"
                  ></span>
                  {{ customer.status === 1 ? 'Hoạt động' : 'Bị khóa' }}
                </span>
              </td>

              <!-- Ngày đăng ký -->
              <td class="py-4 px-4 text-xs text-slate-500">{{ formatDate(customer.created_at) }}</td>

              <!-- Hành động -->
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <!-- View -->
                  <button
                    @click="openViewModal(customer)"
                    class="p-2 rounded-lg text-slate-400 hover:text-black hover:bg-neutral-100 transition-all duration-150"
                    title="Xem chi tiết"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <!-- Edit -->
                  <button
                    @click="openEditModal(customer)"
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
                    @click="confirmDelete(customer)"
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
            <tr v-if="!customerStore.loading && customerStore.customers.length === 0">
              <td colspan="7" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy khách hàng nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="customerStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="customerStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="customerStore.meta.total"
          :lastPage="customerStore.meta.last_page"
          :loading="customerStore.loading"
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
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line v-if="modalMode === 'add'" x1="12" y1="5" x2="12" y2="19"/><line v-if="modalMode === 'add'" x1="5" y1="12" x2="19" y2="12"/>
                    <path v-if="modalMode === 'edit'" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path v-if="modalMode === 'edit'" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">
                  {{ modalMode === 'add' ? 'Thêm khách hàng mới' : 'Chỉnh sửa Khách hàng' }}
                </h2>
              </div>
              <button @click="closeFormModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-7 py-6 overflow-y-auto space-y-4">

              <!-- Server error banner -->
              <div
                v-if="formServerError"
                class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
              >
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ formServerError }}
              </div>

              <!-- Row: Họ & Tên -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Họ -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">Họ</label>
                  <input
                    id="input-customer-lastname"
                    v-model="form.last_name"
                    type="text"
                    placeholder="Nguyễn"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('last_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('last_name')" class="text-xs text-red-500 mt-1">{{ fieldError('last_name') }}</p>
                </div>

                <!-- Tên -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">Tên</label>
                  <input
                    id="input-customer-firstname"
                    v-model="form.first_name"
                    type="text"
                    placeholder="Văn Long"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('first_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('first_name')" class="text-xs text-red-500 mt-1">{{ fieldError('first_name') }}</p>
                </div>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Email</label>
                <input
                  id="input-customer-email"
                  v-model="form.email"
                  type="email"
                  placeholder="long.nv@gmail.com"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                  :class="fieldError('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('email')" class="text-xs text-red-500 mt-1">{{ fieldError('email') }}</p>
              </div>

              <!-- Số điện thoại -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
                <input
                  id="input-customer-phone"
                  v-model="form.phone_number"
                  type="text"
                  placeholder="090 123 4567"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                  :class="fieldError('phone_number') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('phone_number')" class="text-xs text-red-500 mt-1">{{ fieldError('phone_number') }}</p>
              </div>

              <!-- Mật khẩu -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Mật khẩu</label>
                <input
                  id="input-customer-password"
                  v-model="form.password"
                  type="password"
                  placeholder="••••••••"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                  :class="fieldError('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('password')" class="text-xs text-red-500 mt-1">{{ fieldError('password') }}</p>
              </div>

              <!-- Trạng thái -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Trạng thái</label>
                <div class="relative">
                  <select
                    id="select-customer-status"
                    v-model="form.status"
                    class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                  >
                    <option :value="1">Hoạt động</option>
                    <option :value="0">Bị khóa</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </span>
                </div>
                <p v-if="fieldError('status')" class="text-xs text-red-500 mt-1">{{ fieldError('status') }}</p>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button
                @click="closeFormModal"
                :disabled="formSubmitting"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150 disabled:opacity-50"
              >Hủy</button>
              <button
                id="btn-submit-customer"
                @click="submitForm"
                :disabled="formSubmitting"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm shadow-md shadow-neutral-200 hover:shadow-neutral-300 transition-all duration-200 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                {{ formSubmitting ? 'Đang lưu...' : (modalMode === 'add' ? 'Thêm khách hàng' : 'Lưu thay đổi') }}
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
            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Chi tiết khách hàng</h2>
              </div>
              <button @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Content -->
            <div class="px-7 py-6 overflow-y-auto space-y-4">
              <!-- Avatar + Tên -->
              <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-violet-500 flex items-center justify-center text-white text-xl font-bold shrink-0 overflow-hidden">
                  <img v-if="viewTarget?.avatar" :src="viewTarget.avatar" :alt="getFullName(viewTarget)" class="w-full h-full object-cover" />
                  <span v-else>{{ getInitials(viewTarget?.first_name, viewTarget?.last_name) }}</span>
                </div>
                <div>
                  <p class="text-base font-bold text-slate-800">{{ getFullName(viewTarget) }}</p>
                  <p class="text-sm text-slate-500">{{ viewTarget?.email }}</p>
                  <span
                    class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="viewTarget?.status === 1 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="viewTarget?.status === 1 ? 'bg-emerald-500' : 'bg-red-400'"></span>
                    {{ viewTarget?.status === 1 ? 'Đang hoạt động' : 'Bị khóa' }}
                  </span>
                </div>
              </div>

              <!-- Info Grid -->
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
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày đăng ký</p>
                  <p class="text-xs text-slate-600">{{ formatDate(viewTarget?.created_at) }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cập nhật</p>
                  <p class="text-xs text-slate-600">{{ formatDate(viewTarget?.updated_at) }}</p>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
                Đóng
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ========== CONFIRM DELETE MODAL ========== -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa khách hàng"
      message="Bạn có chắc chắn muốn xóa khách hàng"
      :itemName="deleteTarget ? getFullName(deleteTarget) : ''"
      messageSuffix="không? Hành động này không thể hoàn tác và tài khoản sẽ bị xóa hoàn toàn."
      confirmLabel="Xóa khách hàng"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { useCustomerStore } from '@/stores/admin/customerStore'
import Pagination from '@/components/admin/Pagination.vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

const customerStore = useCustomerStore()

// ─── Mounted ──────────────────────────────────────────────────────────────────
onMounted(() => {
  customerStore.fetchCustomers()
})

// ─── Stats ────────────────────────────────────────────────────────────────────
// Dùng số liệu từ meta (tính toàn bộ DB phía backend, không bị ảnh hưởng bởi phân trang)
const activeCount    = computed(() => customerStore.meta.total_active)
const bannedCount    = computed(() => customerStore.meta.total_banned)
const newTodayCount  = computed(() => customerStore.meta.new_today)

// ─── Helpers ──────────────────────────────────────────────────────────────────
const getFullName = (customer) => {
  if (!customer) return ''
  return `${customer.last_name || ''} ${customer.first_name || ''}`.trim()
}

const getInitials = (first, last) => {
  const f = first ? first.trim().split(' ').pop()[0] : ''
  const l = last ? last.trim()[0] : ''
  return (l + f).toUpperCase() || '?'
}

const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  try {
    return new Date(dateStr).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
  } catch {
    return dateStr
  }
}

// ─── Search & Filter ──────────────────────────────────────────────────────────
const searchQuery = ref('')
const filterStatus = ref('')
let searchTimer = null

const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    customerStore.fetchCustomers({ search: searchQuery.value, status: filterStatus.value, page: 1 })
  }, 400)
}

const onFilterChange = () => {
  customerStore.fetchCustomers({ search: searchQuery.value, status: filterStatus.value, page: 1 })
}

// ─── Pagination ───────────────────────────────────────────────────────────────
const goToPage = (page) => {
  customerStore.fetchCustomers({ search: searchQuery.value, status: filterStatus.value, page })
}

const handlePerPageChange = (newPerPage) => {
  customerStore.meta.per_page = newPerPage
  customerStore.fetchCustomers({ search: searchQuery.value, status: filterStatus.value, page: 1 })
}

// ─── View Modal ───────────────────────────────────────────────────────────────
const showViewModal = ref(false)
const viewTarget = ref(null)
const openViewModal = (customer) => {
  viewTarget.value = customer
  showViewModal.value = true
}

// ─── Add / Edit Modal (Form) ─────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const formSubmitting = ref(false)
const formServerError = ref('')
const formErrors = reactive({})

const form = reactive({
  id: null,
  first_name: '',
  last_name: '',
  email: '',
  phone_number: '',
  password: '',
  status: 1,
})

const resetForm = () => {
  form.id = null
  form.first_name = ''
  form.last_name = ''
  form.email = ''
  form.phone_number = ''
  form.password = ''
  form.status = 1
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])
}

const fieldError = (field) => formErrors[field]?.[0] ?? ''

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (customer) => {
  resetForm()
  form.id = customer.id
  form.first_name = customer.first_name || ''
  form.last_name = customer.last_name || ''
  form.email = customer.email || ''
  form.phone_number = customer.phone_number || ''
  form.password = '' // Để trống khi sửa trừ khi muốn đổi mật khẩu
  form.status = customer.status
  modalMode.value = 'edit'
  showFormModal.value = true
}

const closeFormModal = () => {
  if (formSubmitting.value) return
  showFormModal.value = false
}

const submitForm = async () => {
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])

  // Validation phía client
  if (!form.last_name.trim()) { formErrors.last_name = ['Họ không được để trống.']; return }
  if (!form.first_name.trim()) { formErrors.first_name = ['Tên không được để trống.']; return }
  if (!form.email.trim()) { formErrors.email = ['Email không được để trống.']; return }
  if (modalMode.value === 'add' && !form.password) { formErrors.password = ['Mật khẩu không được để trống.']; return }

  formSubmitting.value = true
  try {
    const payload = {
      first_name: form.first_name.trim(),
      last_name: form.last_name.trim(),
      email: form.email.trim(),
      phone_number: form.phone_number.trim(),
      status: form.status,
    }
    if (form.password) {
      payload.password = form.password
    }

    if (modalMode.value === 'add') {
      await customerStore.createCustomer(payload)
    } else {
      await customerStore.updateCustomer(form.id, payload)
    }
    showFormModal.value = false
  } catch (e) {
    if (e.errors) {
      Object.assign(formErrors, e.errors)
    } else {
      formServerError.value = e.message
    }
  } finally {
    formSubmitting.value = false
  }
}

// ─── Delete Modal ─────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (customer) => {
  deleteTarget.value = customer
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await customerStore.deleteCustomer(deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
    console.error('Delete customer error:', e)
  }
}

// ─── Expose perPage để skeleton dùng ─────────────────────────────────────────
const perPage = computed(() => customerStore.meta.per_page || 10)
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
