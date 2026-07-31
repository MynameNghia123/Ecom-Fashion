<template>
  <div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6">
    <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
      </svg>
    </div>
    <h1 class="text-4xl font-bold text-slate-800 mb-2">403</h1>
    <p class="text-xl font-semibold text-slate-600 mb-3">Truy cập bị từ chối</p>
    <p class="text-slate-400 max-w-md mb-8">
      Bạn không có quyền truy cập trang này. Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là nhầm lẫn.
    </p>
    <button
      @click="router.push('/admin/dashboard')"
      class="px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors"
    >
      Về trang Dashboard
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()
</script>
