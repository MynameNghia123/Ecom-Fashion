<template>
        <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Đơn hàng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Theo dõi, xử lý và cập nhật trạng thái đơn hàng trong hệ thống</p>
      </div>
      <button
        id="btn-open-create-order"
        @click="emit('openCreateModal')"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        Tạo đơn hàng mới
      </button>
    </div>  
</template>
<script setup>
import { defineEmits } from 'vue';
const emit = defineEmits(['openCreateModal']);
</script>