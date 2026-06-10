<template>
  <!-- Backdrop -->
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @click.self="$emit('cancel')"
      >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>

        <!-- Dialog -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[420px] p-7 flex flex-col gap-5 animate-modal-in">
          <!-- Icon -->
          <div class="flex items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center border-4 border-red-100">
              <svg class="w-8 h-8 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/>
                <path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
              </svg>
            </div>
          </div>

          <!-- Text -->
          <div class="text-center space-y-1.5">
            <h3 class="text-lg font-bold text-slate-800">{{ title }}</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
              {{ message }}
              <span v-if="itemName" class="font-semibold text-slate-700">"{{ itemName }}"</span>
              {{ messageSuffix }}
            </p>
            <p class="text-xs text-red-500 font-medium mt-1">Hành động này không thể hoàn tác.</p>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-3 pt-1">
            <button
              type="button"
              class="flex-1 py-2.5 px-5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-200"
              @click="$emit('cancel')"
            >
              Hủy bỏ
            </button>
            <button
              type="button"
              class="flex-1 py-2.5 px-5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition-all duration-150 shadow-md shadow-red-200 hover:shadow-red-300 focus:outline-none focus:ring-2 focus:ring-red-300 active:scale-[0.98]"
              @click="$emit('confirm')"
            >
              {{ confirmLabel || 'Xóa' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Xác nhận xóa'
  },
  message: {
    type: String,
    default: 'Bạn có chắc chắn muốn xóa'
  },
  messageSuffix: {
    type: String,
    default: 'không?'
  },
  itemName: {
    type: String,
    default: ''
  },
  confirmLabel: {
    type: String,
    default: 'Xóa'
  }
})

defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

@keyframes modalIn {
  from {
    opacity: 0;
    transform: scale(0.94) translateY(8px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
</style>
