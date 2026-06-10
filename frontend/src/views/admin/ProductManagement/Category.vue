<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Danh mục</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý toàn bộ danh mục sản phẩm trong hệ thống</p>
      </div>
      <button
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
          <p class="text-3xl font-bold text-slate-800">{{ stats.total }}</p>
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
          <p class="text-3xl font-bold text-slate-800">{{ stats.parent }}</p>
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
          <p class="text-3xl font-bold text-slate-800">{{ stats.child }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
      </div>
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
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo tên hoặc slug..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>

        <!-- Filter Level -->
        <div class="relative">
          <select
            v-model="filterLevel"
            class="appearance-none pl-4 pr-10 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer"
          >
            <option value="">Lọc theo cấp độ</option>
            <option value="parent">Danh mục cha</option>
            <option value="child">Danh mục con</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>

        <div class="ml-auto flex items-center gap-2">
          <!-- Filter icon btn -->
          <button class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0258cb] hover:border-[#0258cb] hover:bg-blue-50 transition-all duration-150">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
          </button>
          <!-- Export icon btn -->
          <button class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0258cb] hover:border-[#0258cb] hover:bg-blue-50 transition-all duration-150">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[80px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên danh mục</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Danh mục cha</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mô tả</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr
              v-for="cat in paginatedCategories"
              :key="cat.id"
              class="hover:bg-blue-50/40 transition-colors duration-100 group"
            >
              <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ cat.id }}</td>
              <td class="py-4 px-4 font-semibold text-slate-800">{{ cat.name }}</td>
              <td class="py-4 px-4">
                <span class="inline-block bg-slate-100 text-slate-600 text-xs font-mono px-2.5 py-1 rounded-lg max-w-[160px] truncate">{{ cat.slug }}</span>
              </td>
              <td class="py-4 px-4">
                <span v-if="cat.parentId" class="inline-block bg-blue-50 text-[#0258cb] text-xs font-semibold px-2.5 py-1 rounded-lg max-w-[110px] truncate">
                  {{ getCategoryName(cat.parentId) }}
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
            <!-- Empty state -->
            <tr v-if="paginatedCategories.length === 0">
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
      <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-wrap gap-3">
        <p class="text-xs text-slate-500">
          Hiển thị <span class="font-semibold text-slate-700">{{ paginationFrom }}–{{ paginationTo }}</span> trong tổng số <span class="font-semibold text-slate-700">{{ filteredCategories.length }}</span> danh mục
        </p>
        <div class="flex items-center gap-1">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 disabled:cursor-not-allowed text-sm"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button
            v-for="page in displayedPages"
            :key="page"
            @click="page !== '...' && (currentPage = page)"
            :class="[
              'w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold transition-all duration-150',
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
            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 disabled:cursor-not-allowed text-sm"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
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
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[520px] animate-modal-in flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line v-if="modalMode === 'add'" x1="12" y1="5" x2="12" y2="19"/><line v-if="modalMode === 'add'" x1="5" y1="12" x2="19" y2="12"/>
                    <path v-if="modalMode === 'edit'" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path v-if="modalMode === 'edit'" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">
                  {{ modalMode === 'add' ? 'Thêm danh mục mới' : 'Chỉnh sửa danh mục' }}
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
              <!-- Row: Name + Slug -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Tên danh mục <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.name"
                    @input="autoSlug"
                    type="text"
                    placeholder="Vd: Điện gia dụng"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="{ 'border-red-400 focus:border-red-400 focus:ring-red-100': formErrors.name }"
                  />
                  <p v-if="formErrors.name" class="text-xs text-red-500 mt-1">{{ formErrors.name }}</p>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Đường dẫn (Slug)</label>
                  <input
                    v-model="form.slug"
                    type="text"
                    placeholder="dien-gia-dung"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 font-mono"
                  />
                </div>
              </div>

              <!-- Parent Category -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Danh mục cha</label>
                <div class="relative">
                  <select
                    v-model="form.parentId"
                    class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                  >
                    <option :value="null">-- Chọn danh mục cha (Để trống nếu là gốc) --</option>
                    <option
                      v-for="cat in rootCategories"
                      :key="cat.id"
                      :value="cat.id"
                      :disabled="modalMode === 'edit' && cat.id === form.id"
                    >{{ cat.name }}</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </span>
                </div>
              </div>

              <!-- Description -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả</label>
                <textarea
                  v-model="form.description"
                  rows="4"
                  placeholder="Nhập mô tả chi tiết về danh mục này để hỗ trợ SEO và hiển thị..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 resize-none leading-relaxed"
                ></textarea>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button
                @click="closeFormModal"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
              >Hủy</button>
              <button
                @click="submitForm"
                class="px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
              >{{ modalMode === 'add' ? 'Thêm danh mục' : 'Lưu thay đổi' }}</button>
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
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[480px] animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Chi tiết danh mục</h2>
              </div>
              <button @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>
            <!-- Content -->
            <div class="px-7 py-6 space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
                  <p class="text-sm font-mono font-semibold text-slate-700">{{ viewTarget?.id }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Danh mục cha</p>
                  <p class="text-sm font-semibold text-[#0258cb]">
                    {{ viewTarget?.parentId ? getCategoryName(viewTarget.parentId) : '—' }}
                  </p>
                </div>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tên danh mục</p>
                <p class="text-sm font-bold text-slate-800">{{ viewTarget?.name }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Slug</p>
                <p class="text-sm font-mono text-slate-600">{{ viewTarget?.slug }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mô tả</p>
                <p class="text-sm text-slate-600 leading-relaxed">{{ viewTarget?.description || 'Chưa có mô tả.' }}</p>
              </div>
            </div>
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
              <button @click="openEditModal(viewTarget); showViewModal = false" class="px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all duration-150">
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
      title="Xóa danh mục"
      message="Bạn có chắc chắn muốn xóa danh mục"
      :itemName="deleteTarget?.name"
      messageSuffix="không? Các danh mục con (nếu có) sẽ được chuyển về gốc."
      confirmLabel="Xóa danh mục"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'

// ======== Mock Data ========
const categories = ref([
  { id: 1001, name: 'Thiết bị điện tử', slug: 'thiet-bi-dien-tu', parentId: null, description: 'Các thiết bị công nghệ cao, máy tính và phụ kiện điện tử' },
  { id: 1002, name: 'Điện thoại di động', slug: 'dien-thoai-di-dong', parentId: 1001, description: 'Smartphone, điện thoại phổ thông và máy tính bảng' },
  { id: 1003, name: 'Laptop & Máy tính', slug: 'laptop-may-tinh', parentId: 1001, description: 'Máy tính xách tay, PC Gaming và máy tính văn phòng' },
  { id: 1004, name: 'Thời trang nam', slug: 'thoi-trang-nam', parentId: null, description: 'Quần áo, giày dép và phụ kiện thời trang dành cho nam' },
  { id: 1005, name: 'Nhà cửa & Đời sống', slug: 'nha-cua-doi-song', parentId: null, description: 'Đồ dùng gia đình, nội thất và trang trí nhà cửa' },
  { id: 1006, name: 'Áo thun nam', slug: 'ao-thun-nam', parentId: 1004, description: 'Các loại áo thun thời trang cho nam giới' },
  { id: 1007, name: 'Đồ gia dụng', slug: 'do-gia-dung', parentId: 1005, description: 'Các thiết bị và đồ dùng phục vụ sinh hoạt gia đình' },
  { id: 1008, name: 'Thời trang nữ', slug: 'thoi-trang-nu', parentId: null, description: 'Quần áo, giày dép và phụ kiện thời trang cho nữ' },
  { id: 1009, name: 'Mỹ phẩm & Làm đẹp', slug: 'my-pham-lam-dep', parentId: null, description: 'Sản phẩm chăm sóc sắc đẹp, da và tóc' },
  { id: 1010, name: 'Thể thao & Dã ngoại', slug: 'the-thao-da-ngoai', parentId: null, description: 'Dụng cụ thể thao, thiết bị dã ngoại và đồ outdoor' },
])

// ======== Stats ========
const stats = computed(() => ({
  total: categories.value.length,
  parent: categories.value.filter(c => !c.parentId).length,
  child: categories.value.filter(c => c.parentId).length,
}))

// ======== Filter & Search ========
const searchQuery = ref('')
const filterLevel = ref('')

const filteredCategories = computed(() => {
  return categories.value.filter(cat => {
    const matchSearch = !searchQuery.value ||
      cat.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      cat.slug.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchLevel =
      filterLevel.value === '' ||
      (filterLevel.value === 'parent' && !cat.parentId) ||
      (filterLevel.value === 'child' && !!cat.parentId)
    return matchSearch && matchLevel
  })
})

// ======== Pagination ========
const currentPage = ref(1)
const pageSize = 5

const totalPages = computed(() => Math.max(1, Math.ceil(filteredCategories.value.length / pageSize)))
const paginatedCategories = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredCategories.value.slice(start, start + pageSize)
})
const paginationFrom = computed(() => Math.min((currentPage.value - 1) * pageSize + 1, filteredCategories.value.length))
const paginationTo = computed(() => Math.min(currentPage.value * pageSize, filteredCategories.value.length))

const displayedPages = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)
  const pages = [1]
  if (current > 3) pages.push('...')
  for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i)
  if (current < total - 2) pages.push('...')
  pages.push(total)
  return pages
})

