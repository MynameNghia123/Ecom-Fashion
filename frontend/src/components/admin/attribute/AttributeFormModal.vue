<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
        @click.self="$emit('cancel')"
      >
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[440px] animate-modal-in">

          <!-- Header -->
          <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">
              {{ mode === 'add' ? 'Thêm thuộc tính mới' : 'Chỉnh sửa thuộc tính' }}
            </h2>
            <button
              @click="$emit('cancel')"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="px-6 py-5">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tên thuộc tính</label>
            <input
              v-model="form.name"
              ref="inputRef"
              type="text"
              :placeholder="mode === 'add' ? 'Vd: Kích thước, Màu sắc...' : 'Nhập tên thuộc tính...'"
              @keyup.enter="handleSubmit"
              class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all"
              :class="errors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
            />
            <p v-if="errors.name" class="text-xs text-red-500 mt-1.5">{{ errors.name }}</p>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100">
            <button
              @click="$emit('cancel')"
              :disabled="isSubmitting"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all disabled:opacity-50"
            >Hủy</button>
            <button
              @click="handleSubmit"
              :disabled="isSubmitting"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm shadow-md shadow-neutral-200 transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <svg v-if="isSubmitting" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              {{ mode === 'add' ? 'Thêm thuộc tính' : 'Lưu thay đổi' }}
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, reactive, watch, nextTick } from 'vue'
import { useAttributeStore } from '@/stores/admin/attributeStore'
import { useAttributeValidation } from '@/composables/admin/validation/useAttributeValidation'

const props = defineProps({
  show:      { type: Boolean, default: false },
  mode:      { type: String,  default: 'add' }, // 'add' | 'edit'
  attribute: { type: Object,  default: null },
})

const emit = defineEmits(['saved', 'cancel'])

const store = useAttributeStore()
const { errors, validate, clearErrors, applyBackendErrors } = useAttributeValidation()

const isSubmitting = ref(false)
const inputRef = ref(null)
const form = reactive({ name: '' })

// Reset & populate form mỗi lần modal mở
watch(
  () => props.show,
  (val) => {
    if (!val) return
    clearErrors()
    form.name = props.mode === 'edit' && props.attribute ? props.attribute.name : ''
    nextTick(() => inputRef.value?.focus())
  }
)

const handleSubmit = async () => {
  if (!validate(form)) return
  isSubmitting.value = true
  try {
    if (props.mode === 'add') {
      await store.createAttribute(form.name.trim())
    } else {
      await store.updateAttribute(props.attribute.id, form.name.trim())
    }
    emit('saved')
  } catch (e) {
    applyBackendErrors(e)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.5, 0.64, 1) forwards;
}
</style>
