<template>
  <div class="flex min-h-screen bg-[#f4f6f9] text-slate-800 font-['Inter',_-apple-system,_BlinkMacSystemFont,'Segoe_UI',Roboto,sans-serif]">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Area -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0 bg-[#f4f6f9]">

      <!-- Topbar -->
      <header class="h-16 bg-white border-b border-[#eef2f7] flex items-center justify-between px-7 sticky top-0 z-10 shrink-0">
        <!-- Search bar on the left -->
        <div class="relative flex items-center w-[360px]">
          <span class="absolute left-3.5 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
          </span>
          <input
            type="text"
            placeholder="Tìm kiếm hệ thống..."
            class="w-full h-[38px] pl-10 pr-4 text-[13px] font-medium rounded-full bg-[#f3f5f8] border border-transparent text-slate-800 placeholder-slate-400 outline-none transition-all duration-200 focus:bg-white focus:border-[#0258cb] focus:shadow-[0_0_0_4px_rgba(2,88,203,0.06)]"
          />
        </div>

        <!-- Topbar Right Actions -->
        <div class="flex items-center gap-3">
          <!-- Notification Bell -->
          <button class="relative bg-transparent border-0 p-2 rounded-full text-slate-500 cursor-pointer flex items-center justify-center transition-all duration-150 hover:text-slate-800 hover:bg-slate-100" title="Thông báo">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-[7px] h-[7px] rounded-full bg-red-500 border-[1.5px] border-white"></span>
          </button>

          <!-- Settings Gear -->
          <button class="relative bg-transparent border-0 p-2 rounded-full text-slate-500 cursor-pointer flex items-center justify-center transition-all duration-150 hover:text-slate-800 hover:bg-slate-100" title="Cấu hình">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"/>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
          </button>

          <!-- Help Icon -->
          <button class="relative bg-transparent border-0 p-2 rounded-full text-slate-500 cursor-pointer flex items-center justify-center transition-all duration-150 hover:text-slate-800 hover:bg-slate-100" title="Trợ giúp">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
          </button>

          <!-- Divider -->
          <div class="w-px h-6 bg-slate-200 mx-1"></div>

          <!-- Admin Profile Pill -->
          <div class="flex items-center gap-2.5 cursor-pointer p-1 rounded-lg transition-colors duration-150 hover:bg-slate-50">
            <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-xs font-bold text-[#0258cb] overflow-hidden border border-[#eef2f7] shrink-0">
              <img
                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80"
                alt="Avatar"
                class="w-full h-full object-cover"
                @error="handleAvatarError"
                v-if="avatarLoaded"
              />
              <span v-else>AD</span>
            </div>
            <div class="flex flex-col leading-tight">
              <span class="text-[13px] font-bold text-slate-800">Admin</span>
              <span class="text-[11px] font-medium text-slate-400">Quản trị</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 px-7 overflow-y-auto admin-content">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebar from '@/components/admin/layout/Sidebar.vue'

const avatarLoaded = ref(true)
const handleAvatarError = () => {
  avatarLoaded.value = false
}
</script>

<style>
@import '@/assets/admin/css/app.css';
</style>
