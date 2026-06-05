<template>
  <div class="group flex flex-col gap-[15px]">
    <div class="relative overflow-hidden bg-[#f8f8f8] aspect-[3/4]">
      <span v-if="discount" class="absolute top-[15px] left-[15px] bg-white text-black font-text text-[12px] font-bold px-2 py-1 z-[2]">{{ discount }}</span>
      <img :src="image" :alt="name" class="w-full h-full object-cover transition-transform duration-500 ease-in-out">
      <div class="absolute top-[15px] right-[15px] flex flex-col gap-2.5 opacity-0 translate-x-[10px] transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-x-0 z-[2]">
        <button class="bg-white border-none w-9 h-9 rounded-full flex items-center justify-center cursor-pointer text-black transition-all duration-300 shadow-[0_2px_5px_rgba(0,0,0,0.1)] hover:bg-black hover:text-white" aria-label="Yêu thích">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </button>
        <button class="bg-white border-none w-9 h-9 rounded-full flex items-center justify-center cursor-pointer text-black transition-all duration-300 shadow-[0_2px_5px_rgba(0,0,0,0.1)] hover:bg-black hover:text-white" aria-label="Xem nhanh">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
        </button>
      </div>
      <button class="absolute bottom-0 left-0 w-full bg-white text-black border-none py-[15px] font-text text-[13px] font-bold cursor-pointer translate-y-full transition-transform duration-300 ease-in-out group-hover:translate-y-0 z-[2] hover:bg-black hover:text-white">THÊM VÀO GIỎ HÀNG</button>
    </div>
    <div class="flex flex-col gap-[5px]">
      <h3 class="font-text text-[15px] font-medium m-0 text-black">{{ name }}</h3>
      <div class="font-text text-[15px] flex gap-[10px] items-center">
        <span class="font-semibold text-black">{{ currentPrice }}</span>
        <span v-if="originalPrice" class="text-[#888] line-through text-[14px]">{{ originalPrice }}</span>
      </div>
      <div v-if="rating" class="flex items-center gap-2 mt-[5px]">
        <div class="text-[#ccc] text-[14px] tracking-[2px]">
          <span v-for="(star, index) in rating.stars" :key="index" :class="['inline-block', star === 'filled' ? 'text-[#ffc107]' : star === 'half-filled' ? 'relative text-[#ccc] after:content-[\'★\'] after:absolute after:left-0 after:top-0 after:text-[#ffc107] after:overflow-hidden after:w-1/2' : 'text-[#ccc]']">★</span>
        </div>
        <span class="font-text text-[12px] text-[#666] font-medium">{{ rating.score }} ({{ rating.count }})</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  image: { type: String, required: true },
  name: { type: String, required: true },
  currentPrice: { type: String, required: true },
  originalPrice: { type: String, default: null },
  discount: { type: String, default: null },
  rating: { type: Object, default: null } 
});
</script>
