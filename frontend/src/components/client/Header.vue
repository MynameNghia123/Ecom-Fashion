<template>
  <div class="relative w-full">
    <!-- Top Bar -->
    <div class="bg-black text-white flex justify-between items-center py-2.5 px-5 lg:px-20 text-[11px] font-text tracking-[0.5px] max-lg:flex-col max-lg:gap-2.5 max-lg:p-2.5">
      <div class="flex gap-5 items-center">
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">THEO DÕI ĐƠN HÀNG</a>
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">TÀI KHOẢN CỦA TÔI</a>
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">VỀ CHÚNG TÔI</a>
      </div>
      <div class="text-center max-lg:order-first">
        <span class="text-white font-semibold">GIẢM GIÁ MÙA HÈ CHO TẤT CẢ ĐỒ BƠI VÀ MIỄN PHÍ GIAO HÀNG QUỐC TẾ TỐC ĐỘ CAO</span>
      </div>
      <div class="flex gap-5 items-center">
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">LIÊN HỆ</a>
        <div class="flex items-center gap-1.25">
          <span class="text-white font-semibold cursor-pointer">TIẾNG VIỆT <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-1.25 mb-[2px]"></i></span>
        </div>
        <div class="flex items-center gap-1.25">
          <span class="text-white font-semibold cursor-pointer">VND <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-1.25 mb-[2px]"></i></span>
        </div>
      </div>
    </div>

    <!-- Main Header -->
    <header class="flex justify-between items-center py-[25px] px-5 lg:px-20 w-full absolute left-0 right-0 z-[100]">
      <div class="flex items-center gap-[25px]">
        <button class="bg-transparent border-none cursor-pointer flex flex-col gap-1.25 p-0">
          <span class="block w-6 h-[2px] bg-white transition-all duration-300"></span>
          <span class="block w-[18px] h-[2px] bg-white transition-all duration-300"></span>
          <span class="block w-6 h-[2px] bg-white transition-all duration-300"></span>
        </button>
        <div class="logo">
          <h1 class="font-title text-[32px] m-0 tracking-[2px] font-bold text-white">NURFIA</h1>
        </div>
      </div>

      <nav class="max-lg:hidden">
        <ul class="flex list-none m-0 p-0 gap-[30px]">
          <li class="relative"><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">TRANG CHỦ <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]"></i></a></li>
          <li class="relative"><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">CỬA HÀNG <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]"></i></a></li>
          <li class="relative"><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">DANH MỤC <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]"></i></a></li>
          <li class="relative"><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">NỮ <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]"></i></a></li>
          <li><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">NAM</a></li>
          <li><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">TIN TỨC</a></li>
          <li><a href="#" class="text-white no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-opacity duration-300 hover:opacity-70">LIÊN HỆ</a></li>
        </ul>
      </nav>

      <div class="flex items-center gap-5">
        <button class="bg-transparent border-none text-white cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" aria-label="Tìm kiếm">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
        <button class="bg-transparent border-none text-white cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" aria-label="Yêu thích">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <span class="absolute -top-0.5 -right-1.25 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
        </button>
        <button class="bg-transparent border-none text-white cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" aria-label="Tài khoản">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </button>
        <button class="bg-transparent border-none text-white cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" aria-label="So sánh">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 21l6-6M9 8l-5 5-4-4M9 21v-6H3"></path></svg>
        </button>
        <button class="bg-transparent border-none text-white cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" aria-label="Giỏ hàng">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
          <span class="absolute -top-0.5 -right-1.25 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">3</span>
        </button>
      </div>
    </header>
  </div>
</template>

<script setup>
</script>
