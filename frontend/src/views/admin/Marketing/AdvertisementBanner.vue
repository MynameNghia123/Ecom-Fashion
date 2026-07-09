<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Banner quảng cáo</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý các banner hiển thị trên website</p>
      </div>
      <button
        id="btn-open-add-banner"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm banner mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng banner</p>
          <p class="text-3xl font-bold text-slate-800">{{ bannerStore.meta.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/>
          </svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang bật</p>
          <p class="text-3xl font-bold text-slate-800">{{ activeCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã tắt</p>
          <p class="text-3xl font-bold text-slate-800">{{ inactiveCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="bannerStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ bannerStore.error }}
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
            id="search-banner"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm theo tiêu đề..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <select
          v-model="filterPosition"
          @change="onFilterChange"
          class="py-2.5 px-4 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        >
          <option value="">Tất cả vị trí</option>
          <option value="home_hero">Trang chủ - Hero</option>
          <option value="home_middle">Trang chủ - Giữa trang</option>
          <option value="sidebar">Sidebar</option>
        </select>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[60px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Ảnh</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tiêu đề</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Vị trí</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[70px]">Thứ tự</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Hiệu lực</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[100px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <template v-if="bannerStore.loading">
              <tr v-for="i in bannerStore.meta.per_page" :key="i" class="animate-pulse">
                <td class="py-4 px-5"><div class="h-4 bg-slate-200 rounded w-8"></div></td>
                <td class="py-4 px-4"><div class="h-12 w-20 bg-slate-200 rounded-xl"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-40"></div></td>
                <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-24"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-8"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
                <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-16"></div></td>
                <td class="py-4 px-4">
                  <div class="flex justify-end gap-2">
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                  </div>
                </td>
              </tr>
            </template>

            <!-- Rows -->
            <template v-else>
              <tr
                v-for="banner in bannerStore.banners"
                :key="banner.id"
                class="hover:bg-blue-50/40 transition-colors duration-100 group"
              >
                <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ banner.id }}</td>
                <td class="py-3 px-4">
                  <div class="w-20 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center shrink-0">
                    <img
                      v-if="banner.image_url"
                      :src="banner.image_url"
                      :alt="banner.title"
                      class="w-full h-full object-cover"
                    />
                    <svg v-else class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                  </div>
                </td>
                <td class="py-4 px-4 max-w-[220px]">
                  <p class="font-semibold text-slate-800 line-clamp-1">{{ banner.title }}</p>
                  <a v-if="banner.target_url" :href="banner.target_url" target="_blank" class="text-xs text-[#0258cb] hover:underline truncate block max-w-[200px]">{{ banner.target_url }}</a>
                </td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                    {{ positionLabel(banner.position) }}
                  </span>
                </td>
                <td class="py-4 px-4 text-center">
                  <span class="text-sm font-bold text-slate-600">{{ banner.display_order }}</span>
                </td>
                <td class="py-4 px-4 text-xs text-slate-500">
                  <div v-if="banner.start_date || banner.end_date">
                    <span v-if="banner.start_date">{{ formatDate(banner.start_date) }}</span>
                    <span class="text-slate-300 mx-1">→</span>
                    <span v-if="banner.end_date">{{ formatDate(banner.end_date) }}</span>
                    <span v-else class="text-slate-400">∞</span>
                  </div>
                  <span v-else class="text-slate-400">Không giới hạn</span>
                </td>
                <td class="py-4 px-4">
                  <span
                    :class="banner.is_active
                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                      : 'bg-slate-100 text-slate-500 border border-slate-200'"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                  >
                    <span :class="banner.is_active ? 'bg-emerald-500' : 'bg-slate-400'" class="w-1.5 h-1.5 rounded-full"></span>
                    {{ banner.is_active ? 'Bật' : 'Tắt' }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <button
                      @click="openEditModal(banner)"
                      class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150"
                      title="Chỉnh sửa"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button
                      @click="confirmDelete(banner)"
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

            <!-- Empty state -->
            <tr v-if="!bannerStore.loading && bannerStore.banners.length === 0">
              <td colspan="8" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy banner nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="bannerStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="bannerStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="bannerStore.meta.total"
          :lastPage="bannerStore.meta.last_page"
          :loading="bannerStore.loading"
        />
      </div>
    </div>

    <!-- ========== ADD / EDIT MODAL ========== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showFormModal"
          class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
          @click.self="closeFormModal"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
              <h2 class="text-lg font-bold text-slate-800">
                {{ modalMode === 'add' ? 'Thêm banner mới' : 'Chỉnh sửa banner' }}
              </h2>
              <button @click="closeFormModal" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <form @submit.prevent="submitForm" class="p-6 space-y-5">
              <!-- Tiêu đề -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                  Tiêu đề <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.title"
                  type="text"
                  placeholder="Nhập tiêu đề banner..."
                  class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                />
                <p v-if="formErrors.title" class="mt-1 text-xs text-red-500">{{ formErrors.title[0] }}</p>
              </div>

              <!-- Ảnh banner -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                  Ảnh banner <span class="text-red-500">*</span>
                </label>
                <div v-if="form.image_url" class="mb-3 relative w-full aspect-[21/9] rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                  <img :src="form.image_url" alt="Preview" class="w-full h-full object-cover" />
                  <button
                    type="button"
                    @click="removeImage"
                    class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-red-500 hover:bg-red-50 border border-red-200 transition-all shadow"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
                <label
                  v-else
                  :class="imageUploading ? 'opacity-60 pointer-events-none' : 'cursor-pointer'"
                  class="flex flex-col items-center justify-center w-full aspect-[21/9] border-2 border-dashed border-slate-300 rounded-xl hover:border-[#0258cb] hover:bg-blue-50/30 transition-all"
                >
                  <input type="file" accept="image/*" class="sr-only" @change="handleImageUpload" :disabled="imageUploading" />
                  <div v-if="imageUploading" class="flex flex-col items-center gap-2 text-[#0258cb]">
                    <svg class="w-7 h-7 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    <span class="text-sm font-medium">Đang tải lên...</span>
                  </div>
                  <div v-else class="flex flex-col items-center gap-2 text-slate-400">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <span class="text-sm">Click để chọn ảnh banner (nằm ngang)</span>
                    <span class="text-xs">JPG, PNG, WebP — tối đa 5MB</span>
                  </div>
                </label>
                <p v-if="formErrors.image_url" class="mt-1 text-xs text-red-500">{{ formErrors.image_url[0] }}</p>
              </div>

              <!-- URL đích -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL đích (tùy chọn)</label>
                <input
                  v-model="form.target_url"
                  type="text"
                  placeholder="Ví dụ: /products?sale=1"
                  class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                />
              </div>

              <!-- Vị trí & Thứ tự -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Vị trí <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.position"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  >
                    <option value="">Chọn vị trí...</option>
                    <option value="home_hero">Trang chủ - Hero Slider</option>
                    <option value="home_middle">Trang chủ - Giữa trang</option>
                    <option value="sidebar">Sidebar</option>
                  </select>
                  <p v-if="formErrors.position" class="mt-1 text-xs text-red-500">{{ formErrors.position[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1.5">Thứ tự hiển thị</label>
                  <input
                    v-model.number="form.display_order"
                    type="number"
                    min="0"
                    placeholder="0"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  />
                </div>
              </div>

              <!-- Thời gian hiệu lực -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ngày bắt đầu</label>
                  <input
                    v-model="form.start_date"
                    type="date"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  />
                  <p v-if="formErrors.start_date" class="mt-1 text-xs text-red-500">{{ formErrors.start_date[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ngày kết thúc</label>
                  <input
                    v-model="form.end_date"
                    type="date"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  />
                  <p v-if="formErrors.end_date" class="mt-1 text-xs text-red-500">{{ formErrors.end_date[0] }}</p>
                </div>
              </div>

              <!-- Trạng thái -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Trạng thái</label>
                <div class="flex gap-3">
                  <label
                    class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl cursor-pointer transition-all"
                    :class="form.is_active
                      ? 'border-[#0258cb] bg-blue-50 text-[#0258cb]'
                      : 'border-slate-200 text-slate-500 hover:border-slate-300'"
                  >
                    <input type="radio" :value="true" v-model="form.is_active" class="sr-only" />
                    <span class="w-2 h-2 rounded-full" :class="form.is_active ? 'bg-[#0258cb]' : 'bg-slate-300'"></span>
                    <span class="text-sm font-medium">Bật</span>
                  </label>
                  <label
                    class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl cursor-pointer transition-all"
                    :class="!form.is_active
                      ? 'border-slate-500 bg-slate-50 text-slate-600'
                      : 'border-slate-200 text-slate-500 hover:border-slate-300'"
                  >
                    <input type="radio" :value="false" v-model="form.is_active" class="sr-only" />
                    <span class="w-2 h-2 rounded-full" :class="!form.is_active ? 'bg-slate-500' : 'bg-slate-300'"></span>
                    <span class="text-sm font-medium">Tắt</span>
                  </label>
                </div>
                <p v-if="formErrors.is_active" class="mt-1 text-xs text-red-500">{{ formErrors.is_active[0] }}</p>
              </div>

              <!-- Form error -->
              <div v-if="formError" class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ formError }}
              </div>

              <!-- Footer actions -->
              <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                <button
                  type="button"
                  @click="closeFormModal"
                  class="flex-1 py-2.5 px-5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all focus:outline-none"
                >
                  Hủy bỏ
                </button>
                <button
                  type="submit"
                  :disabled="formSubmitting"
                  class="flex-1 py-2.5 px-5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all shadow-md shadow-blue-200 focus:outline-none active:scale-[0.98] disabled:opacity-60 disabled:pointer-events-none flex items-center justify-center gap-2"
                >
                  <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                  {{ modalMode === 'add' ? 'Thêm banner' : 'Lưu thay đổi' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ========== CONFIRM DELETE MODAL ========== -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa banner"
      message="Bạn có chắc chắn muốn xóa banner"
      :itemName="deleteTarget?.title"
      messageSuffix="không? Banner sẽ bị xóa vĩnh viễn."
      confirmLabel="Xóa banner"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useBannerStore } from '@/stores/admin/bannerStore'
import { uploadService } from '@/services/admin/uploadService'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'

const bannerStore = useBannerStore()

onMounted(() => {
  bannerStore.initialFetch()
})

// ─── Stats ───────────────────────────────────────────────────────────────────
const activeCount = computed(() => bannerStore.banners.filter(b => b.is_active).length)
const inactiveCount = computed(() => bannerStore.banners.filter(b => !b.is_active).length)

// ─── Helpers ─────────────────────────────────────────────────────────────────
const positionLabel = (position) => {
  const labels = {
    home_hero: 'Hero Slider',
    home_middle: 'Giữa trang',
    sidebar: 'Sidebar',
  }
  return labels[position] || position
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  // Nếu dạng YYYY-MM-DD, cắt chuỗi hiển thị trực tiếp tránh timezone offset
  if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
    const [y, m, d] = dateStr.split('-')
    return `${d}/${m}/${y}`
  }
  try {
    return new Date(dateStr).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
  } catch { return dateStr }
}

// ─── Search & Filter ─────────────────────────────────────────────────────────
const searchQuery = ref('')
const filterPosition = ref('')
let searchTimer = null

const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    bannerStore.fetchBanners({ search: searchQuery.value, position: filterPosition.value, page: 1 })
  }, 400)
}

const onFilterChange = () => {
  bannerStore.fetchBanners({ search: searchQuery.value, position: filterPosition.value, page: 1 })
}

const goToPage = (page) => {
  if (page < 1 || page > bannerStore.meta.last_page) return
  bannerStore.fetchBanners({ search: searchQuery.value, position: filterPosition.value, page })
}

const handlePerPageChange = (newPerPage) => {
  bannerStore.meta.per_page = newPerPage
  bannerStore.fetchBanners({ search: searchQuery.value, position: filterPosition.value, page: 1 })
}

// ─── Form Modal ───────────────────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add')
const formSubmitting = ref(false)
const formError = ref(null)
const formErrors = ref({})
const imageUploading = ref(false)
const prevImagePath = ref(null)

const defaultForm = () => ({
  title: '',
  image_url: '',
  target_url: '',
  position: '',
  display_order: 0,
  is_active: true,
  start_date: '',
  end_date: '',
})

const form = ref(defaultForm())
const editTarget = ref(null)

const openAddModal = () => {
  modalMode.value = 'add'
  editTarget.value = null
  form.value = defaultForm()
  formErrors.value = {}
  formError.value = null
  prevImagePath.value = null
  showFormModal.value = true
}

const openEditModal = (banner) => {
  modalMode.value = 'edit'
  editTarget.value = banner
  form.value = {
    title: banner.title,
    image_url: banner.image_url || '',
    target_url: banner.target_url || '',
    position: banner.position,
    display_order: banner.display_order ?? 0,
    is_active: banner.is_active,
    start_date: banner.start_date ? banner.start_date.slice(0, 10) : '',
    end_date: banner.end_date ? banner.end_date.slice(0, 10) : '',
  }
  formErrors.value = {}
  formError.value = null
  prevImagePath.value = null
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
}

const handleImageUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  imageUploading.value = true
  try {
    const result = await uploadService.uploadImage(file, 'banners')
    if (prevImagePath.value) {
      uploadService.deleteImage(prevImagePath.value).catch(() => {})
    }
    form.value.image_url = result.url
    prevImagePath.value = result.path
  } catch (e) {
    formError.value = 'Không thể upload ảnh. Vui lòng thử lại.'
  } finally {
    imageUploading.value = false
  }
}

const removeImage = () => {
  if (prevImagePath.value) {
    uploadService.deleteImage(prevImagePath.value).catch(() => {})
    prevImagePath.value = null
  }
  form.value.image_url = ''
}

const submitForm = async () => {
  formSubmitting.value = true
  formError.value = null
  formErrors.value = {}

  // Chuẩn bị data: bỏ các trường rỗng cho ngày
  const payload = {
    ...form.value,
    start_date: form.value.start_date || null,
    end_date: form.value.end_date || null,
    target_url: form.value.target_url || null,
  }

  try {
    if (modalMode.value === 'add') {
      await bannerStore.createBanner(payload)
    } else {
      await bannerStore.updateBanner(editTarget.value.id, payload)
    }
    showFormModal.value = false
    prevImagePath.value = null
  } catch (e) {
    if (e.errors) {
      formErrors.value = e.errors
    } else {
      formError.value = e.message
    }
  } finally {
    formSubmitting.value = false
  }
}

// ─── Delete ───────────────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const deleteTarget = ref(null)

const confirmDelete = (banner) => {
  deleteTarget.value = banner
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await bannerStore.deleteBanner(deleteTarget.value.id)
    showDeleteModal.value = false
    deleteTarget.value = null
  } catch (e) {
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
