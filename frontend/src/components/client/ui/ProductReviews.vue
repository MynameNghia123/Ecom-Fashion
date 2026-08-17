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

    <!-- Reviews List -->
    <div class="space-y-6 pt-6 border-t border-gray-100">
      <div v-if="reviews.length === 0" class="text-center py-8 bg-gray-50 border border-gray-100 rounded-sm">
        <p class="text-[13px] text-gray-500 font-medium m-0">Sản phẩm này chưa có đánh giá nào từ người mua.</p>
      </div>

      <div v-else v-for="(rev, idx) in reviews" :key="idx" class="flex gap-4 pb-6 border-b border-gray-100 last:border-b-0">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center font-bold text-gray-600">
          {{ rev.author ? rev.author.charAt(0).toUpperCase() : 'K' }}
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
  }
})
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
