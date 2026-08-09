<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
        @click.self="handleCancel"
      >
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[560px] animate-modal-in flex flex-col max-h-[90vh]">

          <!-- Modal Header -->
          <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line v-if="mode === 'add'" x1="12" y1="5" x2="12" y2="19"/><line v-if="mode === 'add'" x1="5" y1="12" x2="19" y2="12"/>
                  <path v-if="mode === 'edit'" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path v-if="mode === 'edit'" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </div>
              <h2 class="text-base font-bold text-slate-800">
                {{ mode === 'add' ? 'Thêm danh mục mới' : 'Chỉnh sửa danh mục' }}
              </h2>
            </div>
            <button @click="handleCancel" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="px-7 py-6 overflow-y-auto space-y-5">

            <!-- Server error banner -->
            <div
              v-if="formServerError"
              class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"
            >
              <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              {{ formServerError }}
            </div>

            <!-- Row: Name + Slug -->
            <div class="grid grid-cols-2 gap-4">
              <!-- Name -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                  Tên danh mục <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-category-name"
                  v-model="form.name"
                  @input="autoSlug"
                  type="text"
                  placeholder="Vd: Thời trang nam"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
                  :class="fieldError('name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('name')" class="text-xs text-red-500 mt-1">{{ fieldError('name') }}</p>
              </div>

              <!-- Slug -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                  Slug <span class="text-red-500">*</span>
                </label>
                <input
                  id="input-category-slug"
                  v-model="form.slug"
                  type="text"
                  placeholder="thoi-trang-nam"
                  class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-600 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 font-mono"
                  :class="fieldError('slug') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
                />
                <p v-if="fieldError('slug')" class="text-xs text-red-500 mt-1">{{ fieldError('slug') }}</p>
              </div>
            </div>

            <!-- Parent Category -->
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Danh mục cha</label>
              <div class="relative">
                <select
                  id="select-category-parent"
                  v-model="form.parent_id"
                  class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                >
                  <option :value="null">— Không có (Danh mục gốc) —</option>
                  <option
                    v-for="cat in filterParents"
                    :key="cat.id"
                    :value="cat.id"
                    :disabled="mode === 'edit' && cat.id === form.id"
                  >{{ cat.name }}</option>
                </select>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </span>
              </div>
              <p v-if="fieldError('parent_id')" class="text-xs text-red-500 mt-1">{{ fieldError('parent_id') }}</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả</label>
              <textarea
                id="textarea-category-description"
                v-model="form.description"
                rows="3"
                placeholder="Nhập mô tả ngắn về danh mục này (tối đa 255 ký tự)..."
                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 resize-none leading-relaxed"
                :class="fieldError('description') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
              ></textarea>
              <div class="flex items-center justify-between mt-1">
                <p v-if="fieldError('description')" class="text-xs text-red-500">{{ fieldError('description') }}</p>
                <p class="text-xs text-slate-400 ml-auto">{{ form.description?.length || 0 }}/255</p>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
            <button
              @click="handleCancel"
              :disabled="formSubmitting"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150 disabled:opacity-50"
            >Hủy</button>
            <button
              id="btn-submit-category"
              @click="submitForm"
              :disabled="formSubmitting"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm shadow-md shadow-neutral-200 hover:shadow-neutral-300 transition-all duration-200 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
            >
              <svg v-if="formSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
              </svg>
              {{ formSubmitting ? 'Đang lưu...' : (mode === 'add' ? 'Thêm danh mục' : 'Lưu thay đổi') }}
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { useCategoryStore } from '@/stores/admin/categoryStore'
import { useCategoryValidation } from '@/composables/admin/validation/useCategoryValidation'

const props = defineProps({
  show:          { type: Boolean, default: false },
  mode:          { type: String,  default: 'add' }, // 'add' | 'edit'
  category:      { type: Object,  default: null },
  filterParents: { type: Array,   default: () => [] },
})

const emit = defineEmits(['saved', 'cancel'])

const categoryStore = useCategoryStore()
const { formErrors, validate, clearErrors, fieldError, applyBackendErrors } = useCategoryValidation()

const formSubmitting = ref(false)
const formServerError = ref('')

const form = reactive({
  id:          null,
  name:        '',
  slug:        '',
  description: '',
  parent_id:   null,
})

// ─── Slug auto-generate ────────────────────────────────────────────────────────
const generateSlug = (text) =>
  (text || '').toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
    .trim().replace(/\s+/g, '-')

const autoSlug = () => {
  if (props.mode === 'add') form.slug = generateSlug(form.name)
}

// ─── Reset / populate form khi modal mở ───────────────────────────────────────
watch(
  () => props.show,
  (val) => {
    if (!val) return
    clearErrors()
    formServerError.value = ''
    if (props.mode === 'edit' && props.category) {
      form.id          = props.category.id
      form.name        = props.category.name
      form.slug        = props.category.slug
      form.description = props.category.description ?? ''
      form.parent_id   = props.category.parent_id   ?? null
    } else {
      form.id          = null
      form.name        = ''
      form.slug        = ''
      form.description = ''
      form.parent_id   = null
    }
  }
)

const handleCancel = () => {
  if (formSubmitting.value) return
  emit('cancel')
}

const submitForm = async () => {
  formServerError.value = ''
  if (!validate(form)) return

  formSubmitting.value = true
  try {
    const payload = {
      name:        form.name.trim(),
      slug:        form.slug.trim(),
      description: form.description?.trim() || null,
      parent_id:   form.parent_id || null,
    }
    if (props.mode === 'add') {
      await categoryStore.createCategory(payload)
    } else {
      await categoryStore.updateCategory(form.id, payload)
    }
    emit('saved')
  } catch (e) {
    formServerError.value = applyBackendErrors(e)
  } finally {
    formSubmitting.value = false
  }
}
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
