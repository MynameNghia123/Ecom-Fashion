<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
        @click.self="$emit('close')"
      >
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[480px] animate-modal-in">

          <!-- Header -->
          <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </div>
              <h2 class="text-base font-bold text-slate-800">Chi tiết danh mục</h2>
            </div>
            <button @click="$emit('close')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Content -->
          <div class="px-7 py-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
                <p class="text-sm font-mono font-semibold text-slate-700">{{ category?.id }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Danh mục cha</p>
                <p class="text-sm font-semibold text-[#0258cb]">
                  {{ category?.parent_id ? getCategoryName(category.parent_id) : '—' }}
                </p>
              </div>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tên danh mục</p>
              <p class="text-sm font-bold text-slate-800">{{ category?.name }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Slug</p>
              <p class="text-sm font-mono text-slate-600">{{ category?.slug }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mô tả</p>
              <p class="text-sm text-slate-600 leading-relaxed">{{ category?.description || 'Chưa có mô tả.' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
                <p class="text-xs text-slate-600">{{ category?.created_at }}</p>
              </div>
              <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cập nhật</p>
                <p class="text-xs text-slate-600">{{ category?.updated_at }}</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
            <button
              @click="$emit('close')"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
            >Đóng</button>
            <button
              @click="$emit('edit', category)"
              class="px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all duration-150"
            >Chỉnh sửa</button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  show:            { type: Boolean,   default: false },
  category:        { type: Object,    default: null },
  getCategoryName: { type: Function,  default: () => '—' },
})

defineEmits(['close', 'edit'])
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
