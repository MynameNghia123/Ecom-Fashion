<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Blog</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý toàn bộ bài viết trong hệ thống</p>
      </div>
      <button
        id="btn-open-add-blog"
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm bài viết mới
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng bài viết</p>
          <p class="text-3xl font-bold text-slate-800">{{ blogStore.meta.total }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
          </svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hiển thị</p>
          <p class="text-3xl font-bold text-slate-800">{{ activeCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã ẩn</p>
          <p class="text-3xl font-bold text-slate-800">{{ inactiveCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="blogStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
    >
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ blogStore.error }}
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
            id="search-blog"
            v-model="searchQuery"
            @input="onSearch"
            type="text"
            placeholder="Tìm theo tiêu đề..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <!-- Filter Status -->
        <select
          v-model="filterStatus"
          @change="onFilterChange"
          class="py-2.5 px-4 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        >
          <option value="">Tất cả trạng thái</option>
          <option value="active">Đang hiển thị</option>
          <option value="inactive">Đã ẩn</option>
        </select>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[60px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[80px]">Ảnh</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tiêu đề</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Ngày tạo</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Loading skeleton -->
            <template v-if="blogStore.loading">
              <tr v-for="i in blogStore.meta.per_page" :key="i" class="animate-pulse">
                <td class="py-4 px-5"><div class="h-4 bg-slate-200 rounded w-8"></div></td>
                <td class="py-4 px-4"><div class="h-14 w-14 bg-slate-200 rounded-xl"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-48"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-32"></div></td>
                <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-20"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
                <td class="py-4 px-4">
                  <div class="flex justify-end gap-2">
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                  </div>
                </td>
              </tr>
            </template>

            <!-- Rows -->
            <template v-else>
              <tr
                v-for="blog in blogStore.blogs"
                :key="blog.id"
                class="hover:bg-blue-50/40 transition-colors duration-100 group"
              >
                <td class="py-4 px-5 font-mono text-xs text-slate-500">{{ blog.id }}</td>
                <td class="py-3 px-4">
                  <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center shrink-0">
                    <img
                      v-if="blog.image"
                      :src="blog.image"
                      :alt="blog.name"
                      class="w-full h-full object-cover"
                    />
                    <svg v-else class="w-6 h-6 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                  </div>
                </td>
                <td class="py-4 px-4 font-semibold text-slate-800 max-w-[240px]">
                  <span class="line-clamp-2">{{ blog.name }}</span>
                </td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-slate-100 text-slate-600 text-xs font-mono px-2.5 py-1 rounded-lg max-w-[180px] truncate">{{ blog.slug }}</span>
                </td>
                <td class="py-4 px-4">
                  <span
                    :class="blog.status
                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                      : 'bg-slate-100 text-slate-500 border border-slate-200'"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                  >
                    <span :class="blog.status ? 'bg-emerald-500' : 'bg-slate-400'" class="w-1.5 h-1.5 rounded-full"></span>
                    {{ blog.status ? 'Hiển thị' : 'Đã ẩn' }}
                  </span>
                </td>
                <td class="py-4 px-4 text-xs text-slate-500">{{ blog.created_at }}</td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <!-- Edit -->
                    <button
                      @click="openEditModal(blog)"
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
                      @click="confirmDelete(blog)"
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
            <tr v-if="!blogStore.loading && blogStore.blogs.length === 0">
              <td colspan="7" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    <line x1="9" y1="13" x2="15" y2="13"/>
                  </svg>
                  <p class="text-sm font-medium">Không tìm thấy bài viết nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          :currentPage="blogStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="blogStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="blogStore.meta.total"
          :lastPage="blogStore.meta.last_page"
          :loading="blogStore.loading"
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
                {{ modalMode === 'add' ? 'Thêm bài viết mới' : 'Chỉnh sửa bài viết' }}
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
                  v-model="form.name"
                  @input="autoSlug"
                  type="text"
                  placeholder="Nhập tiêu đề bài viết..."
                  class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                />
                <p v-if="formErrors.name" class="mt-1 text-xs text-red-500">{{ formErrors.name[0] }}</p>
              </div>

              <!-- Slug -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                  Slug <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.slug"
                    type="text"
                    placeholder="ten-bai-viet-theo-url"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 font-mono focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  />
                </div>
                <p v-if="formErrors.slug" class="mt-1 text-xs text-red-500">{{ formErrors.slug[0] }}</p>
              </div>

              <!-- Ảnh bìa -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                  Ảnh bìa <span class="text-red-500">*</span>
                </label>
                <!-- Preview -->
                <div v-if="form.image" class="mb-3 relative w-full aspect-[16/7] rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                  <img :src="form.image" alt="Preview" class="w-full h-full object-cover" />
                  <button
                    type="button"
                    @click="removeImage"
                    class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-red-500 hover:bg-red-50 border border-red-200 transition-all shadow"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
                <!-- Upload zone -->
                <label
                  v-else
                  :class="imageUploading ? 'opacity-60 pointer-events-none' : 'cursor-pointer'"
                  class="flex flex-col items-center justify-center w-full aspect-[16/7] border-2 border-dashed border-slate-300 rounded-xl hover:border-[#0258cb] hover:bg-blue-50/30 transition-all"
                >
                  <input type="file" accept="image/*" class="sr-only" @change="handleImageUpload" :disabled="imageUploading" />
                  <div v-if="imageUploading" class="flex flex-col items-center gap-2 text-[#0258cb]">
                    <svg class="w-7 h-7 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span class="text-sm font-medium">Đang tải lên...</span>
                  </div>
                  <div v-else class="flex flex-col items-center gap-2 text-slate-400">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <span class="text-sm">Click để chọn ảnh bìa</span>
                    <span class="text-xs">JPG, PNG, WebP — tối đa 5MB</span>
                  </div>
                </label>
                <p v-if="formErrors.image" class="mt-1 text-xs text-red-500">{{ formErrors.image[0] }}</p>
              </div>

              <!-- Mô tả / Nội dung -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                  Nội dung <span class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="form.description"
                  rows="6"
                  placeholder="Nhập nội dung bài viết..."
                  class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 resize-none focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                ></textarea>
                <p v-if="formErrors.description" class="mt-1 text-xs text-red-500">{{ formErrors.description[0] }}</p>
              </div>

              <!-- Trạng thái -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Trạng thái</label>
                <div class="flex gap-3">
                  <label
                    class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl cursor-pointer transition-all"
                    :class="form.status === 'active'
                      ? 'border-[#0258cb] bg-blue-50 text-[#0258cb]'
                      : 'border-slate-200 text-slate-500 hover:border-slate-300'"
                  >
                    <input type="radio" v-model="form.status" value="active" class="sr-only" />
                    <span class="w-2 h-2 rounded-full" :class="form.status === 'active' ? 'bg-[#0258cb]' : 'bg-slate-300'"></span>
                    <span class="text-sm font-medium">Hiển thị</span>
                  </label>
                  <label
                    class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl cursor-pointer transition-all"
                    :class="form.status === 'inactive'
                      ? 'border-slate-500 bg-slate-50 text-slate-600'
                      : 'border-slate-200 text-slate-500 hover:border-slate-300'"
                  >
                    <input type="radio" v-model="form.status" value="inactive" class="sr-only" />
                    <span class="w-2 h-2 rounded-full" :class="form.status === 'inactive' ? 'bg-slate-500' : 'bg-slate-300'"></span>
                    <span class="text-sm font-medium">Ẩn</span>
                  </label>
                </div>
                <p v-if="formErrors.status" class="mt-1 text-xs text-red-500">{{ formErrors.status[0] }}</p>
              </div>

              <!-- Form error message -->
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
                  {{ modalMode === 'add' ? 'Thêm bài viết' : 'Lưu thay đổi' }}
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
      title="Xóa bài viết"
      message="Bạn có chắc chắn muốn xóa bài viết"
      :itemName="deleteTarget?.name"
      messageSuffix="không? Bài viết sẽ bị xóa vĩnh viễn."
      confirmLabel="Xóa bài viết"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useBlogStore } from '@/stores/admin/blogStore'
import { uploadService } from '@/services/admin/uploadService'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'

const blogStore = useBlogStore()

// ─── Load dữ liệu khi mount ──────────────────────────────────────────────────
onMounted(() => {
  blogStore.initialFetch()
})

// ─── Stats ───────────────────────────────────────────────────────────────────
const activeCount = computed(() => blogStore.blogs.filter(b => b.status).length)
const inactiveCount = computed(() => blogStore.blogs.filter(b => !b.status).length)

// ─── Search & Filter ─────────────────────────────────────────────────────────
const searchQuery = ref('')
const filterStatus = ref('')
let searchTimer = null

const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    blogStore.fetchBlogs({ search: searchQuery.value, status: filterStatus.value, page: 1 })
  }, 400)
}

