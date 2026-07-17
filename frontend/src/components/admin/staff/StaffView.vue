<template>
  <div
    class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
    :class="show ? '' : 'hidden'"
  >
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[520px] animate-modal-in flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </div>
          <h2 class="text-base font-bold text-slate-800">Chi tiết nhân viên</h2>
        </div>
        <button @click="emit('close')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-7 py-6 overflow-y-auto space-y-4">

        <!-- Avatar + Name + Status -->
        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white text-xl font-bold shrink-0 overflow-hidden">
            <img v-if="staffData?.avatar" :src="staffData.avatar" :alt="staffData.full_name" class="w-full h-full object-cover" />
            <span v-else>{{ getInitials(staffData?.full_name) }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-base font-bold text-slate-800 truncate">{{ staffData?.full_name }}</p>
            <p class="text-sm text-slate-500 truncate">{{ staffData?.email }}</p>
            <span
              class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
              :class="staffData?.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="staffData?.is_active ? 'bg-emerald-500' : 'bg-red-400'"></span>
              {{ staffData?.is_active ? 'Đang hoạt động' : 'Ngưng hoạt động' }}
            </span>
          </div>
          <button
            @click="emit('edit')"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-xs font-semibold hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 shrink-0"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Sửa
          </button>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
            <p class="text-sm font-mono font-semibold text-slate-700">#{{ staffData?.id }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Số điện thoại</p>
            <p class="text-sm font-mono text-slate-700">{{ staffData?.phone_number || '—' }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Đăng nhập cuối</p>
            <p class="text-xs text-slate-600">{{ staffData?.last_login_at || '—' }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
            <p class="text-xs text-slate-600">{{ staffData?.created_at || '—' }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3 col-span-2">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cập nhật lần cuối</p>
            <p class="text-xs text-slate-600">{{ staffData?.updated_at || '—' }}</p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button @click="emit('close')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
          Đóng
        </button>
        <button
          @click="emit('edit')"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all duration-150"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
          Chỉnh sửa
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineEmits(['close', 'edit'])
defineProps({
  show: { type: Boolean, default: false },
  staffData: { type: Object, default: () => ({}) },
})

const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0][0].toUpperCase()
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}
</script>

<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
