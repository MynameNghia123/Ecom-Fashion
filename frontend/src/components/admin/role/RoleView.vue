<template>
  <div v-if="show" class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="close"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[480px] animate-modal-in flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </div>
          <h2 class="text-base font-bold text-slate-800">Chi tiết vai trò</h2>
        </div>
        <button @click="close" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-7 py-6 space-y-4 overflow-y-auto">
        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-lg font-bold shrink-0" :class="data?.color || 'bg-blue-500'">
            {{ data?.name?.slice(0, 2).toUpperCase() }}
          </div>
          <div>
            <p class="text-base font-bold text-slate-800">{{ data?.name }}</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ data?.description }}</p>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
            <p class="text-sm font-mono font-semibold text-slate-700">#{{ data?.id }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Số nhân viên</p>
            <p class="text-sm font-bold text-slate-700">{{ data?.staffCount || 0 }} người</p>
          </div>
          <div class="bg-slate-50 rounded-xl px-4 py-3 col-span-2">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
            <p class="text-sm text-slate-700">{{ data?.createdAt }}</p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button @click="close" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Đóng</button>
        <button @click="edit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Chỉnh sửa
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  show: Boolean,
  data: Object
})
const emit = defineEmits(['close', 'edit'])

const close = () => {
  emit('close')
}

const edit = () => {
  emit('edit', props.data)
}
</script>

<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
