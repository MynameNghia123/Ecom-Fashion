<template>
  <div class="space-y-6 font-text text-slate-800">
    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Đánh giá</h1>
        <p class="text-sm text-slate-500 mt-0.5">Xem, theo dõi và kiểm duyệt nhận xét sản phẩm từ khách hàng</p>
      </div>
      <button 
        @click="refreshData" 
        class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2 active:scale-95 cursor-pointer"
      >
        <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        Làm mới
      </button>
    </div>

    <!-- Review Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
      <!-- Star Average summary -->
      <div class="md:col-span-4 bg-white rounded-2xl border border-slate-100 p-6 flex flex-col items-center justify-center text-center shadow-sm">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Đánh giá trung bình</p>
        <div class="text-5xl font-extrabold text-slate-800 tracking-tight leading-none mb-3">
          {{ reviewStore.meta.average || '0' }}
        </div>
        <div class="flex items-center gap-1 text-amber-400 mb-2">
          <span v-for="star in 5" :key="star" class="text-xl">
            {{ star <= Math.round(reviewStore.meta.average || 0) ? '★' : '☆' }}
          </span>
        </div>
        <p class="text-xs text-slate-500 font-medium">Từ tổng số {{ reviewStore.meta.total }} lượt đánh giá</p>
      </div>

      <!-- Rating progress bars -->
      <div class="md:col-span-8 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col justify-center gap-3">
        <div 
          v-for="stars in [5, 4, 3, 2, 1]" 
          :key="stars"
          class="flex items-center text-xs font-semibold text-slate-600"
        >
          <span class="w-14 shrink-0 flex items-center gap-1 text-amber-500 font-bold">
            ★ {{ stars }}
          </span>
          <div class="grow h-2.5 bg-slate-100 rounded-full overflow-hidden mx-4">
            <div 
              class="h-full bg-amber-400 rounded-full transition-all duration-500" 
              :style="{ width: reviewStore.meta.star_stats[stars]?.percentage || '0%' }"
            ></div>
          </div>
          <span class="w-20 text-right text-slate-400 font-medium shrink-0">
            {{ reviewStore.meta.star_stats[stars]?.count || 0 }} lượt ({{ reviewStore.meta.star_stats[stars]?.percentage || '0%' }})
          </span>
        </div>
      </div>
    </div>

    <!-- Error Banner -->
    <div
      v-if="reviewStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700 shadow-sm"
    >
      <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ reviewStore.error }}
    </div>

    <!-- Reviews Table and Filters -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <!-- Filter Toolbar -->
      <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
          <!-- Search input -->
          <div class="relative flex items-center w-full max-w-xs">
            <span class="absolute left-3 text-slate-400">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input
              v-model="searchQuery"
              @input="onSearch"
              type="text"
              placeholder="Tìm theo sản phẩm, khách hàng, nội dung..."
              class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-400 transition-colors"
            />
          </div>

          <!-- Star Filter -->
          <select
            v-model="selectedRating"
            @change="onFilterChange"
            class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-slate-400 transition-colors cursor-pointer"
          >
            <option value="">Tất cả mức sao</option>
            <option value="5">★ 5 Sao</option>
            <option value="4">★ 4 Sao</option>
            <option value="3">★ 3 Sao</option>
            <option value="2">★ 2 Sao</option>
            <option value="1">★ 1 Sao</option>
          </select>
        </div>

        <div class="text-xs text-slate-500 font-medium">
          Hiển thị <span class="font-bold text-slate-800">{{ reviewStore.reviews.length }}</span> đánh giá
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
              <th class="py-3.5 px-5">Khách Hàng</th>
              <th class="py-3.5 px-5">Sản Phẩm</th>
              <th class="py-3.5 px-5">Đánh Giá</th>
              <th class="py-3.5 px-5">Bình Luận</th>
              <th class="py-3.5 px-5">Thời Gian</th>
              <th class="py-3.5 px-5 text-right">Hành Động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <tr v-for="item in reviewStore.reviews" :key="item.id" class="hover:bg-slate-50/80 transition-colors">
              <!-- Customer info -->
              <td class="py-4 px-5">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 shrink-0 uppercase border border-slate-200">
                    {{ item.customer ? item.customer.first_name.charAt(0) : 'K' }}
                  </div>
                  <div>
                    <p class="font-bold text-slate-800">
                      {{ item.customer ? `${item.customer.first_name} ${item.customer.last_name}` : 'Ẩn danh' }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-mono">
                      {{ item.customer ? item.customer.email : 'customer@ecom.com' }}
                    </p>
                  </div>
                </div>
              </td>

              <!-- Product info -->
              <td class="py-4 px-5 max-w-[200px]">
                <div class="flex items-center gap-2.5" v-if="item.product">
                  <img 
                    :src="item.product.thumbnail || 'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=100'" 
                    :alt="item.product.name" 
                    class="w-8 h-10 object-cover rounded border border-slate-100 shrink-0" 
                  />
                  <div class="truncate">
                    <p class="font-semibold text-slate-800 truncate" :title="item.product.name">
                      {{ item.product.name }}
                    </p>
                  </div>
                </div>
                <span v-else class="text-slate-400 italic">Sản phẩm đã xóa</span>
              </td>

              <!-- Stars rating -->
              <td class="py-4 px-5">
                <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                  <span v-for="star in 5" :key="star">
                    {{ star <= item.rating ? '★' : '☆' }}
                  </span>
                </div>
              </td>

              <!-- Review comment -->
              <td class="py-4 px-5 max-w-[320px]">
                <p class="text-slate-700 leading-relaxed font-normal whitespace-pre-line break-words">
                  {{ item.comment || '(Không có nội dung bình luận)' }}
                </p>
              </td>

              <!-- Created time -->
              <td class="py-4 px-5 text-slate-400 font-medium">
                {{ formatDateTime(item.created_at) }}
              </td>

              <!-- Action triggers -->
              <td class="py-4 px-5 text-right">
                <button
                  @click="confirmDelete(item)"
                  class="p-2 text-rose-500 hover:text-white hover:bg-rose-500 rounded-lg transition-colors inline-flex items-center justify-center cursor-pointer border-none bg-transparent active:scale-95"
                  title="Xóa đánh giá"
                >
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                  </svg>
                </button>
              </td>
            </tr>

            <!-- Empty reviews -->
            <tr v-if="reviewStore.reviews.length === 0">
              <td colspan="6" class="text-center py-12 text-slate-400 font-medium italic">
                Chưa có đánh giá nào phù hợp với bộ lọc.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div 
        v-if="reviewStore.meta.last_page > 1"
        class="p-5 border-t border-slate-100 flex items-center justify-between"
      >
        <div class="text-xs text-slate-500 font-medium">
          Trang <span class="font-bold text-slate-800">{{ reviewStore.meta.current_page }}</span> / {{ reviewStore.meta.last_page }}
        </div>
        <div class="flex items-center gap-2">
          <button
            @click="changePage(reviewStore.meta.current_page - 1)"
            :disabled="reviewStore.meta.current_page === 1"
            class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none transition-colors cursor-pointer"
          >
            Trước
          </button>
          <button
            v-for="p in reviewStore.meta.last_page"
            :key="p"
            @click="changePage(p)"
            class="w-9 h-9 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center"
            :class="p === reviewStore.meta.current_page ? 'bg-[#0258cb] text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
          >
            {{ p }}
          </button>
          <button
            @click="changePage(reviewStore.meta.current_page + 1)"
            :disabled="reviewStore.meta.current_page === reviewStore.meta.last_page"
            class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none transition-colors cursor-pointer"
          >
            Sau
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="reviewToDelete"
      class="fixed inset-0 z-[350] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
        </div>
        <div>
          <h3 class="font-bold text-slate-800 text-base">Xóa đánh giá?</h3>
          <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            Bạn có chắc chắn muốn xóa đánh giá của khách hàng <span class="font-semibold text-slate-800">{{ reviewToDelete.customer ? `${reviewToDelete.customer.first_name} ${reviewToDelete.customer.last_name}` : 'Ẩn danh' }}</span>? Hành động này không thể hoàn tác.
          </p>
        </div>
        <div class="flex items-center gap-3 pt-2">
          <button 
            @click="reviewToDelete = null"
            class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition-colors border-none cursor-pointer"
          >
            Hủy bỏ
          </button>
          <button 
            @click="handleDeleteReview"
            class="flex-1 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs transition-colors border-none shadow-md shadow-rose-100 cursor-pointer"
          >
            Đồng ý xóa
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useReviewStore } from '@/stores/admin/reviewStore'

