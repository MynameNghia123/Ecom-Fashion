<template>
  <div class="product-reviews font-text">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-8">
      <!-- Rating Summary Stats -->
      <div class="md:col-span-4 bg-gray-50 p-6 border border-gray-100 rounded-sm text-center">
        <p class="text-[12px] font-bold uppercase text-gray-500 mb-1">Trung bình từ {{ reviews.length }} đánh giá</p>
        <div class="text-[64px] font-title font-bold text-gray-900 leading-none mb-2">{{ averageRating }}</div>
        <div class="flex justify-center text-yellow-500 mb-2">
          <span v-for="i in Math.floor(Number(averageRating))" :key="i" class="text-xl">★</span>
          <span v-if="Number(averageRating) % 1 !== 0" class="text-xl">★</span>
          <span v-for="i in (5 - Math.ceil(Number(averageRating)))" :key="'empty-'+i" class="text-xl text-gray-300">★</span>
        </div>
        <p class="text-[12px] text-gray-400">Đánh giá chung của khách hàng</p>
      </div>

      <!-- Progress rating bars -->
      <div class="md:col-span-8 flex flex-col justify-center gap-3">
        <div 
          v-for="stat in ratingStats" 
          :key="stat.stars" 
          class="flex items-center text-[12px] font-medium"
        >
          <span class="w-12 text-gray-600">★ {{ stat.stars }}</span>
          <div class="grow h-2.5 bg-gray-100 rounded-full overflow-hidden mx-4">
            <div class="h-full bg-yellow-500 rounded-full" :style="{ width: stat.percentage }"></div>
          </div>
          <span class="w-6 text-right text-gray-500">{{ stat.count }}</span>
        </div>
      </div>
    </div>

    <!-- Write Review Section -->
    <div class="mb-8 pt-6 border-t border-gray-100">
      <h3 class="text-[14px] font-bold uppercase tracking-[1px] text-gray-800 mb-4">Viết đánh giá của bạn</h3>
      
      <div v-if="!authStore.isAuthenticated" class="bg-gray-50 p-6 border border-gray-100 rounded-sm text-center">
        <p class="text-[14px] text-gray-600 mb-4">Vui lòng đăng nhập hoặc đăng ký để gửi bình luận.</p>
        <div class="flex justify-center gap-4">
          <router-link to="/login" class="bg-black text-white px-6 py-2.5 text-[12px] font-bold uppercase tracking-[1px] hover:bg-neutral-800 transition-colors">
            ĐĂNG NHẬP
          </router-link>
          <router-link to="/register" class="bg-white text-black border border-black px-6 py-2.5 text-[12px] font-bold uppercase tracking-[1px] hover:bg-gray-50 transition-colors">
            ĐĂNG KÝ
          </router-link>
        </div>
      </div>
      
      <div v-else-if="!isEligibleToReview" class="bg-gray-50 p-6 border border-gray-100 rounded-sm text-center">
        <p class="text-[14px] text-gray-600 mb-0">Bạn chỉ có thể đánh giá sau khi đã mua và nhận sản phẩm này.</p>
      </div>

      <div v-else class="bg-gray-50 p-6 border border-gray-100 rounded-sm">
        <div class="mb-4">
          <label class="block text-[13px] font-bold text-gray-700 mb-2">Đánh giá của bạn</label>
          <div class="flex gap-1 text-2xl text-yellow-500 cursor-pointer">
            <span v-for="i in 5" :key="i" @click="newRating = i" :class="i <= newRating ? 'text-yellow-500' : 'text-gray-300'">★</span>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-[13px] font-bold text-gray-700 mb-2">Nội dung đánh giá</label>
          <textarea 
            v-model="newComment" 
            rows="4" 
            class="w-full border border-gray-300 p-3 text-[13px] focus:outline-none focus:border-black transition-colors"
            placeholder="Mời bạn chia sẻ cảm nhận về sản phẩm..."
          ></textarea>
        </div>
        <button 
          @click="submitReview"
          class="bg-black hover:bg-neutral-800 text-white uppercase px-8 py-3 text-[13px] font-bold tracking-[1px] transition-all duration-300"
        >
          GỬI ĐÁNH GIÁ
        </button>
      </div>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6 pt-6 border-t border-gray-100">
      <div v-for="(rev, idx) in reviews" :key="idx" class="flex gap-4 pb-6 border-b border-gray-100 last:border-b-0">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center font-bold text-gray-600">
          {{ rev.author.charAt(0).toUpperCase() }}
        </div>
        <div class="grow">
          <div class="flex items-center gap-2 mb-1">
            <span class="text-[13px] font-bold text-gray-800">{{ rev.author }}</span>
            <span class="text-[11px] text-gray-400 font-medium">- {{ rev.date }}</span>
          </div>
          <div class="flex text-yellow-500 mb-2 text-[12px]">
            <span v-for="i in rev.rating" :key="i">★</span>
          </div>
          <p class="text-[13px] text-gray-600 leading-relaxed font-normal">
            {{ rev.comment }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'
import { reviewService } from '@/services/client/reviewService'

const props = defineProps({
  reviews: {
    type: Array,
    required: true
  },
  ratingStats: {
    type: Array,
    required: true
  },
  averageRating: {
    type: [String, Number],
    default: '0'
  },
  isEligibleToReview: {
    type: Boolean,
    default: false
  },
  eligibleOrderDetailId: {
    type: [Number, String],
    default: null
  }
})

const authStore = useClientAuthStore()
const newComment = ref('')
const newRating = ref(5)

const submitReview = async () => {
  if (!newComment.value.trim()) {
    alert('Vui lòng nhập nội dung bình luận.')
    return
  }
  
  if (!props.eligibleOrderDetailId) {
    alert('Không tìm thấy chi tiết đơn hàng để đánh giá.')
    return
  }

  try {
    const res = await reviewService.submitReview({
      order_detail_id: props.eligibleOrderDetailId,
      rating: newRating.value,
      comment: newComment.value
    })
    
    if (res.data && res.data.success) {
      alert('Cảm ơn bạn đã đánh giá sản phẩm!')
      newComment.value = ''
      newRating.value = 5
      
      // Emit an event to reload reviews or reload page
      window.location.reload()
    }
  } catch (error) {
    alert(error.response?.data?.message || 'Có lỗi xảy ra khi gửi đánh giá.')
  }
}
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
