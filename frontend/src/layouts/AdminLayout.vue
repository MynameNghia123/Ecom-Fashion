<template>
  <div class="flex min-h-screen text-slate-800 font-['Inter',_-apple-system,_BlinkMacSystemFont,'Segoe_UI',Roboto,sans-serif]" style="background:#f5f5f5;">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Area -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0" style="background:#f5f5f5;">

      <!-- Topbar -->
      <header class="h-16 bg-white flex items-center justify-between px-7 sticky top-0 z-10 shrink-0" style="border-bottom:1px solid #e8e8e8;">
        <!-- Search bar -->
        <div class="relative flex items-center w-[360px]">
          <span class="absolute left-3.5 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
          </span>
          <input
            type="text"
            placeholder="Tìm kiếm hệ thống..."
            class="w-full h-[38px] pl-10 pr-4 text-[13px] font-medium rounded-full border text-slate-800 placeholder-neutral-400 outline-none transition-all duration-200"
            style="background:#f5f5f5; border-color:transparent;"
            @focus="e => { e.target.style.background='#fff'; e.target.style.borderColor='#111'; e.target.style.boxShadow='0 0 0 3px rgba(17,17,17,0.06)'; }"
            @blur="e => { e.target.style.background='#f5f5f5'; e.target.style.borderColor='transparent'; e.target.style.boxShadow='none'; }"
          />
        </div>

        <!-- Topbar Right Actions -->
        <div class="flex items-center gap-3">
          <!-- Notification Bell -->
          <button class="relative bg-transparent border-0 p-2 rounded-full text-neutral-500 cursor-pointer flex items-center justify-center transition-all duration-150 hover:text-neutral-900 hover:bg-neutral-100" title="Thông báo">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-[7px] h-[7px] rounded-full bg-red-500 border-[1.5px] border-white"></span>
          </button>

          <!-- Settings -->
          <button class="relative bg-transparent border-0 p-2 rounded-full text-neutral-500 cursor-pointer flex items-center justify-center transition-all duration-150 hover:text-neutral-900 hover:bg-neutral-100" title="Cấu hình">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"/>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
          </button>

          <!-- Divider -->
          <div class="w-px h-6 bg-neutral-200 mx-1"></div>

          <!-- Admin Profile Pill -->
          <div class="relative group">
            <div class="flex items-center gap-2.5 cursor-pointer p-1 rounded-lg transition-colors duration-150 hover:bg-neutral-50">
              <div class="w-9 h-9 rounded-full bg-neutral-100 flex items-center justify-center text-xs font-bold text-neutral-700 overflow-hidden border border-neutral-200 shrink-0">
                <img
                  v-if="authStore.user?.avatar"
                  :src="authStore.user.avatar"
                  alt="Avatar"
                  class="w-full h-full object-cover"
                />
                <span v-else>{{ getInitials(authStore.user?.full_name) || 'AD' }}</span>
              </div>
              <div class="flex flex-col leading-tight">
                <span class="text-[13px] font-bold text-slate-800">{{ authStore.user?.full_name || 'Admin' }}</span>
                <span class="text-[11px] font-medium text-neutral-400">Quản trị</span>
              </div>
            </div>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right scale-95 group-hover:scale-100 z-50">
              <div class="p-1.5">
                <button @click="handleLogout" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors text-left">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                  </svg>
                  Đăng xuất
                </button>
              </div>
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
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/admin/authStore'
import Sidebar from '@/components/admin/layout/Sidebar.vue'

const router = useRouter()
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/admin/signin')
}

const getInitials = (name) => {
  if (!name) return ''
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0][0].toUpperCase()
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}
</script>

<style>
@import '@/assets/admin/css/app.css';
</style>