const reviewStore = useReviewStore()

const searchQuery = ref('')
const selectedRating = ref('')
const reviewToDelete = ref(null)

let searchTimeout = null

// Fetch data on mounted
onMounted(() => {
  reviewStore.fetchReviews()
})

const onSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchFiltered()
  }, 350)
}

const onFilterChange = () => {
  fetchFiltered()
}

const fetchFiltered = (page = 1) => {
  reviewStore.fetchReviews({
    page,
    search: searchQuery.value,
    rating: selectedRating.value
  })
}

const changePage = (page) => {
  fetchFiltered(page)
}

const confirmDelete = (item) => {
  reviewToDelete.value = item
}

const handleDeleteReview = async () => {
  if (!reviewToDelete.value) return
  try {
    await reviewStore.deleteReview(reviewToDelete.value.id)
    reviewToDelete.value = null
  } catch (e) {
    console.error('Xóa đánh giá thất bại:', e)
  }
}

const refreshData = () => {
  searchQuery.value = ''
  selectedRating.value = ''
  fetchFiltered(1)
}

// Helpers
const formatDateTime = (dateStr) => {
  if (!dateStr) return 'Gần đây'
  const date = new Date(dateStr)
  return date.toLocaleString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.98); }
  to   { opacity: 1; transform: scale(1); }
}
</style>
