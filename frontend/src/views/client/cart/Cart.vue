<template>
  <div class="max-w-[1200px] mx-auto px-5 py-10 lg:py-16">

    <!-- Breadcrumb -->
    <nav class="font-text text-[11px] text-neutral-400 uppercase tracking-wider mb-8 flex items-center gap-2">
      <router-link to="/" class="hover:text-black transition-colors no-underline text-neutral-400">Trang chủ</router-link>
      <span>/</span>
      <span class="text-black font-semibold">Giỏ hàng</span>
    </nav>

    <!-- Free Shipping Banner -->
    <div class="bg-neutral-50 border border-dashed border-neutral-300 px-6 py-4 flex items-center gap-3 text-[13px] text-neutral-600 font-text mb-10 rounded">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-neutral-500 shrink-0">
        <rect x="1" y="3" width="15" height="13"></rect>
        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
        <circle cx="5.5" cy="18.5" r="2.5"></circle>
        <circle cx="18.5" cy="18.5" r="2.5"></circle>
      </svg>
      Thêm <span class="font-semibold text-black mx-1">3.280.000đ</span> vào giỏ để được miễn phí vận chuyển!
    </div>

    <div class="flex flex-col xl:flex-row gap-10 xl:gap-16 items-start">

      <!-- LEFT: Product Table -->
      <div class="w-full xl:w-[65%] space-y-6">

        <!-- Table header -->
        <div class="hidden md:grid grid-cols-[1.5fr_1fr_1fr_1fr_40px] gap-4 pb-3 border-b border-neutral-200 font-text text-[10px] uppercase tracking-wider text-neutral-400 font-semibold">
          <span>Sản phẩm</span>
          <span class="text-center">Đơn giá</span>
          <span class="text-center">Số lượng</span>
          <span class="text-center">Thành tiền</span>
          <span></span>
        </div>

        <!-- Cart Item 1 -->
        <div class="grid grid-cols-1 md:grid-cols-[1.5fr_1fr_1fr_1fr_40px] gap-4 items-center border-b border-neutral-100 pb-6">
          <div class="flex items-center gap-4">
            <div class="w-[80px] h-[100px] bg-neutral-50 overflow-hidden shrink-0 border border-neutral-100 rounded">
              <img
                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop"
                alt="Áo thun tay ngắn"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="space-y-1.5">
              <h4 class="text-[13px] font-bold text-black uppercase tracking-wide leading-tight font-text">Áo thun tay ngắn dệt kim Textured</h4>
              <p class="text-xs text-neutral-400 font-text">Size: M | Màu: Xanh dương</p>
            </div>
          </div>
          <div class="text-center text-sm font-text text-neutral-700 font-medium">580.000đ</div>
          <div class="flex items-center justify-center gap-2">
            <button class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none">−</button>
            <span class="w-8 text-center text-sm font-text font-semibold">3</span>
            <button class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none">+</button>
          </div>
          <div class="text-center text-sm font-text font-semibold text-black">1.740.000đ</div>
          <div class="flex justify-end md:justify-center">
            <button class="text-neutral-300 hover:text-black transition-colors cursor-pointer bg-transparent border-none" aria-label="Xóa sản phẩm">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>

        <!-- Cart Item 2 -->
        <div class="grid grid-cols-1 md:grid-cols-[1.5fr_1fr_1fr_1fr_40px] gap-4 items-center border-b border-neutral-100 pb-6">
          <div class="flex items-center gap-4">
            <div class="w-[80px] h-[100px] bg-neutral-50 overflow-hidden shrink-0 border border-neutral-100 rounded">
              <img
                src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=300&auto=format&fit=crop"
                alt="Áo khoác Hoodie"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="space-y-1.5">
              <h4 class="text-[13px] font-bold text-black uppercase tracking-wide leading-tight font-text">Áo khoác Hoodie Run & Brunch</h4>
              <p class="text-xs text-neutral-400 font-text">Size: S | Màu: Trắng</p>
            </div>
          </div>
          <div class="text-center text-sm font-text text-neutral-700 font-medium">1.380.000đ</div>
          <div class="flex items-center justify-center gap-2">
            <button class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none">−</button>
            <span class="w-8 text-center text-sm font-text font-semibold">1</span>
            <button class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center transition-all cursor-pointer bg-white text-base leading-none">+</button>
          </div>
          <div class="text-center text-sm font-text font-semibold text-black">1.380.000đ</div>
          <div class="flex justify-end md:justify-center">
            <button class="text-neutral-300 hover:text-black transition-colors cursor-pointer bg-transparent border-none" aria-label="Xóa sản phẩm">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>

        <!-- Coupon + Clear All -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-4">
          <div class="flex flex-1 max-w-[360px] border border-neutral-200 rounded overflow-hidden">
            <input
              type="text"
              placeholder="Mã giảm giá"
              class="flex-1 px-4 py-3 text-sm outline-none bg-transparent font-text border-none"
            />
            <button class="bg-black hover:bg-neutral-800 text-white font-text text-[11px] font-bold tracking-wider px-6 uppercase transition-colors border-none cursor-pointer">
              Áp dụng
            </button>
          </div>
          <button class="border border-neutral-200 hover:border-black bg-transparent text-neutral-600 hover:text-black font-text text-[11px] font-bold tracking-wider py-3 px-6 uppercase transition-all cursor-pointer rounded">
            Xóa giỏ hàng
          </button>
        </div>

      </div>

      <!-- RIGHT: Cart Totals -->
      <div class="w-full xl:w-[35%] bg-[#fafafa] border border-neutral-100 p-8 rounded sticky top-[100px] space-y-6">
        <h2 class="font-title text-[22px] tracking-[1.5px] text-black uppercase font-medium border-b border-neutral-200 pb-5">Tổng giỏ hàng</h2>

        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm font-text text-neutral-600">
          <span>Tạm tính</span>
          <span class="font-semibold text-black">3.120.000đ</span>
        </div>

        <!-- Coupon applied (hidden by default - UI state) -->
        <div class="flex justify-between items-center text-sm font-text text-rose-600">
          <span>Giảm giá (SALE10)</span>
          <span class="font-semibold">-312.000đ</span>
        </div>

        <!-- Shipping options -->
        <div class="space-y-3 pt-2 border-t border-neutral-100">
          <p class="font-text text-[11px] uppercase tracking-wider text-neutral-400 font-semibold">Vận chuyển</p>
          <!-- Standard (selected) -->
          <div class="flex items-center gap-3 cursor-pointer select-none">
            <div class="w-4 h-4 rounded-full border border-black flex items-center justify-center bg-white shrink-0">
              <div class="w-2.5 h-2.5 rounded-full bg-black"></div>
            </div>
            <span class="text-sm font-text text-neutral-700">Tiêu chuẩn (3-5 ngày)</span>
            <span class="ml-auto text-sm font-text text-neutral-700 font-medium">Miễn phí</span>
          </div>
          <!-- Express -->
          <div class="flex items-center gap-3 cursor-pointer select-none">
            <div class="w-4 h-4 rounded-full border border-neutral-300 flex items-center justify-center bg-white shrink-0"></div>
            <span class="text-sm font-text text-neutral-500">Hỏa tốc (24 giờ)</span>
            <span class="ml-auto text-sm font-text text-neutral-500 font-medium">150.000đ</span>
          </div>
        </div>

        <!-- Total -->
        <div class="border-t border-neutral-200 pt-5 flex justify-between items-end">
          <span class="font-title text-[20px] uppercase tracking-[1px] text-black">Tổng cộng</span>
          <span class="font-title text-[24px] font-bold text-black">2.808.000đ</span>
        </div>

        <!-- Checkout button -->
        <router-link
          to="/checkout"
          class="block w-full bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4 uppercase transition-colors duration-300 text-center no-underline"
        >
          Tiến hành thanh toán
        </router-link>

        <!-- Trust icons -->
        <div class="flex justify-center gap-5 pt-2">
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="2" width="14" height="20" rx="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
          <svg class="w-7 h-7 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
// Giao diện tĩnh - chức năng sẽ kết nối API sau
</script>
