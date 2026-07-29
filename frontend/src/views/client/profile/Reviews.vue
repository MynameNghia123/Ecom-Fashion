<template>
  <div class="space-y-8 animate-fade-in text-[#111111] font-text">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title leading-tight">Đánh giá của tôi</h1>
      <p class="text-sm text-neutral-400 mt-2">Xem lại các sản phẩm bạn đã gửi đánh giá.</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="py-16 text-center">
      <svg class="animate-spin w-6 h-6 mx-auto text-neutral-400" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
      </svg>
    </div>

    <!-- Empty -->
    <div v-else-if="reviews.length === 0" class="py-16 text-center border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
      <p class="text-sm text-neutral-400">Bạn chưa có đánh giá nào.</p>
    </div>

    <!-- Review list -->
    <div v-else class="space-y-6">
      <div
        v-for="review in reviews"
        :key="review.id"
        class="border border-neutral-200 p-6 bg-white space-y-4"
      >
        <!-- Product link and date -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 pb-3 border-b border-neutral-100">
          <div>
            <p class="text-sm font-bold text-neutral-900">{{ review.product?.name }}</p>
            <p class="text-xs text-neutral-400 mt-0.5">
              <span v-if="review.order_detail?.product_variant?.attribute_values">
                {{ review.order_detail.product_variant.attribute_values.map(v => v.value).join(' / ') }}
              </span>
            </p>
          </div>
          <span class="text-xs text-neutral-400">{{ formatDate(review.created_at) }}</span>
        </div>

        <!-- Rating Stars -->
        <div class="flex items-center gap-1">
          <svg
            v-for="star in 5"
            :key="star"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            :fill="review.rating >= star ? '#FBBF24' : 'none'"
            :stroke="review.rating >= star ? '#FBBF24' : '#D1D5DB'"
            stroke-width="2"
          >
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>

        <!-- Comment -->
        <p class="text-sm text-neutral-600 leading-relaxed italic">
          "{{ review.comment || 'Không có nhận xét viết tay.' }}"
        </p>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { reviewService } from '@/services/client/reviewService'

const reviews = ref([])
const loading = ref(false)

const fetchReviews = async () => {
  loading.value = true
  try {
    const res = await reviewService.getMyReviews()
    if (res.data && res.data.success) {
      reviews.value = res.data.data
    }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchReviews)

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('vi-VN')
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
</style>