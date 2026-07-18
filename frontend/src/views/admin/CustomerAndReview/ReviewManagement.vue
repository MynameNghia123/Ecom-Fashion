<template>
  <div class="space-y-6">

    <!-- Header + ô tìm kiếm/lọc -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Quản lý Đánh giá</h1>
        <p class="text-sm text-slate-500 mt-1">Quản lý phản hồi và xếp hạng sản phẩm của khách hàng trên toàn nền tảng.</p>
      </div>

      <div class="flex gap-3">
        <div class="relative">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm kiếm đánh giá..."
            class="pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg w-56 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
          />
        </div>

        <select v-model="ratingFilter" class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
          <option value="all">Tất cả xếp hạng</option>
          <option value="5">5 sao</option>
          <option value="4">4 sao</option>
          <option value="3">3 sao</option>
          <option value="2">2 sao</option>
          <option value="1">1 sao</option>
        </select>
      </div>
    </div>

    <!-- 3 thẻ thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between shadow-sm">
        <div>
          <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Tổng đánh giá</p>
          <p class="text-2xl font-bold text-slate-900">{{ totalReviews.toLocaleString('vi-VN') }}</p>
          <p class="text-xs text-emerald-600 mt-1">↗ +12% trong tháng này</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between shadow-sm">
        <div>
          <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Điểm trung bình</p>
          <p class="text-2xl font-bold text-slate-900">{{ averageRating }} <span class="text-base font-normal text-slate-400">/ 5.0</span></p>
          <span class="text-sm block mt-1">
            <span v-for="n in 5" :key="n" :class="n <= Math.round(Number(averageRating)) ? 'text-amber-400' : 'text-slate-200'">★</span>
          </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between shadow-sm">
        <div>
          <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Chờ kiểm duyệt</p>
          <p class="text-2xl font-bold text-slate-900">{{ pendingCount }}</p>
          <p class="text-xs text-slate-400 mt-1">Yêu cầu xử lý</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Bảng danh sách đánh giá gần đây -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Đánh giá gần đây</h2>
        <button class="text-sm text-[#0258cb] font-semibold hover:underline flex items-center gap-1.5">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
          </svg>
          Lọc
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-slate-500 text-xs uppercase tracking-wide bg-slate-50 border-b border-slate-100">
              <th class="text-left px-5 py-3 font-medium">ID</th>
              <th class="text-left px-5 py-3 font-medium">Sản phẩm</th>
              <th class="text-left px-5 py-3 font-medium">Khách hàng</th>
              <th class="text-left px-5 py-3 font-medium">Đánh giá</th>
              <th class="text-left px-5 py-3 font-medium">Bình luận</th>
              <th class="text-left px-5 py-3 font-medium">Ngày tạo</th>
              <th class="text-right px-5 py-3 font-medium w-[110px]">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in filteredReviews"
              :key="item.id"
              class="border-t border-slate-100 hover:bg-slate-50/60 transition-colors duration-150"
            >
              <td class="px-5 py-3 text-[#0258cb] font-medium">#{{ item.id }}</td>
              <td class="px-5 py-3">
                <div class="flex items-center gap-2">
                  <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-lg">{{ item.productIcon }}</div>
                  <span class="text-slate-700 font-medium">{{ item.product }}</span>
                </div>
              </td>
              <td class="px-5 py-3 text-slate-600 font-medium">{{ item.customer }}</td>
              <td class="px-5 py-3">
                <span class="text-sm">
                  <span v-for="n in 5" :key="n" :class="n <= item.rating ? 'text-amber-400' : 'text-slate-200'">★</span>
                </span>
              </td>
              <td class="px-5 py-3 text-slate-500 max-w-xs truncate">{{ item.comment }}</td>
              <td class="px-5 py-3 text-slate-400">{{ item.date }}</td>
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
                    @click="deleteReview(item)"
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

            <!-- Trạng thái rỗng khi tìm/lọc không có kết quả -->
            <tr v-if="filteredReviews.length === 0">
              <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                Không tìm thấy đánh giá phù hợp.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
        <span>Hiển thị 1 đến {{ filteredReviews.length }} trên {{ totalReviews.toLocaleString('vi-VN') }} mục</span>
        <div class="flex gap-1 items-center">
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">‹</button>
          <button class="w-7 h-7 flex items-center justify-center rounded bg-[#0258cb] text-white">1</button>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">2</button>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">3</button>
          <span class="px-1">...</span>
          <button class="w-7 h-7 flex items-center justify-center rounded border border-slate-200 hover:bg-slate-50">›</button>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: CHI TIẾT ĐÁNH GIÁ                                        -->
    <!-- ============================================================ -->
    <Transition name="fade">
      <div v-if="showDetail && selected" class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4 z-40" @click.self="closeDetail">
        <Transition name="pop" appear>
          <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100 bg-white sticky top-0 z-10">
              <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                Chi tiết đánh giá
              </h2>
              <button @click="closeDetail" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">
              <!-- Thông tin khách hàng + số sao -->
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-blue-100 text-[#0258cb] font-semibold flex items-center justify-center">
                    {{ initials(selected.customer) }}
                  </div>
                  <div>
                    <div class="font-medium text-slate-900">{{ selected.customer }}</div>
                    <div class="text-xs text-slate-500">{{ selected.email }}</div>
                    <div class="text-xs text-slate-400">Đánh giá lúc: {{ selected.datetime }}</div>
                  </div>
                </div>
                <span class="text-lg">
                  <span v-for="n in 5" :key="n" :class="n <= selected.rating ? 'text-amber-400' : 'text-slate-200'">★</span>
                </span>
              </div>

              <!-- Sản phẩm liên quan -->
              <div class="flex items-center gap-3 bg-slate-50 rounded-lg px-4 py-3 border border-slate-100">
                <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-lg">
                  {{ selected.productIcon }}
                </div>
                <div>
                  <div class="text-xs text-slate-400 uppercase tracking-wide font-semibold">Sản phẩm</div>
                  <a href="#" class="text-[#0258cb] text-sm font-semibold hover:underline">{{ selected.product }}</a>
                </div>
              </div>

              <!-- Nội dung bình luận -->
              <p class="text-sm text-slate-700 leading-relaxed bg-slate-50/50 p-4 border border-slate-100 rounded-xl italic">"{{ selected.comment }}"</p>
            </div>

            <div class="flex justify-end px-6 py-4 border-t border-slate-100">
              <button @click="closeDetail" class="px-5 py-2 text-sm rounded-lg bg-[#0258cb] text-white hover:bg-[#004bb3] transition-colors duration-150">Đóng</button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// ---------------------------------------------------------------
// 1. STATE
// ---------------------------------------------------------------

// Dữ liệu mẫu, đóng vai trò "database" giả lập
const reviews = ref([
  {
    id: 'R-1042',
    product: 'Sony WH-1000XM4...',
    productIcon: '🎧',
    customer: 'Nguyen Thanh',
    email: 'thanh.nguyen@example.com',
    rating: 5,
    comment: 'Âm thanh tuyệt vời, chống ồn rất tốt. Đóng gói cẩn thận, giao hàng nhanh.',
    date: 'Oct 24, 2023',
    datetime: '24/05/2026 14:30',
  },
  {
    id: 'R-1041',
    product: 'Apple Watch...',
    productIcon: '⌚',
    customer: 'Le Minh',
    email: 'minh.le@example.com',
    rating: 3,
    comment: 'Pin hơi yếu, cần sạc mỗi ngày. Ngoài ra thì các tính năng khác đều ổn.',
    date: 'Oct 23, 2023',
    datetime: '23/05/2026 09:12',
  },
  {
    id: 'R-1040',
    product: 'Nike Air Max 270',
    productIcon: '👟',
    customer: 'Tran Hai',
    email: 'hai.tran@example.com',
    rating: 4,
    comment: 'Giày đẹp, mang êm chân nhưng form hơi rộng so với size thường đi.',
    date: 'Oct 22, 2023',
    datetime: '22/05/2026 18:45',
  },
])

const searchQuery = ref('')
const ratingFilter = ref('all')

// Thống kê tổng quan
const totalReviews = ref(12458)
const pendingCount = ref(342)

// computed: điểm trung bình tự tính lại theo dữ liệu mẫu hiện có
const averageRating = computed(() => {
  const avg = reviews.value.reduce((sum, r) => sum + r.rating, 0) / reviews.value.length
  return avg.toFixed(1)
})

// computed: danh sách sau khi áp dụng tìm kiếm + lọc theo số sao
const filteredReviews = computed(() => {
  return reviews.value.filter(item => {
    const q = searchQuery.value.toLowerCase()
    const matchSearch =
      item.product.toLowerCase().includes(q) ||
      item.customer.toLowerCase().includes(q) ||
      item.comment.toLowerCase().includes(q)
    const matchRating = ratingFilter.value === 'all' || item.rating === Number(ratingFilter.value)
    return matchSearch && matchRating
  })
})

// State điều khiển modal chi tiết
const showDetail = ref(false)
const selected = ref(null)

// ---------------------------------------------------------------
// 2. METHODS
// ---------------------------------------------------------------

function openDetail(item) {
  selected.value = item
  showDetail.value = true
}

function closeDetail() {
  showDetail.value = false
  selected.value = null
}

function initials(name) {
  return name
    .split(' ')
    .map(p => p.charAt(0))
    .slice(-2)
    .join('')
    .toUpperCase()
}

function deleteReview(item) {
  reviews.value = reviews.value.filter(r => r.id !== item.id)
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.18s ease; }
.pop-leave-active { transition: all 0.12s ease; }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: scale(0.96) translateY(8px); }
</style>