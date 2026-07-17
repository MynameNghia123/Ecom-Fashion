<template>
  <div
    class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
    :class="show ? '' : 'hidden'"
  >
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[560px] animate-modal-in flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50">
            <svg v-if="typeOfAction === 'add'" class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <svg v-else class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </div>
          <h2 class="text-base font-bold text-slate-800">
            {{ typeOfAction === 'add' ? 'Thêm nhân viên mới' : 'Chỉnh sửa nhân viên' }}
          </h2>
        </div>
        <button @click="emit('cancel')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-7 py-6 overflow-y-auto space-y-4">

        <!-- Avatar Upload -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-2">Ảnh đại diện</label>
          <div class="flex items-center gap-4">
            <!-- Preview -->
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white text-lg font-bold shrink-0 overflow-hidden border-2 border-slate-100">
              <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover" />
              <span v-else>{{ getInitials(form.full_name) }}</span>
            </div>

            <!-- Upload Controls -->
            <div class="flex-1">
              <input
                ref="fileInputRef"
                type="file"
                accept="image/jpeg,image/png,image/webp,image/gif"
                class="hidden"
                @change="handleFileChange"
              />
              <div class="flex gap-2 flex-wrap">
                <button
                  type="button"
                  @click="fileInputRef.click()"
                  :disabled="avatarUploading"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg border border-slate-200 text-slate-600 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="!avatarUploading" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                  </svg>
                  <svg v-else class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                  </svg>
                  {{ avatarUploading ? 'Đang tải...' : 'Chọn ảnh' }}
                </button>

                <button
                  v-if="avatarPreview"
                  type="button"
                  @click="removeAvatar"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-all duration-150"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  </svg>
                  Xóa ảnh
                </button>
              </div>
              <p class="text-[11px] text-slate-400 mt-1.5">JPEG, PNG, WebP, GIF • Tối đa 5MB</p>
              <p v-if="avatarError" class="text-xs text-red-500 mt-1">{{ avatarError }}</p>
            </div>
          </div>
        </div>

        <div class="border-t border-slate-100"></div>

        <!-- Họ và tên -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Họ và tên <span class="text-red-500">*</span>
          </label>
          <input
            id="input-staff-fullname"
            v-model="form.full_name"
            type="text"
            placeholder="Nguyễn Văn A"
            class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
            :class="fieldError('full_name') ? 'border-red-400 focus:border-red-400' : 'border-slate-200 focus:border-[#0258cb]'"
          />
          <p v-if="fieldError('full_name')" class="text-xs text-red-500 mt-1">{{ fieldError('full_name') }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            id="input-staff-email"
            v-model="form.email"
            type="email"
            placeholder="nhanvien@example.com"
            class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
            :class="fieldError('email') ? 'border-red-400 focus:border-red-400' : 'border-slate-200 focus:border-[#0258cb]'"
          />
          <p v-if="fieldError('email')" class="text-xs text-red-500 mt-1">{{ fieldError('email') }}</p>
        </div>

        <!-- Số điện thoại -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Số điện thoại <span class="text-red-500">*</span>
          </label>
          <input
            id="input-staff-phone"
            v-model="form.phone_number"
            type="text"
            placeholder="090 123 4567"
            class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
            :class="fieldError('phone_number') ? 'border-red-400 focus:border-red-400' : 'border-slate-200 focus:border-[#0258cb]'"
          />
          <p v-if="fieldError('phone_number')" class="text-xs text-red-500 mt-1">{{ fieldError('phone_number') }}</p>
        </div>

        <!-- Mật khẩu -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Mật khẩu
            <span v-if="typeOfAction === 'add'" class="text-red-500">*</span>
            <span v-else class="text-[11px] font-normal text-slate-400 ml-1">(để trống nếu không đổi)</span>
          </label>
          <div class="relative">
            <input
              id="input-staff-password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              class="w-full px-3.5 py-2.5 pr-10 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
              :class="fieldError('password') ? 'border-red-400 focus:border-red-400' : 'border-slate-200 focus:border-[#0258cb]'"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
            >
              <svg v-if="!showPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <p v-if="fieldError('password')" class="text-xs text-red-500 mt-1">{{ fieldError('password') }}</p>
        </div>

        <!-- Trạng thái (Toggle) -->
        <div class="flex items-center justify-between py-3 px-4 bg-slate-50 rounded-xl border border-slate-100">
          <div>
            <p class="text-sm font-semibold text-slate-700">Trạng thái hoạt động</p>
            <p class="text-xs text-slate-400 mt-0.5">Cho phép nhân viên đăng nhập vào hệ thống.</p>
          </div>
          <button
            @click="form.is_active = !form.is_active"
            type="button"
            :class="[
              'relative inline-flex w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none',
              form.is_active ? 'bg-[#0258cb]' : 'bg-gray-300'
            ]"
          >
            <span
              :class="[
                'inline-block w-5 h-5 mt-0.5 rounded-full bg-white transition-transform duration-200',
                form.is_active ? 'translate-x-5' : 'translate-x-0.5'
              ]"
            ></span>
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button
          @click="emit('cancel')"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150"
        >
          Hủy bỏ
        </button>
        <button
          @click="handleSubmit"
          :disabled="avatarUploading"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          <span>{{ typeOfAction === 'add' ? 'Thêm nhân viên' : 'Lưu thay đổi' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
import { useStaffValidation } from '@/composables/admin/validation/useStaffValidation'
import { uploadService } from '@/services/admin/uploadService'

const emit = defineEmits(['submit', 'cancel'])
const props = defineProps({
  show: { type: Boolean, default: false },
  typeOfAction: { type: String, default: 'add' },
  staffData: { type: Object, default: () => ({}) },
})

const { formErrors, validate, clearErrors, fieldError, applyBackendErrors } = useStaffValidation()

// ─── Form ───────────────────────────────────────────────────────────────────
const form = reactive({
  full_name: '',
  email: '',
  phone_number: '',
  password: '',
  avatar: '',
  is_active: true,
})

// ─── Avatar upload state ─────────────────────────────────────────────────────
const fileInputRef = ref(null)
const avatarPreview = ref('')
const avatarUploading = ref(false)
const avatarError = ref('')
const pendingDeletePath = ref('')  // đường dẫn ảnh cũ cần xóa khi submit

// ─── Toggle show password ────────────────────────────────────────────────────
const showPassword = ref(false)

// ─── Helpers ─────────────────────────────────────────────────────────────────
const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0][0].toUpperCase()
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}

