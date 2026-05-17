<template>
  <div class="header-wrapper">
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="top-bar-left">
        <a href="#">THEO DÕI ĐƠN HÀNG</a>
        <a href="#">TÀI KHOẢN CỦA TÔI</a>
        <a href="#">VỀ CHÚNG TÔI</a>
      </div>
      <div class="top-bar-center">
        <span>GIẢM GIÁ MÙA HÈ CHO TẤT CẢ ĐỒ BƠI VÀ MIỄN PHÍ GIAO HÀNG QUỐC TẾ TỐC ĐỘ CAO</span>
      </div>
      <div class="top-bar-right">
        <a href="#">LIÊN HỆ</a>
        <div class="dropdown">
          <span>TIẾNG VIỆT <i class="chevron-down"></i></span>
        </div>
        <div class="dropdown">
          <span>VND <i class="chevron-down"></i></span>
        </div>
      </div>
    </div>

    <!-- Main Header -->
    <header class="main-header absolute-header">
      <div class="header-left">
        <button class="hamburger-menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="logo">
          <h1>NURFIA</h1>
        </div>
      </div>

      <nav class="main-nav">
        <ul>
          <li class="has-dropdown"><a href="#">TRANG CHỦ <i class="chevron-down"></i></a></li>
          <li class="has-dropdown"><a href="#">CỬA HÀNG <i class="chevron-down"></i></a></li>
          <li class="has-dropdown"><a href="#">DANH MỤC <i class="chevron-down"></i></a></li>
          <li class="has-dropdown"><a href="#">NỮ <i class="chevron-down"></i></a></li>
          <li><a href="#">NAM</a></li>
          <li><a href="#">TIN TỨC</a></li>
          <li><a href="#">LIÊN HỆ</a></li>
        </ul>
      </nav>

      <div class="header-right">
        <button class="icon-btn search-btn" aria-label="Tìm kiếm">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
        <button class="icon-btn wishlist-btn" aria-label="Yêu thích">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <span class="badge">0</span>
        </button>
        <button class="icon-btn user-btn" aria-label="Tài khoản">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </button>
        <button class="icon-btn compare-btn" aria-label="So sánh">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 21l6-6M9 8l-5 5-4-4M9 21v-6H3"></path></svg>
        </button>
        <button class="icon-btn cart-btn" aria-label="Giỏ hàng">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
          <span class="badge">3</span>
        </button>
      </div>
    </header>
  </div>
</template>

<script setup>
</script>