// ======== Helper ========
const getCategoryName = (id) => categories.value.find(c => c.id === id)?.name ?? '—'
const rootCategories = computed(() => categories.value.filter(c => !c.parentId))

const generateSlug = (text) =>
  text.toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
    .trim().replace(/\s+/g, '-')

// ======== Form Modal (Add/Edit) ========
const showFormModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const form = reactive({ id: null, name: '', slug: '', parentId: null, description: '' })
const formErrors = reactive({ name: '' })

const resetForm = () => {
  form.id = null; form.name = ''; form.slug = ''; form.parentId = null; form.description = ''
  formErrors.name = ''
}

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (cat) => {
  resetForm()
  Object.assign(form, { ...cat })
  modalMode.value = 'edit'
  showFormModal.value = true
}

const closeFormModal = () => { showFormModal.value = false }

const autoSlug = () => {
  if (modalMode.value === 'add') form.slug = generateSlug(form.name)
}

const validateForm = () => {
  formErrors.name = ''
  if (!form.name.trim()) { formErrors.name = 'Tên danh mục không được để trống.'; return false }
  return true
}

const submitForm = () => {
  if (!validateForm()) return
  if (!form.slug) form.slug = generateSlug(form.name)

  if (modalMode.value === 'add') {
    const newId = Math.max(...categories.value.map(c => c.id)) + 1
    categories.value.push({ id: newId, name: form.name, slug: form.slug, parentId: form.parentId, description: form.description })
  } else {
    const idx = categories.value.findIndex(c => c.id === form.id)
    if (idx !== -1) categories.value[idx] = { ...form }
  }
  closeFormModal()
}

// ======== View Modal ========
const showViewModal = ref(false)
const viewTarget = ref(null)
const openViewModal = (cat) => { viewTarget.value = cat; showViewModal.value = true }

// ======== Delete Modal ========
const showDeleteModal = ref(false)
const deleteTarget = ref(null)
const confirmDelete = (cat) => { deleteTarget.value = cat; showDeleteModal.value = true }
const executeDelete = () => {
  categories.value = categories.value.filter(c => c.id !== deleteTarget.value.id)
  showDeleteModal.value = false
  deleteTarget.value = null
  // Reset page if needed
  if (currentPage.value > totalPages.value) currentPage.value = totalPages.value
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