// ─── Watch: khi modal mở, đổ dữ liệu vào form ────────────────────────────────
watch(
  () => props.show,
  (newVal) => {
    if (!newVal) return
    clearErrors()
    showPassword.value = false
    avatarError.value = ''
    pendingDeletePath.value = ''

    if (props.typeOfAction === 'update' && props.staffData) {
      form.full_name = props.staffData.full_name || ''
      form.email = props.staffData.email || ''
      form.phone_number = props.staffData.phone_number || ''
      form.password = ''
      form.avatar = props.staffData.avatar || ''
      form.is_active = props.staffData.is_active ?? true
      avatarPreview.value = props.staffData.avatar || ''
    } else {
      form.full_name = ''
      form.email = ''
      form.phone_number = ''
      form.password = ''
      form.avatar = ''
      form.is_active = true
      avatarPreview.value = ''
    }
  }
)

// ─── Avatar: chọn file và upload ngay lên storage ────────────────────────────
const handleFileChange = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  avatarError.value = ''

  // Validate size (5MB)
  if (file.size > 5 * 1024 * 1024) {
    avatarError.value = 'Ảnh không được vượt quá 5MB.'
    return
  }

  avatarUploading.value = true
  try {
    // Nếu đang có ảnh cũ (đã upload từ trước), đánh dấu để xóa sau khi submit
    if (form.avatar && !pendingDeletePath.value) {
      // extract path từ URL: lấy phần sau /storage/
      const match = form.avatar.match(/\/storage\/(.+)$/)
      if (match) pendingDeletePath.value = match[1]
    }

    const result = await uploadService.uploadImage(file, 'staffs')
    form.avatar = result.url
    avatarPreview.value = result.url
  } catch (err) {
    avatarError.value = err?.response?.data?.message || 'Upload ảnh thất bại.'
  } finally {
    avatarUploading.value = false
    // Reset input để có thể chọn lại cùng file
    if (fileInputRef.value) fileInputRef.value.value = ''
  }
}

// ─── Avatar: xóa ảnh hiện tại ────────────────────────────────────────────────
const removeAvatar = async () => {
  if (form.avatar) {
    const match = form.avatar.match(/\/storage\/(.+)$/)
    if (match) {
      await uploadService.deleteImage(match[1]).catch(() => {})
    }
  }
  form.avatar = ''
  avatarPreview.value = ''
  pendingDeletePath.value = ''
  avatarError.value = ''
}

// ─── Submit ───────────────────────────────────────────────────────────────────
const handleSubmit = () => {
  const mode = props.typeOfAction === 'add' ? 'add' : 'edit'
  if (!validate(form, mode)) return

  emit('submit', { formData: { ...form }, applyBackendErrors })
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
