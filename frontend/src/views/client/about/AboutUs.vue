<template>
  <div class="w-full bg-white text-black py-12 px-5 lg:px-20 font-text">
    
    <!-- Title & Intro Section -->
    <div class="text-center mb-12 border-b border-gray-100 pb-8">
      <h1 class="font-title text-[48px] md:text-[60px] font-bold tracking-wide mb-6 text-gray-900 uppercase">
        VỀ CHÚNG TÔI
      </h1>
      <p class="text-[14px] md:text-[16px] text-gray-500 max-w-[700px] mx-auto leading-relaxed">
        Chào mừng bạn đến với Luxury. Chúng tôi tin rằng thời trang cao cấp không chỉ là trang phục, mà là ngôn ngữ thể hiện phong thái cá nhân độc bản và phong cách sống tinh tế.
      </p>
    </div>

    <!-- Banner Section -->
    <div class="w-full aspect-[21/9] min-h-[350px] bg-gray-50 border border-gray-100 overflow-hidden mb-16 relative group">
      <img 
        src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1600&auto=format&fit=crop" 
        alt="Về Luxury" 
        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-103"
      >
      <div class="absolute inset-0 bg-black/10 transition-opacity duration-500 group-hover:opacity-20"></div>
    </div>

    <!-- Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start max-w-[1200px] mx-auto mb-16">
      
      <!-- Left Column: Story -->
      <div class="lg:col-span-7 flex flex-col gap-6">
        <div>
          <h2 class="text-[13px] font-bold uppercase tracking-[2px] text-gray-400 mb-4">
            CÂU CHUYỆN THƯƠNG HIỆU
          </h2>
          <p class="text-[15px] text-gray-600 leading-[1.8] font-normal mb-6">
            Được thành lập từ khao khát mang đến những thiết kế mang đậm tính đương đại, Luxury tập trung vào việc kiến tạo những trang phục tối giản nhưng đầy chiều sâu. Chúng tôi tin rằng vẻ đẹp thực sự nằm ở sự giản đơn được trau chuốt tỉ mỉ.
          </p>
          <p class="text-[15px] text-gray-600 leading-[1.8] font-normal mb-6">
            Mỗi bộ sưu tập của Luxury là hành trình tìm kiếm sự cân bằng giữa chất liệu tự nhiên cao cấp và những đường cắt may phóng khoáng, hiện đại. Chúng tôi không chạy theo xu hướng nhất thời, mà hướng tới những giá trị bền vững và trường tồn cùng thời gian.
          </p>
          <p class="text-[15px] text-gray-600 leading-[1.8] font-normal">
            Chúng tôi tự hào khi mang đến cho khách hàng không chỉ là sản phẩm chất lượng cao, mà còn là một trải nghiệm thời trang trọn vẹn, tôn vinh cá tính độc bản của mỗi người mặc.
          </p>
        </div>
      </div>
      
      <!-- Right Column: Info / Vision & Values -->
      <div class="lg:col-span-5 bg-[#fafafa] border border-gray-200 p-8 md:p-10 flex flex-col gap-8">
        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            TRIẾT LÝ THIẾT KẾ
          </h3>
          <p class="text-[14px] text-gray-800 font-medium leading-relaxed">
            Sự kết hợp hài hòa giữa cấu trúc phom dáng hiện đại và sự mềm mại của chất liệu tự nhiên cao cấp.
          </p>
        </div>

        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            CAM KẾT CHẤT LƯỢNG
          </h3>
          <p class="text-[14px] text-gray-800 font-medium leading-relaxed">
            Quy trình sản xuất được kiểm soát nghiêm ngặt, từ việc tuyển chọn sợi vải hữu cơ đến từng mũi chỉ hoàn thiện cuối cùng.
          </p>
        </div>

        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            ĐỊA CHỈ TRỰC TIẾP
          </h3>
          <p class="text-[14px] text-gray-800 font-medium leading-relaxed">
            Luxury Flagship Store<br>
            428 Soho Architecture Loft, NY 10012<br>
            Điện thoại: +44 20 7946 0123
          </p>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
// Trang giới thiệu tĩnh của Luxury
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
