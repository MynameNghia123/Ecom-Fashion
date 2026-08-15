<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="show"
        class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
        @click.self="emit('cancel')"
      >
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[560px] animate-modal-in flex flex-col max-h-[90vh]">

          <!-- Header -->
          <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line v-if="mode === 'add'" x1="12" y1="5" x2="12" y2="19"/><line v-if="mode === 'add'" x1="5" y1="12" x2="19" y2="12"/>
                  <path v-if="mode === 'edit'" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path v-if="mode === 'edit'" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </div>
              <h2 class="text-base font-bold text-slate-800">
                {{ mode === 'add' ? 'Thêm nhân viên mới' : 'Chỉnh sửa nhân viên' }}
              </h2>
            </div>
            <button type="button" @click="emit('cancel')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="px-7 py-6 overflow-y-auto space-y-4">
            <!-- Họ và tên -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                Họ và tên <span class="text-red-500">*</span>
              </label>
              <input
                id="input-staff-fullname"
                v-model="form.full_name"
                type="text"
                placeholder="Nguyễn Văn A"
                :class="['w-full px-3.5 py-2.5 text-sm border rounded-xl placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:outline-none transition-all duration-200', validationErrors.full_name ? 'border-red-500 text-red-600 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 text-slate-800 focus:border-black focus:ring-black/10']"
              />
              <p v-if="validationErrors.full_name" class="mt-1.5 text-xs text-red-500">{{ validationErrors.full_name[0] }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                id="input-staff-email"
                v-model="form.email"
                type="email"
                placeholder="nhanvien@example.com"
                :class="['w-full px-3.5 py-2.5 text-sm border rounded-xl placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:outline-none transition-all duration-200', validationErrors.email ? 'border-red-500 text-red-600 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 text-slate-800 focus:border-black focus:ring-black/10']"
              />
              <p v-if="validationErrors.email" class="mt-1.5 text-xs text-red-500">{{ validationErrors.email[0] }}</p>
            </div>

            <!-- Số điện thoại -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
              <input
                id="input-staff-phone"
                v-model="form.phone_number"
                type="text"
                placeholder="090 123 4567"
                :class="['w-full px-3.5 py-2.5 text-sm border rounded-xl placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:outline-none transition-all duration-200', validationErrors.phone_number ? 'border-red-500 text-red-600 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 text-slate-800 focus:border-black focus:ring-black/10']"
              />
              <p v-if="validationErrors.phone_number" class="mt-1.5 text-xs text-red-500">{{ validationErrors.phone_number[0] }}</p>
            </div>

            <!-- Mật khẩu -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                Mật khẩu
                <span v-if="mode === 'add'" class="text-red-500">*</span>
                <span v-else class="text-xs font-normal text-slate-400 ml-1">(để trống nếu không đổi)</span>
              </label>
              <input
                id="input-staff-password"
                v-model="form.password"
                type="password"
                placeholder="••••••••"
                :class="['w-full px-3.5 py-2.5 text-sm border rounded-xl placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:outline-none transition-all duration-200', validationErrors.password ? 'border-red-500 text-red-600 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 text-slate-800 focus:border-black focus:ring-black/10']"
              />
              <p v-if="validationErrors.password" class="mt-1.5 text-xs text-red-500">{{ validationErrors.password[0] }}</p>
            </div>

            <!-- Xác nhận Mật khẩu -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                Xác nhận mật khẩu
                <span v-if="mode === 'add'" class="text-red-500">*</span>
              </label>
              <input
                id="input-staff-password-confirmation"
                v-model="form.password_confirmation"
                type="password"
                placeholder="••••••••"
                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200"
              />
            </div>

            <!-- Avatar URL -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">Avatar (URL)</label>
              <input
                id="input-staff-avatar"
                v-model="form.avatar"
                type="text"
                placeholder="https://example.com/avatar.jpg"
                :class="['w-full px-3.5 py-2.5 text-sm border rounded-xl placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:outline-none transition-all duration-200', validationErrors.avatar ? 'border-red-500 text-red-600 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 text-slate-800 focus:border-black focus:ring-black/10']"
              />
              <p v-if="validationErrors.avatar" class="mt-1.5 text-xs text-red-500">{{ validationErrors.avatar[0] }}</p>
              <div v-if="form.avatar" class="mt-2 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 bg-slate-100">
                  <img :src="form.avatar" alt="Preview avatar" class="w-full h-full object-cover" />
                </div>
                <p class="text-xs text-slate-400">Xem trước avatar</p>
              </div>
            </div>

            <!-- Vai trò -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">Vai trò (Roles)</label>
              <div class="grid grid-cols-2 gap-2 p-3 border border-slate-200 rounded-xl bg-slate-50">
                <div v-for="role in roles" :key="role.id" class="flex items-center gap-2">
                  <label class="inline-flex items-center gap-2 cursor-pointer text-xs select-none">
                    <input
                      type="checkbox"
                      class="rounded border-slate-300 text-black focus:ring-black/20 cursor-pointer"
                      :value="role.id"
                      v-model="form.role_ids"
                    />
                    <span class="font-medium text-slate-700">{{ role.name }}</span>
                  </label>
                </div>
              </div>
            </div>


            <!-- Trạng thái -->
            <div>
              <label class="block text-sm font-semibold text-slate-600 mb-1.5">Trạng thái</label>
              <div class="relative">
                <select
                  id="select-staff-status"
                  v-model="form.is_active"
                  class="w-full appearance-none px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 cursor-pointer pr-10"
                >
                  <option :value="true">Hoạt động</option>
                  <option :value="false">Ngưng hoạt động</option>
                </select>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </span>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
            <button
              type="button"
              @click="emit('cancel')"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
            >
              Hủy
            </button>
            <button
              id="btn-submit-staff"
              type="button"
              :disabled="isSubmitting"
              @click="handleSubmit"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm shadow-md shadow-neutral-200 hover:shadow-neutral-300 transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:active:scale-100"
            >
              <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
              {{ isSubmitting ? 'Đang lưu...' : (mode === 'add' ? 'Thêm nhân viên' : 'Lưu thay đổi') }}
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { reactive, watch, ref } from 'vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  mode: { type: String, default: 'add' }, // 'add' | 'edit'
  staffData: { type: Object, default: null },
  roles: { type: Array, default: () => [] },
  permissions: { type: Array, default: () => [] },
})

