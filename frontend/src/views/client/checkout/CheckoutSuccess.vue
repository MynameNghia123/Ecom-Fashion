<template>
  <div class="max-w-[800px] mx-auto px-5 py-16 md:py-24 flex flex-col items-center">
    <!-- Success Icon -->
    <div class="w-16 h-16 rounded-full border border-emerald-500 flex items-center justify-center text-emerald-500 mb-8 bg-emerald-50/30 animate-scale-in">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="4 12 9 17 20 6" class="animate-draw-check"></polyline>
      </svg>
    </div>

    <!-- Thank You Header -->
    <h1 class="font-title text-3xl md:text-4xl font-normal text-black text-center mb-4 tracking-[0.5px]">Cảm ơn bạn đã đặt hàng!</h1>
    <p class="font-text text-sm text-neutral-600 text-center leading-relaxed max-w-[500px] mb-12">
      Đơn hàng <span class="font-semibold text-black">#NURFIA12345</span> của bạn đã được tiếp nhận.<br>
      Một email xác nhận chi tiết đã được gửi đến địa chỉ của bạn.
    </p>

    <!-- Divider -->
    <div class="w-full h-px bg-neutral-100 mb-10"></div>

    <!-- Summary Details Grid -->
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 mb-12">
      <!-- Shipping Info -->
      <div class="space-y-4">
        <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-neutral-400">Thông tin giao hàng</h3>
        <div class="space-y-1 font-text text-[13px] text-neutral-600 leading-relaxed">
          <p class="font-semibold text-black text-sm">Nguyễn Văn A</p>
          <p>123 Đường Lê Lợi, Phường Bến Thành</p>
          <p>Quận 1, TP. Hồ Chí Minh</p>
          <p>+84 90 123 4567</p>
        </div>
      </div>

      <!-- Payment Info -->
      <div class="space-y-4">
        <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-neutral-400">Phương thức thanh toán</h3>
        <div class="space-y-2">
          <div class="flex items-center gap-3 font-text text-[13px] text-neutral-850 leading-relaxed">
            <!-- Credit Card Icon -->
            <svg class="w-5 h-5 text-neutral-500 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
              <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            <div>
              <p class="font-semibold">Thanh toán qua Thẻ Quốc Tế</p>
              <p class="text-neutral-500 text-xs">(Visa/Mastercard)</p>
            </div>
          </div>
          <p class="font-text text-xs italic text-neutral-500 pl-8">Đã thanh toán thành công</p>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="w-full h-px bg-neutral-100 mb-10"></div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 w-full justify-center mb-16">
      <router-link 
        to="/profile" 
        class="bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4 px-8 uppercase transition-colors duration-300 text-center min-w-[220px] shadow-sm no-underline"
      >
        Xem đơn hàng của tôi
      </router-link>
      <router-link 
        to="/" 
        class="border border-neutral-300 hover:border-black bg-white hover:bg-neutral-50 text-black font-text text-[12px] font-bold tracking-wider py-4 px-8 uppercase transition-all duration-300 text-center min-w-[220px] shadow-sm no-underline"
      >
        Tiếp tục mua sắm
      </router-link>
    </div>

    <!-- Footer Image -->
    <div class="w-full max-w-[640px] aspect-[16/9] overflow-hidden rounded bg-neutral-50 border border-neutral-100 shadow-xs">
      <img 
        src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop" 
        alt="Success order" 
        class="w-full h-full object-cover grayscale contrast-105 brightness-95"
      />
    </div>
  </div>
</template>

<script setup>
// Trang hiển thị thông tin thành công tĩnh sau checkout
</script>

<style scoped>
@keyframes scaleIn {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes drawCheck {
  0% {
    stroke-dashoffset: 24;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.animate-scale-in {
  opacity: 0;
  animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-draw-check {
  stroke-dasharray: 24;
  stroke-dashoffset: 24;
  animation: drawCheck 0.4s ease-out 0.45s forwards;
}
</style>
