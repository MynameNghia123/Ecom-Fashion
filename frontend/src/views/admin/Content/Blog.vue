<template>
  <div class="space-y-6">

    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Quản lý Blog</h1>
        <p class="text-sm text-slate-500 mt-1">Xem, tạo mới và quản lý các nội dung bài viết trên hệ thống.</p>
      </div>
      <button
        @click="openCreate"
        class="inline-flex items-center gap-2 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
      >
        + Thêm bài viết mới
      </button>
    </div>

    <!-- 3 thẻ thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
          </svg>
        </div>
        <div>
          <div class="text-xs text-slate-500 uppercase tracking-wide">Tổng số bài viết</div>
          <div class="text-2xl font-bold text-slate-900">{{ posts.length }}</div>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
          <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
          </svg>
        </div>
        <div>
          <div class="text-xs text-slate-500 uppercase tracking-wide">Đang hiển thị</div>
          <div class="text-2xl font-bold text-slate-900">{{ activeCount }}</div>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
          <svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </div>
        <div>
          <div class="text-xs text-slate-500 uppercase tracking-wide">Bản nháp</div>
          <div class="text-2xl font-bold text-slate-900">{{ draftCount }}</div>
        </div>
      </div>
    </div>

    <!-- Bảng danh sách bài viết -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <h2 class="font-semibold text-slate-900">Danh sách bài viết</h2>
          <div class="flex bg-slate-100 rounded-full p-0.5 text-xs">
            <button
              @click="tab = 'all'"
              class="px-3 py-1 rounded-full transition-colors"
              :class="tab === 'all' ? 'bg-white text-[#0258cb] shadow-sm font-medium' : 'text-slate-500'"
            >Tất cả</button>
            <button
              @click="tab = 'recent'"
              class="px-3 py-1 rounded-full transition-colors"
              :class="tab === 'recent' ? 'bg-white text-[#0258cb] shadow-sm font-medium' : 'text-slate-500'"
            >Gần đây</button>
          </div>
        </div>
        <div class="flex items-center gap-2 text-slate-400">
          <button title="Lọc" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 hover:text-slate-600 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
          </button>
          <button title="Tải xuống" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 hover:text-slate-600 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-slate-500 text-xs uppercase tracking-wide bg-slate-50 border-b border-slate-100">
              <th class="text-left px-5 py-3 font-medium">ID</th>
              <th class="text-left px-5 py-3 font-medium">Ảnh đại diện</th>
              <th class="text-left px-5 py-3 font-medium">Tiêu đề &amp; Slug</th>
              <th class="text-left px-5 py-3 font-medium">Trạng thái</th>
              <th class="text-left px-5 py-3 font-medium">Ngày tạo</th>
              <th class="text-right px-5 py-3 font-medium w-[120px]">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in filteredPosts"
              :key="item.id"
              class="border-t border-slate-100 hover:bg-slate-50/60 transition-colors duration-150"
            >
              <td class="px-5 py-3 text-[#0258cb] font-medium">#{{ item.id }}</td>
              <td class="px-5 py-3">
                <div class="w-14 h-10 rounded-lg overflow-hidden border border-slate-200">
                  <img :src="item.image" :alt="item.title" class="w-full h-full object-cover" />
                </div>
              </td>
              <td class="px-5 py-3">
                <a href="#" @click.prevent="openDetail(item)" class="text-slate-850 font-semibold hover:text-[#0258cb]">{{ item.title }}</a>
                <div class="text-xs text-slate-400">/blog/{{ item.slug }}</div>
              </td>
              <td class="px-5 py-3">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="statusStyle(item.status)">
                  {{ statusLabel(item.status) }}
                </span>
              </td>
              <td class="px-5 py-3 text-slate-500">{{ item.createdAt }}</td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-1 text-slate-400">
                  <button
                    @click="openDetail(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150"
                    title="Xem chi tiết"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button
                    @click="openEdit(item)"
                    class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150"
                    title="Chỉnh sửa"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button
                    @click="askDelete(item)"
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

            <!-- Trạng thái rỗng khi lọc không có kết quả -->
            <tr v-if="filteredPosts.length === 0">
              <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                Không có bài viết nào phù hợp.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
        <span>Hiển thị 1 - {{ filteredPosts.length }} của {{ posts.length }} bài viết</span>
        <div class="flex gap-1 items-center">
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">‹</button>
          <button class="w-7 h-7 flex items-center justify-center rounded bg-[#0258cb] text-white">1</button>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">2</button>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">3</button>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">›</button>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 1: CHI TIẾT BÀI VIẾT                                      -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showDetail && selected" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeAll">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto" v-if="showDetail && selected">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 sticky top-0 bg-white z-10">
              <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                Chi tiết bài viết
              </h2>
              <button @click="closeAll" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="relative">
              <img :src="selected.image" :alt="selected.title" class="w-full h-48 object-cover" />
              <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1.5 bg-white border border-slate-200" :class="statusStyle(selected.status)">
                <span class="w-1.5 h-1.5 rounded-full" :class="selected.status === 'Active' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                {{ selected.status === 'Active' ? 'Đang hoạt động' : 'Bản nháp' }}
              </span>
            </div>

            <div class="px-6 py-5">
              <h3 class="text-xl font-bold text-slate-900 mb-2">{{ selected.title }}</h3>
              <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 mb-4">
                <span>📅 {{ selected.createdAt }} {{ selected.createdTime }}</span>
                <span>🔗 {{ selected.slug }}</span>
                <span>🏷️ ID: {{ selected.id }}</span>
              </div>
              <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 border border-slate-100 rounded-xl">{{ selected.description }}</p>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-white">
              <button @click="closeAll" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Đóng</button>
              <button @click="openEdit(selected)" class="inline-flex items-center gap-1 px-4 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3]">
                ✏️ Chỉnh sửa
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- ============================================================ -->
    <!-- MODAL 2: THÊM MỚI / CHỈNH SỬA BÀI VIẾT                          -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showForm && form" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeAll">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" v-if="showForm && form">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 sticky top-0 bg-white z-10">
              <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                {{ isCreating ? 'Thêm bài viết mới' : 'Chỉnh sửa bài viết' }}
              </h2>
              <button @click="closeAll" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-slate-700">Tên bài viết <span class="text-rose-500">*</span></label>
                  <input v-model="form.title" type="text"
                    class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30" />
                </div>
                <div>
                  <label class="text-sm font-medium text-slate-700">Đường dẫn (Slug) <span class="text-rose-500">*</span></label>
                  <div class="mt-1 relative">
                    <input v-model="form.slug" type="text"
                      class="w-full text-sm border border-slate-200 rounded-lg pl-3 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30" />
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                      </svg>
                    </span>
                  </div>
                </div>
              </div>

              <div>
                <label class="text-sm font-medium text-slate-700">Trạng thái <span class="text-rose-500">*</span></label>
                <select v-model="form.status" class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                  <option value="" disabled>Chọn trạng thái</option>
                  <option value="Active">Active</option>
                  <option value="Draft">Draft</option>
                </select>
              </div>

              <div>
                <label class="text-sm font-medium text-slate-700">Mô tả bài viết</label>
                <textarea v-model="form.description" rows="4"
                  class="mt-1 w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/30"></textarea>
              </div>

              <div>
                <label class="text-sm font-medium text-slate-700 block mb-2">Ảnh đại diện</label>

                <div v-if="form.image" class="flex items-start gap-4">
                  <img :src="form.image" class="w-40 h-28 object-cover rounded-lg border border-slate-200" />
                  <div class="text-sm">
                    <button @click="form.image = ''" class="text-rose-600 font-semibold hover:underline">Xóa ảnh</button>
                    <p class="text-xs text-slate-400 mt-2">Định dạng hỗ trợ: JPG, PNG. Kích thước tối đa: 2MB. Tỷ lệ khuyên dùng 16:9.</p>
                  </div>
                </div>
                <div v-else class="flex items-start gap-4">
                  <div class="w-40 h-28 rounded-lg bg-slate-100 border border-dashed border-slate-200"></div>
                  <div class="text-sm">
                    <button @click="form.image = fallbackImage" class="inline-flex items-center gap-1 border border-dashed border-slate-300 text-slate-600 px-3 py-1.5 rounded-lg hover:bg-slate-50 font-medium">
                      ⬆ Tải ảnh mới
                    </button>
                    <p class="text-xs text-slate-400 mt-2">Định dạng hỗ trợ: JPG, PNG. Kích thước tối đa: 2MB. Tỷ lệ khuyên dùng 16:9.</p>
                  </div>
                </div>
              </div>

              <div v-if="!isCreating" class="flex items-center gap-4 text-xs text-slate-500 bg-slate-50 border-l-4 border-blue-500 rounded px-4 py-2">
                <span>📅 Ngày tạo: {{ form.createdAt }} {{ form.createdTime }}</span>
                <span>🏷️ ID: {{ form.id }}</span>
              </div>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-white">
              <button @click="closeAll" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Hủy</button>
              <button @click="submitForm" class="inline-flex items-center gap-1 px-4 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3]">
                💾 {{ isCreating ? 'Đăng bài viết' : 'Cập nhật bài viết' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- ============================================================ -->
    <!-- MODAL 3: XÁC NHẬN XOÁ                                           -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showDeleteConfirm && toDelete" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-50" @click.self="showDeleteConfirm = false">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6" v-if="showDeleteConfirm && toDelete">
            <h3 class="font-semibold text-slate-900 mb-2">Xoá bài viết?</h3>
            <p class="text-sm text-slate-500 mb-5">
              Bạn có chắc muốn xoá bài viết "<span class="font-medium text-slate-700">{{ toDelete.title }}</span>"? Hành động này không thể hoàn tác.
            </p>
            <div class="flex justify-end gap-2">
              <button @click="showDeleteConfirm = false" class="px-4 py-2 text-sm rounded-lg border border-slate-200 hover:bg-slate-50">Hủy</button>
              <button @click="confirmDelete" class="px-4 py-2 text-sm rounded-lg bg-rose-600 text-white hover:bg-rose-700">Xoá bài viết</button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const fallbackImage = 'https://images.unsplash.com/photo-1487014679447-9f8336841d58?w=400&h=280&fit=crop'

// Dữ liệu mẫu, đóng vai trò "database" giả lập
const posts = ref([
  {
    id: 1024,
    title: 'Xu hướng thiết kế UI/UX hiện đại năm 2024',
    slug: 'xu-huong-thiet-ke-ui-ux-2024',
    status: 'Active',
    createdAt: '15/05/2024',
    createdTime: '14:30',
    image: 'https://images.unsplash.com/photo-1587440871875-191322ee64b0?w=400&h=280&fit=crop',
    description: 'Hệ thống quản trị BFD Admin được thiết kế nhằm mang lại trải nghiệm quản lý dữ liệu tối ưu nhất cho các doanh nghiệp quy mô lớn. Trong giai đoạn phát triển năm 2024, chúng tôi tập trung mạnh mẽ vào khả năng mở rộng kiến trúc vi dịch vụ và tích hợp các công cụ phân tích dữ liệu thời gian thực.\n\nCác tính năng cốt lõi được nâng cấp bao gồm:\n- Cải thiện tốc độ tải dữ liệu lên đến 40% thông qua cơ chế caching mới.\n- Tích hợp hệ thống báo cáo tùy chỉnh với hơn 50 loại biểu đồ khác nhau.\n- Bảo mật đa lớp với xác thực sinh trắc học và quản lý phân quyền chi tiết.',
  },
  {
    id: 1023,
    title: 'Xu hướng thời trang Thu Đông 2023',
    slug: 'xu-huong-thoi-trang-thu-dong-2023',
    status: 'Draft',
    createdAt: '20/10/2023',
    createdTime: '14:30',
    image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400&h=280&fit=crop',
    description: 'Khám phá những xu hướng thời trang nổi bật nhất cho mùa Thu Đông năm nay. Từ bảng màu trung tính thanh lịch đến những chất liệu len cao cấp, bài viết này sẽ cập nhật cho bạn những phong cách không thể bỏ lỡ để luôn dẫn đầu trong gu thẩm mỹ đương đại.',
  },
  {
    id: 1022,
    title: '10 mẹo tối ưu hiệu suất website',
    slug: '10-meo-toi-uu-hieu-suat-website',
    status: 'Active',
    createdAt: '02/03/2024',
    createdTime: '09:00',
    image: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=280&fit=crop',
    description: 'Tổng hợp các kỹ thuật giúp website tải nhanh hơn: tối ưu ảnh, lazy-load, nén tài nguyên và sử dụng CDN hợp lý.',
  },
])

const tab = ref('all') // 'all' | 'recent'

// computed: đếm số bài Active / Draft, tự cập nhật khi posts thay đổi
const activeCount = computed(() => posts.value.filter(p => p.status === 'Active').length)
const draftCount = computed(() => posts.value.filter(p => p.status === 'Draft').length)

// computed: danh sách hiển thị theo tab đang chọn
const filteredPosts = computed(() => {
  if (tab.value === 'all') return posts.value
  return [...posts.value].sort((a, b) => b.id - a.id).slice(0, 2)
})

// State điều khiển các modal
const showDetail = ref(false)
const showForm = ref(false)
const showDeleteConfirm = ref(false)

const selected = ref(null)   // bài viết đang xem chi tiết
const form = ref(null)       // form dùng chung cho tạo mới / chỉnh sửa
const isCreating = ref(false)
const toDelete = ref(null)

// ---------------------------------------------------------------
// 2. METHODS
// ---------------------------------------------------------------

function statusLabel(status) {
  return status === 'Active' ? 'Active' : 'Draft'
}

function statusStyle(status) {
  return status === 'Active'
    ? 'bg-emerald-100 text-emerald-700'
    : 'bg-slate-100 text-slate-600'
}

function closeAll() {
  showDetail.value = false
  showForm.value = false
  selected.value = null
  form.value = null
}

function openDetail(item) {
  selected.value = item
  showDetail.value = true
  showForm.value = false
}

function openCreate() {
  isCreating.value = true
  form.value = {
    id: null,
    title: '',
    slug: '',
    status: 'Active',
    description: '',
    image: '',
    createdAt: '',
    createdTime: '',
  }
  showForm.value = true
  showDetail.value = false
}

function openEdit(item) {
  isCreating.value = false
  form.value = { ...item }
  showForm.value = true
  showDetail.value = false
}

function submitForm() {
  if (isCreating.value) {
    const newId = Math.max(...posts.value.map(p => p.id)) + 1
    posts.value.unshift({
      ...form.value,
      id: newId,
      createdAt: new Date().toLocaleDateString('vi-VN'),
      createdTime: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
      image: form.value.image || fallbackImage,
    })
  } else {
    const idx = posts.value.findIndex(p => p.id === form.value.id)
    if (idx !== -1) posts.value[idx] = { ...form.value }
  }
  closeAll()
}

function askDelete(item) {
  toDelete.value = item
  showDeleteConfirm.value = true
}

function confirmDelete() {
  posts.value = posts.value.filter(p => p.id !== toDelete.value.id)
  showDeleteConfirm.value = false
  toDelete.value = null
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.18s ease; }
.pop-leave-active { transition: all 0.12s ease; }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: scale(0.96) translateY(8px); }
</style>