const emit = defineEmits(['submit', 'cancel'])
const isSubmitting = ref(false)

const form = reactive({
  id: null,
  full_name: '',
  email: '',
  phone_number: '',
  password: '',
  password_confirmation: '',
  avatar: '',
  is_active: true,
  role_ids: [],
})

const resetForm = () => {
  form.id = null
  form.full_name = ''
  form.email = ''
  form.phone_number = ''
  form.password = ''
  form.password_confirmation = ''
  form.avatar = ''
  form.is_active = true
  form.role_ids = []
  validationErrors.value = {}
}

// Đồng bộ form khi modal được mở
watch(() => props.show, (isOpen) => {
  if (!isOpen) {
    isSubmitting.value = false
    return
  }
  if (props.mode === 'edit' && props.staffData) {
    form.id = props.staffData.id
    form.full_name = props.staffData.full_name || ''
    form.email = props.staffData.email || ''
    form.phone_number = props.staffData.phone_number || ''
    form.password = ''
    form.password_confirmation = ''
    form.avatar = props.staffData.avatar || ''
    form.is_active = props.staffData.is_active
    form.role_ids = props.staffData.roles ? props.staffData.roles.map(r => r.id) : []
  } else {
    resetForm()
  }
})

const validationErrors = ref({})

const handleSubmit = () => {
  isSubmitting.value = true
  validationErrors.value = {}
  emit('submit', {
    formData: { ...form },
    done: () => { isSubmitting.value = false },
    setErrors: (errs) => { validationErrors.value = errs }
  })
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
