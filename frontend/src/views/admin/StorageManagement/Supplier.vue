<template>
  <div class="space-y-6">

    <!-- ══════════════════════ PAGE HEADER ══════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Nhà phân phối</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý toàn bộ nhà phân phối / nhà cung cấp trong hệ thống</p>
      </div>
      <button
        id="btn-open-add-supplier"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm nhà phân phối mới
      </button>
    </div>

    <!-- ══════════════════════ STATS CARDS ══════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Total -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng nhà phân phối</p>
          <p class="text-3xl font-bold text-slate-800">{{ totalSuppliers }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
            <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
          </svg>
        </div>
      </div>
      <!-- Active -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hợp tác</p>
          <p class="text-3xl font-bold text-slate-800">{{ activeSuppliers }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>
      <!-- Paused -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tạm dừng</p>
          <p class="text-3xl font-bold text-slate-800">{{ pausedSuppliers }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- ══════════════════════ ERROR BANNER ══════════════════════ -->
    <div
      v-if="globalError"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ globalError }}
    </div>

    <!-- ══════════════════════ TABLE CARD ══════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <p class="text-base font-bold text-slate-800 mr-2">Danh sách nhà phân phối</p>
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-lg border border-emerald-100">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          ACTIVE NOW
        </span>
        <div class="flex-1"></div>
        <!-- Search -->
        <div class="relative flex items-center min-w-[220px] max-w-xs">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            id="search-supplier"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm tên, SĐT, email..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <!-- Filter Status -->
        <select
          v-model="filterStatus"
          @change="onSearch"
          class="px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        >
          <option value="">Tất cả trạng thái</option>
          <option value="active">Đang hợp tác</option>
          <option value="paused">Tạm dừng</option>
        </select>
        <!-- Export -->
        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Xuất file
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16"></th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên nhà phân phối</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Liên hệ (SĐT / Email)</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Địa chỉ</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Thao tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <template v-if="loading">
              <tr v-for="i in 5" :key="'sk-'+i" class="animate-pulse">
                <td class="py-4 px-5"><div class="w-10 h-10 bg-slate-200 rounded-xl"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-36 mb-2"></div><div class="h-3 bg-slate-100 rounded w-20"></div></td>
                <td class="py-4 px-4"><div class="h-3 bg-slate-200 rounded w-28 mb-2"></div><div class="h-3 bg-slate-100 rounded w-36"></div></td>
                <td class="py-4 px-4"><div class="h-3 bg-slate-200 rounded w-48"></div></td>
                <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-24"></div></td>
                <td class="py-4 px-4"><div class="flex justify-end gap-2"><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div></div></td>
              </tr>
            </template>

            <!-- Rows -->
            <template v-else>
              <tr
                v-for="sup in filteredSuppliers"
                :key="sup.id"
                class="hover:bg-blue-50/40 transition-colors duration-100 group"
              >
                <!-- Avatar -->
                <td class="py-4 px-5">
                  <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-white shrink-0"
                    :class="avatarColor(sup.id)"
                  >
                    {{ initials(sup.name) }}
                  </div>
                </td>
                <!-- Name + ID -->
                <td class="py-4 px-4">
                  <p class="font-semibold text-slate-800">{{ sup.name }}</p>
                  <p class="text-xs text-slate-400 font-mono mt-0.5">ID: {{ sup.code }}</p>
                </td>
                <!-- Contact -->
                <td class="py-4 px-4">
                  <p class="text-slate-700">{{ sup.phone }}</p>
                  <p class="text-xs text-slate-400 mt-0.5">{{ sup.email }}</p>
                </td>
                <!-- Address -->
                <td class="py-4 px-4 text-slate-500 max-w-[220px]">
                  <span class="line-clamp-1 text-sm">{{ sup.address || '—' }}</span>
                </td>
                <!-- Status -->
                <td class="py-4 px-4">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                    :class="sup.is_active
                      ? 'bg-emerald-50 text-emerald-600 border border-emerald-100'
                      : 'bg-red-50 text-red-500 border border-red-100'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="sup.is_active ? 'bg-emerald-500' : 'bg-red-400'"></span>
                    {{ sup.is_active ? 'Đang hợp tác' : 'Tạm dừng' }}
                  </span>
                </td>
                <!-- Actions -->
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <!-- View -->
                    <button
                      @click="openViewModal(sup)"
                      class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                      title="Xem chi tiết"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                    <!-- Edit -->
                    <button
                      @click="openEditModal(sup)"
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
                      @click="confirmDelete(sup)"
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

            <!-- Empty -->
            <tr v-if="!loading && filteredSuppliers.length === 0">
              <td colspan="6" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy nhà phân phối nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="px-5 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
        <p class="text-xs text-slate-500">
          Hiển thị {{ filteredSuppliers.length }} / {{ totalSuppliers }} nhà phân phối
        </p>
        <div class="flex items-center gap-1">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            class="w-8 h-8 rounded-lg text-sm font-semibold transition-all duration-150"
            :class="currentPage === page
              ? 'bg-[#0258cb] text-white shadow-sm'
              : 'text-slate-500 hover:bg-slate-100'"
          >{{ page }}</button>
        </div>
      </div>
    </div>

    <!-- ══════════════════════ ADD / EDIT MODAL ══════════════════════ -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showFormModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="closeFormModal"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[540px] animate-modal-in flex flex-col max-h-[90vh]">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" :class="modalMode === 'add' ? 'bg-blue-50' : 'bg-amber-50'">
                  <svg class="w-5 h-5" :class="modalMode === 'add' ? 'text-[#0258cb]' : 'text-amber-500'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <template v-if="modalMode === 'add'">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </template>
                    <template v-else>
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </template>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">
                  {{ modalMode === 'add' ? 'Thêm mới nhà phân phối' : 'Chỉnh sửa nhà phân phối' }}
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

              <!-- Server error -->
              <div v-if="formServerError" class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ formServerError }}
              </div>

              <!-- Tên nhà phân phối -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                  Tên nhà phân phối <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-supplier-name"
                  v-model="form.name"
                  type="text"
                  placeholder="Vd: Cty TNHH TechPro"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                  :class="fieldError('name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('name')" class="text-xs text-red-500 mt-1">{{ fieldError('name') }}</p>
              </div>

              <!-- Người liên hệ -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Người liên hệ</label>
                <input
                  id="input-supplier-contact-name"
                  v-model="form.contact_name"
                  type="text"
                  placeholder="Vd: Nguyễn Văn A"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                />
              </div>

              <!-- Phone + Email -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Số điện thoại <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="input-supplier-phone"
                    v-model="form.phone"
                    type="text"
                    placeholder="0987 123 456"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('phone') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('phone')" class="text-xs text-red-500 mt-1">{{ fieldError('phone') }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                  <input
                    id="input-supplier-email"
                    v-model="form.email"
                    type="email"
                    placeholder="contact@company.vn"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                  />
                </div>
              </div>

              <!-- Địa chỉ -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Địa chỉ</label>
                <textarea
                  id="input-supplier-address"
                  v-model="form.address"
                  rows="2"
                  placeholder="123 Đường Láng, Đống Đa, Hà Nội"
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 resize-none"
                ></textarea>
              </div>

              <!-- Ghi chú -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ghi chú</label>
                <textarea
                  id="input-supplier-note"
                  v-model="form.note"
                  rows="2"
                  placeholder="Ghi chú thêm về nhà phân phối..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 resize-none"
                ></textarea>
              </div>

              <!-- Trạng thái -->
              <div class="flex items-center justify-between py-3 px-4 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                  <p class="text-sm font-semibold text-slate-700">Trạng thái hoạt động</p>
                  <p class="text-xs text-slate-400 mt-0.5">Cho phép nhà phân phối tham gia vào hệ thống.</p>
                </div>
                <button
                  type="button"
                  @click="form.is_active = !form.is_active"
                  class="relative inline-flex w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none"
                  :class="form.is_active ? 'bg-[#0258cb]' : 'bg-slate-300'"
                >
                  <span
                    class="inline-block w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-200 mt-0.5"
                    :class="form.is_active ? 'translate-x-5' : 'translate-x-0.5'"
                  ></span>
                </button>
              </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button
                @click="closeFormModal"
                :disabled="formSubmitting"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150 disabled:opacity-50"
              >
                Hủy bỏ
              </button>
              <button
                @click="submitForm"
                :disabled="formSubmitting"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 disabled:opacity-60 active:scale-[0.98]"
              >
                <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                  <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                {{ formSubmitting ? 'Đang lưu...' : 'Lưu thay đổi' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ══════════════════════ VIEW DETAIL MODAL ══════════════════════ -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showViewModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showViewModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[680px] animate-modal-in flex flex-col max-h-[90vh]">

            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Chi tiết Nhà phân phối</h2>
                <p class="text-xs text-slate-400 mt-0.5">Thông tin chi tiết và lịch sử sản phẩm</p>
              </div>
              <button @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <div class="px-7 py-5 overflow-y-auto space-y-5">

              <!-- Supplier Info Card -->
              <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div
                      class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-bold text-white shrink-0"
                      :class="avatarColor(viewTarget?.id)"
                    >
                      {{ initials(viewTarget?.name) }}
                    </div>
                    <div>
                      <h3 class="text-lg font-bold text-slate-800">{{ viewTarget?.name }}</h3>
                      <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="text-xs text-slate-400 font-mono">{{ viewTarget?.code }}</span>
                        <span
                          class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                          :class="viewTarget?.is_active
                            ? 'bg-emerald-50 text-emerald-600 border border-emerald-100'
                            : 'bg-red-50 text-red-500 border border-red-100'"
                        >
                          <span class="w-1.5 h-1.5 rounded-full" :class="viewTarget?.is_active ? 'bg-emerald-500' : 'bg-red-400'"></span>
                          {{ viewTarget?.is_active ? 'Đang hợp tác' : 'Tạm dừng' }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <button
                    @click="openEditModal(viewTarget); showViewModal = false"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-semibold hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 shrink-0"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Chỉnh sửa
                  </button>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-x-8 gap-y-4 mt-5">
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                      Người liên hệ
                    </p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.contact_name || '—' }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                      Email
                    </p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.email || '—' }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                      Điện thoại
                    </p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.phone || '—' }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                      Địa chỉ
                    </p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.address || '—' }}</p>
                  </div>
                  <div v-if="viewTarget?.note" class="col-span-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ghi chú</p>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ viewTarget.note }}</p>
                  </div>
                </div>
              </div>

              <!-- Product List -->
              <div>
                <div class="flex items-center justify-between mb-3">
                  <p class="text-sm font-bold text-slate-700">Sản phẩm cung cấp</p>
                  <span class="px-2.5 py-1 bg-blue-50 text-[#0258cb] text-xs font-bold rounded-lg border border-blue-100">
                    {{ viewTarget?.products?.length || 0 }} sản phẩm
                  </span>
                </div>
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên sản phẩm</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Danh mục</th>
                        <th class="py-2.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Giá nhập</th>
                        <th class="py-2.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đã nhập</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                      <tr v-if="!viewTarget?.products?.length">
                        <td colspan="5" class="py-8 text-center text-sm text-slate-400">Chưa có sản phẩm nào</td>
                      </tr>
                      <tr
                        v-for="prod in viewTarget?.products"
                        :key="prod.id"
                        class="hover:bg-blue-50/30 transition-colors duration-100"
                      >
                        <td class="py-3 px-4">
                          <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                              <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                              </svg>
                            </div>
                            <span class="font-medium text-slate-800">{{ prod.name }}</span>
                          </div>
                        </td>
                        <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ prod.sku }}</td>
                        <td class="py-3 px-4">
                          <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold px-2 py-0.5 rounded-md">{{ prod.category }}</span>
                        </td>
                        <td class="py-3 px-4 text-right text-slate-700 font-medium">{{ formatPrice(prod.import_price) }}</td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-500">{{ prod.imported_qty }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
                Đóng
              </button>
              <button
                @click="openEditModal(viewTarget); showViewModal = false"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]"
              >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Chỉnh sửa
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ══════════════════════ CONFIRM DELETE MODAL ══════════════════════ -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showDeleteModal"
          class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
          @click.self="showDeleteModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[420px] animate-modal-in">
            <div class="p-7 text-center">
              <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">Xóa nhà phân phối</h3>
              <p class="text-sm text-slate-500 leading-relaxed">
                Bạn có chắc chắn muốn xóa
                <span class="font-semibold text-slate-700">{{ deleteTarget?.name }}</span>
                không? Hành động này không thể hoàn tác.
              </p>
            </div>
            <div class="flex items-center gap-3 px-7 pb-7">
              <button
                @click="showDeleteModal = false"
                class="flex-1 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
              >
                Hủy bỏ
              </button>
              <button
                @click="executeDelete"
                class="flex-1 px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]"
              >
                Xóa
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

