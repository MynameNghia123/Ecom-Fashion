<template>
  <div class="w-full bg-white text-black py-12 px-5 lg:px-20 font-text">
    
    <!-- Title Section -->
    <div class="text-center mb-12 border-b border-gray-100 pb-8">
      <h1 class="font-title text-[48px] md:text-[60px] font-bold tracking-wide mb-6 text-gray-900">
        TẠP CHÍ THỜI TRANG
      </h1>
      <!-- Categories Filter -->
      <div class="flex flex-wrap justify-center gap-6 md:gap-8 text-[11px] font-bold uppercase tracking-[2px] text-gray-400">
        <button 
          v-for="cat in categories" 
          :key="cat"
          @click="activeCategory = cat"
          class="pb-1 transition-all duration-200"
          :class="activeCategory === cat ? 'text-black border-b-2 border-black font-extrabold' : 'hover:text-black'"
        >
          {{ cat }}
        </button>
      </div>
    </div>

    <!-- Featured Post (Full-width Split layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-16 border-b border-gray-100 pb-16">
      <div class="lg:col-span-7 aspect-[16/10] bg-gray-50 border border-gray-100 overflow-hidden">
        <img 
          src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1200&auto=format&fit=crop" 
          alt="Kiến Trúc Của Sự Tĩnh Lặng" 
          class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
        >
      </div>
      <div class="lg:col-span-5 flex flex-col justify-center">
        <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-3">TẬP SAN • MÙA THU 2025</span>
        <h2 class="font-title text-[32px] md:text-[42px] font-bold leading-tight text-gray-900 mb-4 tracking-tight">
          Kiến Trúc Của Sự Tĩnh Lặng: Nghệ Thuật May Đo Tối Giản
        </h2>
        <p class="text-[14px] text-gray-500 leading-relaxed mb-6 font-normal">
          Khám phá ranh giới giữa cấu trúc và sự mềm mại, bộ sưu tập mùa này lược bỏ những chi tiết thừa, chỉ tập trung vào phom dáng và chất liệu vải.
        </p>
        <div>
          <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b-2 border-black pb-1 hover:opacity-70 transition-opacity">
            ĐỌC BÀI VIẾT
          </a>
        </div>
      </div>
    </div>

    <!-- Asymmetric Blog Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 mb-16">
      
      <!-- Left Column (Taller list items) -->
      <div class="flex flex-col gap-16">
        <!-- Post 1 (Tall) -->
        <article class="flex flex-col">
          <div class="aspect-[3/4] bg-gray-50 border border-gray-100 overflow-hidden mb-6">
            <img 
              src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop" 
              alt="Tuần Lễ Thời Trang Paris" 
              class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
            >
          </div>
          <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-2">HẬU TRƯỜNG</span>
          <h3 class="font-title text-[24px] md:text-[28px] font-bold text-gray-900 mb-3 hover:underline cursor-pointer">
            Tuần Lễ Thời Trang Paris: Khám Phá Hậu Trường
          </h3>
          <p class="text-[14px] text-gray-500 leading-relaxed mb-4 font-normal">
            Cận cảnh những khoảnh khắc trước khi người mẫu bước lên sàn diễn, vẻ đẹp hỗn loạn đầy cuốn hút của sự chuẩn bị và từng chi tiết tạo kiểu.
          </p>
          <div>
            <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b border-black pb-1 hover:opacity-70 transition-opacity">
              ĐỌC BÀI VIẾT
            </a>
          </div>
        </article>

        <!-- Post 3 (Tall) -->
        <article class="flex flex-col">
          <div class="aspect-[3/4] bg-gray-50 border border-gray-100 overflow-hidden mb-6">
            <img 
              src="https://images.unsplash.com/photo-1617137968427-85924c800a22?q=80&w=800&auto=format&fit=crop" 
              alt="Giải Mã Thiết Kế Blazer Oversized" 
              class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
            >
          </div>
          <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-2">THIẾT KẾ</span>
          <h3 class="font-title text-[24px] md:text-[28px] font-bold text-gray-900 mb-3 hover:underline cursor-pointer">
            Giải Mã Thiết Kế Blazer Oversized
          </h3>
          <p class="text-[14px] text-gray-500 leading-relaxed mb-4 font-normal">
            Truy vết sự phát triển của phong cách thời trang quyền lực. Lý do vì sao những tỷ lệ phóng đại tiếp tục thống trị phom dáng hiện đại.
          </p>
          <div>
            <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b border-black pb-1 hover:opacity-70 transition-opacity">
              ĐỌC BÀI VIẾT
            </a>
          </div>
        </article>
      </div>

      <!-- Right Column (Wider/landscape or square items, offsets the height) -->
      <div class="flex flex-col gap-16 md:mt-12">
        <!-- Post 2 (Landscape/Wide) -->
        <article class="flex flex-col">
          <div class="aspect-[4/3] bg-gray-50 border border-gray-100 overflow-hidden mb-6">
            <img 
              src="https://images.unsplash.com/photo-1581557991964-125469da3b8a?q=80&w=800&auto=format&fit=crop" 
              alt="Tủ Đồ Tối Giản" 
              class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
            >
          </div>
          <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-2">HƯỚNG DẪN PHONG CÁCH</span>
          <h3 class="font-title text-[24px] md:text-[28px] font-bold text-gray-900 mb-3 hover:underline cursor-pointer">
            Phân Tích Tủ Đồ Tối Giản (Capsule Wardrobe)
          </h3>
          <p class="text-[14px] text-gray-500 leading-relaxed mb-4 font-normal">
            Lựa chọn những món đồ thiết yếu cho phong cách tối giản hiện đại. Cách xây dựng một nền tảng tủ đồ đa năng vượt qua mọi xu hướng mùa vụ.
          </p>
          <div>
            <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b border-black pb-1 hover:opacity-70 transition-opacity">
              ĐỌC BÀI VIẾT
            </a>
          </div>
        </article>

        <!-- Post 4 (Square-ish) -->
        <article class="flex flex-col">
          <div class="aspect-square bg-gray-50 border border-gray-100 overflow-hidden mb-6">
            <img 
              src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?q=80&w=800&auto=format&fit=crop" 
              alt="Trò Chuyện Cùng Giám Đốc Thiết Kế" 
              class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
            >
          </div>
          <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-2">PHỎNG VẤN</span>
          <h3 class="font-title text-[24px] md:text-[28px] font-bold text-gray-900 mb-3 hover:underline cursor-pointer">
            Trò Chuyện Cùng: Giám Đốc Thiết Kế
          </h3>
          <p class="text-[14px] text-gray-500 leading-relaxed mb-4 font-normal">
            Cái nhìn sâu sắc về quy trình sáng tạo, nguồn cung ứng bền vững và tương lai của thời trang chậm cùng người dẫn dắt tầm nhìn của chúng tôi.
          </p>
          <div>
            <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b border-black pb-1 hover:opacity-70 transition-opacity">
              ĐỌC PHỎNG VẤN
            </a>
          </div>
        </article>
      </div>

    </div>

    <!-- Row 3: Bottom Full-Width Wide Post -->
    <div class="border-t border-gray-100 pt-16 mb-16">
      <article class="flex flex-col items-center text-center max-w-[900px] mx-auto">
        <div class="w-full aspect-[21/9] bg-gray-50 border border-gray-100 overflow-hidden mb-8">
          <img 
            src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=1200&auto=format&fit=crop" 
            alt="Tiêu Điểm Chất Liệu: Vải Linen Hữu Cơ" 
            class="w-full h-full object-cover transition-transform duration-700 hover:scale-103"
          >
        </div>
        <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-2">BỀN VỮNG</span>
        <h3 class="font-title text-[28px] md:text-[36px] font-bold text-gray-900 mb-4 hover:underline cursor-pointer">
          Tiêu Điểm Chất Liệu: Vải Linen Hữu Cơ
        </h3>
        <p class="text-[14px] text-gray-500 leading-relaxed mb-6 font-normal max-w-[650px]">
          Tìm hiểu về vòng đời của chất liệu vải thoáng khí nhất của chúng tôi, cùng cam kết hướng tới sản xuất không rác thải.
        </p>
        <div>
          <a href="#" class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b border-black pb-1 hover:opacity-70 transition-opacity">
            ĐỌC BÀI VIẾT
          </a>
        </div>
      </article>
    </div>

    <!-- Load More Button -->
    <div class="flex justify-center border-t border-gray-100 pt-12">
      <button class="border border-black hover:bg-black hover:text-white transition-colors duration-300 py-3.5 px-10 text-[12px] font-bold uppercase tracking-[2px] cursor-pointer">
        TẢI THÊM BÀI VIẾT
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeCategory = ref('TẤT CẢ')

const categories = ref([
  'TẤT CẢ',
  'XU HƯỚNG',
  'HƯỚNG DẪN PHONG CÁCH',
  'HẬU TRƯỜNG'
])
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
