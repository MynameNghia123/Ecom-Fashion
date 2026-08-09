<template>
  <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
  :class="show ? '' : 'hidden'"
  >
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[540px] animate-modal-in flex flex-col max-h-[90vh]">

      <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50">
            <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
          </div>
          <h2 class="text-base font-bold text-slate-800">
            {{ typeOfAction === 'add' ? 'Thêm mới nhà phân phối' : 'Chỉnh sửa nhà phân phối' }}
          </h2>
        </div>
        <button 
          @click="emit('cancel')"
          class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <div class="px-7 py-6 overflow-y-auto space-y-5">

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Tên nhà phân phối <span class="text-red-500">*</span>
          </label>
          <input
            id="input-supplier-name"
            type="text"
            v-model="form.name"
            placeholder="Vd: Cty TNHH TechPro"
            class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 border-slate-200 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
            :class="fieldError('name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
          />
          <p v-if="fieldError('name')" class="text-xs text-red-500 mt-1">{{ fieldError('name') }}</p>
        </div>


        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Số điện thoại <span class="text-red-500">*</span>
            </label>
            <input
            v-model="form.phone"
              id="input-supplier-phone"
              type="text"
              placeholder="0987 123 456"
              class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 border-slate-200 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
              :class="fieldError('phone') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"
            />
            <p v-if="fieldError('phone') " class="text-xs text-red-500 mt-1">{{ fieldError('phone')  }}</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
            <input
              id="input-supplier-email"
              v-model="form.email"
              type="email"
              placeholder="contact@company.vn"
              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
              :class="fieldError('email')  ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"

              />
            <p v-if="fieldError('email')" class="text-xs text-red-500 mt-1">{{ fieldError('email') }}</p>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Địa chỉ</label>
          <textarea
            id="input-supplier-address"
            v-model="form.address"
            rows="2"
            placeholder="123 Đường Láng, Đống Đa, Hà Nội"
            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 resize-none"
              :class="fieldError('address') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'"

           >
          </textarea>
            <p v-if="fieldError('address')" class="text-xs text-red-500 mt-1">{{ fieldError('address') }}</p>
        </div>

  
        <div class="flex items-center justify-between py-3 px-4 bg-slate-50 rounded-xl border border-slate-100">
          <div>
            <p class="text-sm font-semibold text-slate-700">Trạng thái hoạt động</p>
            <p class="text-xs text-slate-400 mt-0.5">Cho phép nhà phân phối tham gia vào hệ thống.</p>
          </div>
          <button
            @click="form.is_active = form.is_active === 1 ? 0 : 1"
            type="button"
            :class="[
                  'relative inline-flex w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none',
                  form.is_active === 1 ? 'bg-black' : 'bg-gray-300'
                ]"
              >
            <span 
              :class="[
                'inline-block w-5 h-5 mt-0.5 rounded-full bg-white transition-transform duration-200',
                form.is_active === 1 ? 'translate-x-5' : 'translate-x-0.5'
              ]"
            ></span>
        </button>
        </div>

      </div>

      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button 
          @click="emit('cancel'); console.log('Cancel button clicked')"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
          Hủy bỏ
        </button>
        <button
          :disabled="isSubmitting"
          @click="handleSubmit()"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:active:scale-100">
          <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
          </svg>
          <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          <span>{{ isSubmitting ? 'Đang cập nhật...' : (props.typeOfAction === 'add' ? 'Thêm mới' : 'Chỉnh sửa') }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { defineProps, defineEmits, reactive, ref, watch } from 'vue'
import { useSupplierValidation } from '@/composables/admin/validation/useSupplierValidation'


const { formErrors, validate, clearErrors, applyBackendErrors, fieldError } = useSupplierValidation()
const emit = defineEmits(['submit', 'cancel'])
const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  typeOfAction: {
    type: String,
    default: 'add'
  },
  supplierData: {
    type: Object,
    default: () => ({})
  },
})
const form = reactive({
  name: props.supplierData?.name || '',
  phone: props.supplierData?.phone || '',
  email: props.supplierData?.email || '',
  address: props.supplierData?.address || '',
  is_active: props.supplierData?.is_active ?? 1,
})
const isSubmitting = ref(false)

watch(
  () => props.show,
  (newVal) => {
    isSubmitting.value = false;
    if (!newVal) return;
    clearErrors();
  // console.log('SupplierForm props:', props.supplierData)
  if (newVal && props.typeOfAction === 'update') {
    form.name = props.supplierData.name || ''
    form.phone = props.supplierData.phone || ''
    form.email = props.supplierData.email || ''
    form.address = props.supplierData.address || ''
    form.is_active = props.supplierData?.is_active ?? 1
  } else if (newVal && props.typeOfAction === 'add') {
    form.name = ''
    form.phone = ''
    form.email = ''
    form.address = ''
    form.is_active = 1
  }

}, { deep: true })

const handleSubmit = () => {
  if (!validate(form)) return  // dừng nếu có lỗi
  isSubmitting.value = true;
  emit('submit', { 
    formData: form, 
    applyBackendErrors: (e) => {
      isSubmitting.value = false;
      applyBackendErrors(e);
    } 
  })
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

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>