const onFilterChange = () => {
  blogStore.fetchBlogs({ search: searchQuery.value, status: filterStatus.value, page: 1 })
}

const goToPage = (page) => {
  if (page < 1 || page > blogStore.meta.last_page) return
  blogStore.fetchBlogs({ search: searchQuery.value, status: filterStatus.value, page })
}

const handlePerPageChange = (newPerPage) => {
  blogStore.meta.per_page = newPerPage
  blogStore.fetchBlogs({ search: searchQuery.value, status: filterStatus.value, page: 1 })
}

// ─── Form Modal ───────────────────────────────────────────────────────────────
const showFormModal = ref(false)
const modalMode = ref('add')
const formSubmitting = ref(false)
const formError = ref(null)
const formErrors = ref({})
const imageUploading = ref(false)
const prevImagePath = ref(null) // path của ảnh cũ để xóa khi đổi ảnh

const defaultForm = () => ({
  name: '',
  slug: '',
  description: '',
  image: '',
  status: 'active',
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

const openEditModal = (blog) => {
  modalMode.value = 'edit'
  editTarget.value = blog
  form.value = {
    name: blog.name,
    slug: blog.slug,
    description: blog.description || '',
    image: blog.image || '',
    status: blog.status ? 'active' : 'inactive',
  }
  formErrors.value = {}
  formError.value = null
  prevImagePath.value = null
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
}

// Auto-generate slug từ tiêu đề
const autoSlug = () => {
  if (modalMode.value === 'edit') return
  form.value.slug = form.value.name
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd').replace(/Đ/g, 'd')
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

// Upload ảnh
const handleImageUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  imageUploading.value = true
  try {
    const result = await uploadService.uploadImage(file, 'blogs')
    // Xóa ảnh cũ nếu có (chỉ ảnh vừa upload, không phải từ DB)
    if (prevImagePath.value) {
      uploadService.deleteImage(prevImagePath.value).catch(() => {})
    }
    form.value.image = result.url
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
  form.value.image = ''
}

const submitForm = async () => {
  formSubmitting.value = true
  formError.value = null
  formErrors.value = {}

  try {
    if (modalMode.value === 'add') {
      await blogStore.createBlog(form.value)
    } else {
      await blogStore.updateBlog(editTarget.value.id, form.value)
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

const confirmDelete = (blog) => {
  deleteTarget.value = blog
  showDeleteModal.value = true
}

const executeDelete = async () => {
  try {
    await blogStore.deleteBlog(deleteTarget.value.id)
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

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
