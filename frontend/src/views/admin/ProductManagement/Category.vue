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
          <p class="text-3xl font-bold text-slate-800">{{ categoryStore.meta.total }}</p>
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
          <p class="text-3xl font-bold text-slate-800">{{ parentCount }}</p>
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
          <p class="text-3xl font-bold text-slate-800">{{ childCount }}</p>
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
            <tr v-if="categoryStore.loading" v-for="i in perPage" :key="'sk-'+i">
              <td colspan="6" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr>

            <!-- Rows -->
            <tr
              v-else
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

              <!-- Row: Name + Slug -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Tên danh mục <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="input-category-name"
                    v-model="form.name"
                    @input="autoSlug"
                    type="text"
                    placeholder="Vd: Thời trang nam"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="fieldError('name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('name')" class="text-xs text-red-500 mt-1">{{ fieldError('name') }}</p>
                </div>

                <!-- Slug -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Slug <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="input-category-slug"
                    v-model="form.slug"
                    type="text"
                    placeholder="thoi-trang-nam"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-600 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 font-mono"
                    :class="fieldError('slug') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                  />
                  <p v-if="fieldError('slug')" class="text-xs text-red-500 mt-1">{{ fieldError('slug') }}</p>
                </div>
              </div>

              <!-- Parent Category -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Danh mục cha</label>
                <div class="relative">
                  <select
                    id="select-category-parent"
                    v-model="form.parent_id"
                    class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                  >
                    <option :value="null">— Không có (Danh mục gốc) —</option>
                    <option
                      v-for="cat in filterParents"
                      :key="cat.id"
                      :value="cat.id"
                      :disabled="modalMode === 'edit' && cat.id === form.id"
                    >{{ cat.name }}</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                  </span>
                </div>
                <p v-if="fieldError('parent_id')" class="text-xs text-red-500 mt-1">{{ fieldError('parent_id') }}</p>
              </div>

              <!-- Description -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả</label>
                <textarea
                  id="textarea-category-description"
                  v-model="form.description"
                  rows="3"
                  placeholder="Nhập mô tả ngắn về danh mục này (tối đa 255 ký tự)..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 resize-none leading-relaxed"
                  :class="fieldError('description') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                ></textarea>
                <div class="flex items-center justify-between mt-1">
                  <p v-if="fieldError('description')" class="text-xs text-red-500">{{ fieldError('description') }}</p>
                  <p class="text-xs text-slate-400 ml-auto">{{ form.description?.length || 0 }}/255</p>
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
                id="btn-submit-category"
                @click="submitForm"
                :disabled="formSubmitting"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                {{ formSubmitting ? 'Đang lưu...' : (modalMode === 'add' ? 'Thêm danh mục' : 'Lưu thay đổi') }}
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
                    {{ viewTarget?.parent_id ? getCategoryName(viewTarget.parent_id) : '—' }}
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
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.created_at }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cập nhật</p>
                  <p class="text-xs text-slate-600">{{ viewTarget?.updated_at }}</p>
                </div>
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
      messageSuffix="không? Các danh mục con (nếu có) sẽ bị ảnh hưởng."
      confirmLabel="Xóa danh mục"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted, watch } from 'vue'
import { useCategoryStore } from '@/stores/admin/categoryStore'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'
import { categoryService } from '@/services/admin/categoryService'

const categoryStore = useCategoryStore()

// ─── Load dữ liệu khi mount ──────────────────────────────────────────────────
onMounted(() => {
  categoryStore.initialFetch()   // chỉ fetch nếu chưa có data
  fetchFilterParents()
})

// ─── Stats tính từ toàn bộ danh sách trang hiện tại ──────────────────────────
// (thống kê chính xác sẽ cần API riêng, ở đây dùng meta.total để hiển thị tổng)
const parentCount = computed(() =>
  categoryStore.categories.filter(c => !c.parent_id).length
)
const childCount = computed(() =>
  categoryStore.categories.filter(c => c.parent_id).length
)

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



// ─── Slug helper ─────────────────────────────────────────────────────────────
const generateSlug = (text) =>
  (text || '').toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
    .trim().replace(/\s+/g, '-')

// ─── Form Modal (Add / Edit) ─────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const formSubmitting = ref(false)
const formServerError = ref('')
const formErrors = reactive({}) // { name: '...', slug: '...', ... }

const form = reactive({
  id: null,
  name: '',
  slug: '',
  description: '',
  parent_id: null,
})

const resetForm = () => {
  form.id = null
  form.name = ''
  form.slug = ''
  form.description = ''
  form.parent_id = null
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])
}

const fieldError = (field) => formErrors[field]?.[0] ?? ''

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (cat) => {
  resetForm()
  form.id = cat.id
  form.name = cat.name
  form.slug = cat.slug
  form.description = cat.description ?? ''
  form.parent_id = cat.parent_id ?? null
  modalMode.value = 'edit'
  showFormModal.value = true
}

const closeFormModal = () => {
  if (formSubmitting.value) return
  showFormModal.value = false
}

const autoSlug = () => {
  if (modalMode.value === 'add') {
    form.slug = generateSlug(form.name)
  }
}

const submitForm = async () => {
  formServerError.value = ''
  Object.keys(formErrors).forEach(k => delete formErrors[k])

  // Client-side validation cơ bản
  if (!form.name.trim()) { formErrors.name = ['Tên danh mục không được để trống.']; return }
  if (!form.slug.trim()) { formErrors.slug = ['Slug không được để trống.']; return }

  formSubmitting.value = true
  try {
    const payload = {
      name: form.name.trim(),
      slug: form.slug.trim(),
      description: form.description?.trim() || null,
      parent_id: form.parent_id || null,
    }

    if (modalMode.value === 'add') {
      await categoryStore.createCategory(payload)
    } else {
      await categoryStore.updateCategory(form.id, payload)
    }

    fetchFilterParents()
    showFormModal.value = false
  } catch (e) {
    // Lỗi validation từ backend (422): e.errors = { name: [...], slug: [...] }
    if (e.errors) {
      Object.assign(formErrors, e.errors)
    } else {
      formServerError.value = e.message
    }
  } finally {
    formSubmitting.value = false
  }
}

// ─── View Modal ──────────────────────────────────────────────────────────────
const showViewModal = ref(false)
const viewTarget = ref(null)
const openViewModal = (cat) => { viewTarget.value = cat; showViewModal.value = true }

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
    // Lỗi xóa sẽ hiển thị qua categoryStore.error (banner ở đầu trang)
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