// ─── Mock data (thay bằng API thực tế) ───────────────────────────────────────
const mockSuppliers = [
  {
    id: 1, code: 'SUP-001', name: 'Cty TNHH TechPro', contact_name: 'Nguyễn Văn A',
    phone: '0987 123 456', email: 'contact@techpro.vn',
    address: '123 Đường Láng, Đống Đa, Hà Nội', note: '', is_active: true,
    products: [
      { id: 1, name: 'Bàn phím cơ Keychron K2', sku: 'KEY-K2-01', category: 'Phụ kiện', import_price: 1250000, imported_qty: 45 },
      { id: 2, name: 'Chuột Logitech MX Master 3', sku: 'LOG-MX3-BK', category: 'Phụ kiện', import_price: 1890000, imported_qty: 22 },
      { id: 3, name: 'Màn hình Dell UltraSharp U2720Q', sku: 'DEL-U2720Q', category: 'Màn hình', import_price: 11500000, imported_qty: 22 },
      { id: 4, name: 'Tai nghe Sony WH-1000XM4', sku: 'SON-WH4-S', category: 'Âm thanh', import_price: 5200000, imported_qty: 18 },
    ],
  },
  {
    id: 2, code: 'SUP-002', name: 'Green Logistics JSC', contact_name: 'Trần Thị B',
    phone: '0912 987 654', email: 'info@greenlog.com',
    address: '456 Lê Lợi, Quận 1, TP. HCM', note: 'Ưu tiên giao hàng nhanh.', is_active: true,
    products: [],
  },
  {
    id: 3, code: 'SUP-003', name: 'Vina Packaging', contact_name: 'Lê Văn C',
    phone: '0243 555 888', email: 'sales@vinapack.vn',
    address: 'KCN Quang Minh, Mê Linh, Hà Nội', note: '', is_active: false,
    products: [],
  },
  {
    id: 4, code: 'SUP-004', name: 'Global Mart Supply', contact_name: 'Phạm Thị D',
    phone: '0888 444 222', email: 'support@globalmart.com',
    address: '789 Nguyễn Huệ, Quận 1, TP. HCM', note: '', is_active: true,
    products: [],
  },
]

