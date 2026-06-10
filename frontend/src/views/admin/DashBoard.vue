<template>
  <div class="dashboard-page space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-bold text-white tracking-wide">Trang Tổng Quan</h2>
      <span class="text-sm text-slate-400">Hôm nay: {{ currentDate }}</span>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-slate-900 border border-slate-850 rounded-xl space-y-2 shadow-sm">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Doanh thu tháng</span>
        <div class="text-2xl font-bold text-emerald-400">125.400.000 ₫</div>
        <p class="text-xs text-emerald-500/80">+14% so với tháng trước</p>
      </div>
      <div class="p-6 bg-slate-900 border border-slate-850 rounded-xl space-y-2 shadow-sm">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đơn hàng mới</span>
        <div class="text-2xl font-bold text-blue-400">342 Đơn</div>
        <p class="text-xs text-blue-500/80">+8% hôm nay</p>
      </div>
      <div class="p-6 bg-slate-900 border border-slate-850 rounded-xl space-y-2 shadow-sm">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sản phẩm bán chạy</span>
        <div class="text-2xl font-bold text-yellow-400">1,840 Items</div>
        <p class="text-xs text-yellow-500/80">Bộ sưu tập Hè 2026</p>
      </div>
    </div>

    <!-- Info Welcome Card -->
    <div class="p-8 bg-gradient-to-r from-blue-950/40 to-indigo-950/40 border border-blue-900/40 rounded-xl">
      <h3 class="text-lg font-semibold text-white mb-2">Chào mừng bạn trở lại, Trần Nghĩa!</h3>
      <p class="text-sm text-slate-300 leading-relaxed max-w-2xl">
        Hệ thống quản lý thời trang EcomFashion đã sẵn sàng. Bạn có thể sử dụng menu bên trái để quản lý sản phẩm, đơn hàng, khách hàng hoặc các cấu hình hệ thống khác.
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const currentDate = computed(() => {
  const d = new Date()
  return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`
})
</script>
