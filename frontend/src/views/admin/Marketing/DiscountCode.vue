<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Mã giảm giá</h1>
        <p class="text-sm text-slate-500 mt-0.5">Tạo và quản lý toàn bộ mã khuyến mãi trong hệ thống</p>
      </div>
      <button
        id="btn-open-add-coupon"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tạo mã giảm giá
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <!-- Tổng mã -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng mã</p>
          <p class="text-3xl font-bold text-slate-800">{{ couponStore.meta.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
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

      <!-- Đã tắt / Hết hạn -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã tắt / Hết hạn</p>
          <p class="text-3xl font-bold text-slate-800">{{ inactiveCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
        </div>
      </div>

      <!-- Phần trăm giảm -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Giảm theo %</p>
          <p class="text-3xl font-bold text-slate-800">{{ percentCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="couponStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ couponStore.error }}
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
            id="search-coupon"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm theo mã code..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>

        <!-- Filter loại giảm giá -->
        <div class="relative">
          <select
            id="filter-coupon-type"
            v-model="filterType"
            @change="onFilterChange"
            class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer"
          >
            <option value="">Tất cả loại</option>
            <option value="percent">Phần trăm (%)</option>
            <option value="fixed">Số tiền cố định</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>

        <!-- Filter trạng thái -->
        <div class="relative">
          <select
            id="filter-coupon-status"
            v-model="filterStatus"
            @change="onFilterChange"
            class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="1">Đang hoạt động</option>
            <option value="0">Đã tắt</option>
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
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[60px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mã Code</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Loại</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Giá trị</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[150px]">Đơn tối thiểu</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Lượt dùng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Hết hạn</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <tr v-if="couponStore.loading" v-for="i in (couponStore.meta.per_page || 10)" :key="'sk-'+i">
              <td colspan="9" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr>

            <!-- Rows -->
            <tr
              v-else
              v-for="coupon in couponStore.coupons"
              :key="coupon.id"
              class="hover:bg-blue-50/40 transition-colors duration-100 group"
            >
              <!-- ID -->
              <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ coupon.id }}</td>

              <!-- Mã Code -->
              <td class="py-4 px-4">
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 font-mono text-sm font-bold text-slate-700 tracking-widest select-all">
                    {{ coupon.code }}
                  </span>
                  <button
                    @click="copyCode(coupon.code)"
                    class="opacity-0 group-hover:opacity-100 p-1 rounded text-slate-400 hover:text-[#0258cb] transition-all duration-150"
                    title="Sao chép mã"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                  </button>
                </div>
              </td>

              <!-- Loại -->
              <td class="py-4 px-4">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="coupon.type === 'percent' ? 'bg-violet-50 text-violet-700' : 'bg-amber-50 text-amber-700'"
                >
                  {{ coupon.type === 'percent' ? '% Phần trăm' : '₫ Cố định' }}
                </span>
              </td>

              <!-- Giá trị -->
              <td class="py-4 px-4 font-semibold text-slate-700">
                <span v-if="coupon.type === 'percent'">{{ coupon.discount_value }}%</span>
                <span v-else>{{ formatCurrency(coupon.discount_value) }}</span>
              </td>

              <!-- Đơn tối thiểu -->
              <td class="py-4 px-4 text-slate-600 text-sm">
                {{ coupon.price_min_order_value ? formatCurrency(coupon.price_min_order_value) : '—' }}
              </td>

              <!-- Lượt dùng -->
              <td class="py-4 px-4">
                <div class="flex items-center gap-2">
                  <span class="text-slate-700 font-medium">{{ coupon.used_count }}</span>
                  <span class="text-slate-400">/</span>
                  <span class="text-slate-500">{{ coupon.max_usage ?? '∞' }}</span>
                </div>
                <!-- Progress bar -->
                <div v-if="coupon.max_usage" class="mt-1 h-1.5 bg-slate-100 rounded-full w-20 overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all duration-300"
                    :class="usagePercent(coupon) >= 90 ? 'bg-red-400' : usagePercent(coupon) >= 60 ? 'bg-amber-400' : 'bg-emerald-400'"
                    :style="{ width: usagePercent(coupon) + '%' }"
                  ></div>
                </div>
              </td>

              <!-- Hết hạn -->
              <td class="py-4 px-4">
                <span
                  v-if="coupon.expiry_date"
                  class="text-xs"
                  :class="isExpired(coupon.expiry_date) ? 'text-red-500 font-semibold' : 'text-slate-500'"
                >
                  {{ formatDate(coupon.expiry_date) }}
                  <span v-if="isExpired(coupon.expiry_date)" class="block text-[10px] font-bold text-red-400">Đã hết hạn</span>
                </span>
                <span v-else class="text-slate-400 text-xs">Không giới hạn</span>
              </td>

              <!-- Trạng thái -->
              <td class="py-4 px-4">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="coupon.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                >
                  <span
                    class="w-1.5 h-1.5 rounded-full"
                    :class="coupon.is_active ? 'bg-emerald-500' : 'bg-slate-400'"
                  ></span>
                  {{ coupon.is_active ? 'Hoạt động' : 'Đã tắt' }}
                </span>
              </td>

              <!-- Hành động -->
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <!-- Edit -->
                  <button
                    @click="openEditModal(coupon)"
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
                    @click="confirmDelete(coupon)"
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
            <tr v-if="!couponStore.loading && couponStore.coupons.length === 0">
              <td colspan="9" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy mã giảm giá nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="couponStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="couponStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="couponStore.meta.total"
          :lastPage="couponStore.meta.last_page"
          :loading="couponStore.loading"
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
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[600px] animate-modal-in flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
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
                  {{ modalMode === 'add' ? 'Tạo mã giảm giá mới' : 'Chỉnh sửa mã giảm giá' }}
                </h2>
              </div>
              <button @click="closeFormModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-7 py-6 overflow-y-auto space-y-5">

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

              <!-- Row: Mã code -->
              <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                  Mã code <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                  <input
                    id="input-coupon-code"
                    v-model="form.code"
                    type="text"
                    placeholder="VD: SUMMER20"
                    class="flex-1 px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 font-mono uppercase"
                    :class="fieldError('code') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                    @input="form.code = form.code.toUpperCase()"
                  />
                  <button
                    type="button"
                    @click="generateCode"
                    class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 text-xs font-semibold transition-all duration-150 whitespace-nowrap"
                    title="Tạo mã ngẫu nhiên"
                  >
                    Tạo ngẫu nhiên
                  </button>
                </div>
                <p v-if="fieldError('code')" class="text-xs text-red-500 mt-1">{{ fieldError('code') }}</p>
              </div>

              <!-- Row: Loại & Giá trị -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Loại giảm giá -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                    Loại giảm giá <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <select
                      id="select-coupon-type"
                      v-model="form.type"
                      class="w-full appearance-none px-3.5 py-2.5 text-sm border rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                      :class="fieldError('type') ? 'border-red-400' : 'border-slate-200'"
                    >
                      <option value="percent">Phần trăm (%)</option>
                      <option value="fixed">Số tiền cố định (₫)</option>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>
                  <p v-if="fieldError('type')" class="text-xs text-red-500 mt-1">{{ fieldError('type') }}</p>
                </div>

                <!-- Giá trị giảm -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                    Giá trị giảm <span class="text-red-500">*</span>
                    <span class="font-normal text-slate-400 ml-1">{{ form.type === 'percent' ? '(%)' : '(₫)' }}</span>
                  </label>
                  <input
                    id="input-coupon-discount-value"
                    v-model.number="form.discount_value"
                    type="number"
                    :min="0"
                    :max="form.type === 'percent' ? 100 : undefined"
                    :placeholder="form.type === 'percent' ? 'VD: 20' : 'VD: 50000'"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('discount_value') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('discount_value')" class="text-xs text-red-500 mt-1">{{ fieldError('discount_value') }}</p>
                </div>
              </div>

              <!-- Row: Đơn tối thiểu & Số lần dùng tối đa -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Đơn hàng tối thiểu -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                    Đơn hàng tối thiểu (₫)
                  </label>
                  <input
                    id="input-coupon-min-order"
                    v-model.number="form.price_min_order_value"
                    type="number"
                    min="0"
                    placeholder="VD: 200000 (để trống = không giới hạn)"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('price_min_order_value') ? 'border-red-400' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('price_min_order_value')" class="text-xs text-red-500 mt-1">{{ fieldError('price_min_order_value') }}</p>
                </div>

                <!-- Số lần dùng tối đa -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                    Số lần dùng tối đa
                  </label>
                  <input
                    id="input-coupon-max-usage"
                    v-model.number="form.max_usage"
                    type="number"
                    min="1"
                    placeholder="Để trống = không giới hạn"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('max_usage') ? 'border-red-400' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('max_usage')" class="text-xs text-red-500 mt-1">{{ fieldError('max_usage') }}</p>
                </div>
              </div>

              <!-- Row: Ngày hết hạn & Trạng thái -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Ngày hết hạn -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">Ngày hết hạn</label>
                  <input
                    id="input-coupon-expiry"
                    v-model="form.expiry_date"
                    type="date"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('expiry_date') ? 'border-red-400' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('expiry_date')" class="text-xs text-red-500 mt-1">{{ fieldError('expiry_date') }}</p>
                  <p class="text-xs text-slate-400 mt-1">Để trống = không giới hạn thời gian</p>
                </div>

                <!-- Trạng thái -->
                <div>
                  <label class="block text-sm font-semibold text-slate-600 mb-1.5">Trạng thái</label>
                  <div class="relative">
                    <select
                      id="select-coupon-status"
                      v-model="form.is_active"
                      class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                    >
                      <option :value="true">Hoạt động</option>
                      <option :value="false">Tắt</option>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Preview Card -->
              <div v-if="form.code" class="p-4 bg-gradient-to-r from-blue-50 to-violet-50 border border-blue-100 rounded-xl">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Xem trước mã</p>
                <div class="flex items-center gap-3">
                  <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border-2 border-dashed border-blue-300 font-mono text-lg font-bold text-[#0258cb] tracking-widest shadow-sm">
                    {{ form.code }}
                  </span>
                  <div class="text-sm text-slate-600">
                    <span v-if="form.discount_value">
                      Giảm <strong>{{ form.type === 'percent' ? form.discount_value + '%' : formatCurrency(form.discount_value) }}</strong>
                    </span>
                    <span v-if="form.price_min_order_value" class="text-slate-400"> · Đơn từ {{ formatCurrency(form.price_min_order_value) }}</span>
                  </div>
                </div>
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
                id="btn-submit-coupon"
                @click="submitForm"
                :disabled="formSubmitting"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                {{ formSubmitting ? 'Đang lưu...' : (modalMode === 'add' ? 'Tạo mã giảm giá' : 'Lưu thay đổi') }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ========== CONFIRM DELETE MODAL ========== -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa mã giảm giá"
      message="Bạn có chắc chắn muốn xóa mã"
      :itemName="deleteTarget?.code ?? ''"
      messageSuffix="không? Mã sẽ bị xóa hoàn toàn và không thể khôi phục."
      confirmLabel="Xóa mã giảm giá"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

    <!-- Toast copy -->
    <Teleport to="body">
      <Transition name="toast-fade">
        <div
          v-if="showCopyToast"
          class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[99999] flex items-center gap-2 px-5 py-3 bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-xl"
        >
          <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          Đã sao chép mã: {{ copiedCode }}
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { useCouponStore } from '@/stores/admin/couponStore'
import Pagination from '@/components/admin/Pagination.vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

const couponStore = useCouponStore()

// ─── Mounted ──────────────────────────────────────────────────────────────────
onMounted(() => {
  couponStore.fetchCoupons()
})

// ─── Stats ────────────────────────────────────────────────────────────────────
const activeCount = computed(() => couponStore.coupons.filter(c => c.is_active).length)
const inactiveCount = computed(() => couponStore.coupons.filter(c => !c.is_active || isExpired(c.expiry_date)).length)
const percentCount = computed(() => couponStore.coupons.filter(c => c.type === 'percent').length)

// ─── Helpers ──────────────────────────────────────────────────────────────────
const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  try {
    return new Date(dateStr).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
  } catch {
    return dateStr
  }
}

const formatCurrency = (value) => {
  if (value == null) return '—'
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
}

const isExpired = (dateStr) => {
  if (!dateStr) return false
  return new Date(dateStr) < new Date()
}

const usagePercent = (coupon) => {
  if (!coupon.max_usage) return 0
  return Math.min(Math.round((coupon.used_count / coupon.max_usage) * 100), 100)
}

// ─── Copy to clipboard ────────────────────────────────────────────────────────
const showCopyToast = ref(false)
const copiedCode = ref('')
let copyTimer = null

const copyCode = (code) => {
  navigator.clipboard.writeText(code).then(() => {
    copiedCode.value = code
    showCopyToast.value = true
    clearTimeout(copyTimer)
    copyTimer = setTimeout(() => { showCopyToast.value = false }, 2000)
  })
}

// ─── Generate random code ─────────────────────────────────────────────────────
const generateCode = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  let code = ''
  for (let i = 0; i < 8; i++) {
    code += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  form.code = code
}

// ─── Search & Filter ──────────────────────────────────────────────────────────
const searchQuery = ref('')
const filterType = ref('')
const filterStatus = ref('')
let searchTimer = null

const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    couponStore.fetchCoupons({
      search: searchQuery.value,
      type: filterType.value,
      is_active: filterStatus.value,
      page: 1,
    })
  }, 400)
}

const onFilterChange = () => {
  couponStore.fetchCoupons({
    search: searchQuery.value,
    type: filterType.value,
    is_active: filterStatus.value,
    page: 1,
  })
}

// ─── Pagination ───────────────────────────────────────────────────────────────
const goToPage = (page) => {
  couponStore.fetchCoupons({ search: searchQuery.value, type: filterType.value, is_active: filterStatus.value, page })
}

const handlePerPageChange = (newPerPage) => {
  couponStore.meta.per_page = newPerPage
  couponStore.fetchCoupons({ search: searchQuery.value, type: filterType.value, is_active: filterStatus.value, page: 1 })
}

// ─── Add / Edit Modal ─────────────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const formSubmitting = ref(false)
const formServerError = ref('')
const formErrors = reactive({})

const form = reactive({
  id: null,
  code: '',
  type: 'percent',
  discount_value: '',
  price_min_order_value: '',
  max_usage: '',
  is_active: true,
  expiry_date: '',
})

const resetForm = () => {
  form.id = null
  form.code = ''
  form.type = 'percent'
  form.discount_value = ''
  form.price_min_order_value = ''
  form.max_usage = ''
  form.is_active = true
  form.expiry_date = ''
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])
}

const fieldError = (field) => formErrors[field]?.[0] ?? ''

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (coupon) => {
  resetForm()
  form.id = coupon.id
  form.code = coupon.code || ''
  form.type = coupon.type || 'percent'
  form.discount_value = coupon.discount_value ?? ''
  form.price_min_order_value = coupon.price_min_order_value ?? ''
  form.max_usage = coupon.max_usage ?? ''
  form.is_active = !!coupon.is_active
  // Normalize date: keep only YYYY-MM-DD
  form.expiry_date = coupon.expiry_date ? coupon.expiry_date.substring(0, 10) : ''
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

  // Client-side validation
  if (!form.code.trim()) { formErrors.code = ['Mã code không được để trống.']; return }
  if (!form.discount_value && form.discount_value !== 0) { formErrors.discount_value = ['Giá trị giảm không được để trống.']; return }
  if (form.type === 'percent' && (form.discount_value < 0 || form.discount_value > 100)) {
    formErrors.discount_value = ['Phần trăm giảm phải từ 0 đến 100.']; return
  }

  formSubmitting.value = true
  try {
    const payload = {
      code: form.code.trim().toUpperCase(),
      type: form.type,
      discount_value: Number(form.discount_value),
      price_min_order_value: form.price_min_order_value !== '' ? Number(form.price_min_order_value) : null,
      max_usage: form.max_usage !== '' ? Number(form.max_usage) : null,
      is_active: form.is_active,
      expiry_date: form.expiry_date || null,
    }

    if (modalMode.value === 'add') {
      await couponStore.createCoupon(payload)
    } else {
      await couponStore.updateCoupon(form.id, payload)
    }
    showFormModal.value = false
  } catch (e) {
    if (e.errors) {
      Object.assign(formErrors, e.errors)
    } else {
      formServerError.value = e.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.'
    }
  } finally {
    formSubmitting.value = false
  }
}

// ─── Delete Modal ─────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (coupon) => {
  deleteTarget.value = coupon
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await couponStore.deleteCoupon(deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
    console.error('Delete coupon error:', e)
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

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.2s ease;
}
.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(12px);
}

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