// ─── State ───────────────────────────────────────────────────────────────────
const suppliers = ref([])
const loading = ref(false)
const globalError = ref('')
const searchQuery = ref('')
const filterStatus = ref('')
const currentPage = ref(1)
const perPage = 10

// ─── Computed ─────────────────────────────────────────────────────────────────
const filteredSuppliers = computed(() => {
  let list = suppliers.value
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(s =>
      s.name.toLowerCase().includes(q) ||
      s.phone.includes(q) ||
      (s.email || '').toLowerCase().includes(q)
    )
  }
  if (filterStatus.value === 'active') list = list.filter(s => s.is_active)
  if (filterStatus.value === 'paused') list = list.filter(s => !s.is_active)
  return list
})

const totalSuppliers = computed(() => suppliers.value.length)
const activeSuppliers = computed(() => suppliers.value.filter(s => s.is_active).length)
const pausedSuppliers = computed(() => suppliers.value.filter(s => !s.is_active).length)
const totalPages = computed(() => Math.max(1, Math.ceil(filteredSuppliers.value.length / perPage)))

// ─── Helpers ─────────────────────────────────────────────────────────────────
const avatarColors = [
  'bg-blue-500', 'bg-violet-500', 'bg-rose-500', 'bg-amber-500',
  'bg-teal-500', 'bg-indigo-500', 'bg-pink-500', 'bg-cyan-500',
]
const avatarColor = (id) => avatarColors[(id - 1) % avatarColors.length]

const initials = (name = '') =>
  name.split(' ').slice(-2).map(w => w[0]?.toUpperCase() ?? '').join('')

const formatPrice = (val) =>
  new Intl.NumberFormat('vi-VN').format(val) + ' đ'

// ─── Search debounce ──────────────────────────────────────────────────────────
let searchTimer = null
const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { currentPage.value = 1 }, 300)
}

// ─── Load data ────────────────────────────────────────────────────────────────
onMounted(() => {
  loading.value = true
  setTimeout(() => {
    suppliers.value = mockSuppliers
    loading.value = false
  }, 600)
})

// ─── Form Modal ───────────────────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add')
const formSubmitting = ref(false)
const formServerError = ref('')
const formErrors = reactive({})

const form = reactive({
  id: null,
  name: '',
  contact_name: '',
  phone: '',
  email: '',
  address: '',
  note: '',
  is_active: true,
})

const resetForm = () => {
  form.id = null
  form.name = ''
  form.contact_name = ''
  form.phone = ''
  form.email = ''
  form.address = ''
  form.note = ''
  form.is_active = true
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])
}

const fieldError = (field) => formErrors[field]?.[0] ?? ''

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (sup) => {
  resetForm()
  form.id = sup.id
  form.name = sup.name
  form.contact_name = sup.contact_name ?? ''
  form.phone = sup.phone ?? ''
  form.email = sup.email ?? ''
  form.address = sup.address ?? ''
  form.note = sup.note ?? ''
  form.is_active = sup.is_active
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

  if (!form.name.trim()) { formErrors.name = ['Tên nhà phân phối không được để trống.']; return }
  if (!form.phone.trim()) { formErrors.phone = ['Số điện thoại không được để trống.']; return }

  formSubmitting.value = true
  try {
    await new Promise(r => setTimeout(r, 800)) // Giả lập API call

    const payload = {
      id: form.id ?? Date.now(),
      code: form.id ? suppliers.value.find(s => s.id === form.id)?.code : `SUP-${String(suppliers.value.length + 1).padStart(3, '0')}`,
      name: form.name.trim(),
      contact_name: form.contact_name.trim(),
      phone: form.phone.trim(),
      email: form.email.trim(),
      address: form.address.trim(),
      note: form.note.trim(),
      is_active: form.is_active,
      products: form.id ? suppliers.value.find(s => s.id === form.id)?.products ?? [] : [],
    }

    if (modalMode.value === 'add') {
      suppliers.value.unshift(payload)
    } else {
      const idx = suppliers.value.findIndex(s => s.id === form.id)
      if (idx !== -1) suppliers.value[idx] = payload
    }

    showFormModal.value = false
  } catch (e) {
    formServerError.value = e.message ?? 'Đã có lỗi xảy ra.'
  } finally {
    formSubmitting.value = false
  }
}

// ─── View Modal ───────────────────────────────────────────────────────────────
const showViewModal = ref(false)
const viewTarget = ref(null)
const openViewModal = (sup) => { viewTarget.value = sup; showViewModal.value = true }

// ─── Delete Modal ─────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (sup) => {
  deleteTarget.value = sup
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await new Promise(r => setTimeout(r, 400))
    suppliers.value = suppliers.value.filter(s => s.id !== deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
    globalError.value = e.message ?? 'Xóa thất bại.'
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
