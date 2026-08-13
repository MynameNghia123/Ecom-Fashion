<template>
  <div>
    <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Đăng ký</h2>
    <form @submit.prevent="handleRegister" class="space-y-6">
      <!-- Names Grid -->
      <div class="grid grid-cols-2 gap-6">
        <div class="relative">
          <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Tên *</label>
          <input 
            type="text" 
            v-model="registerForm.firstName"
            required
            class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
          />
        </div>
        <div class="relative">
          <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Họ *</label>
          <input 
            type="text" 
            v-model="registerForm.lastName"
            required
            class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
          />
        </div>
      </div>

      <!-- Email -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Địa chỉ Email *</label>
        <input 
          type="email" 
          v-model="registerForm.email"
          required
          class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Phone -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Số điện thoại</label>
        <input 
          type="tel" 
          v-model="registerForm.phone"
          class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Password -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Mật khẩu *</label>
        <input 
          type="password" 
          v-model="registerForm.password"
          required
          class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Confirm Password -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Xác nhận mật khẩu *</label>
        <input 
          type="password" 
          v-model="registerForm.password_confirmation"
          required
          class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Error Message -->
      <p v-if="localError || authStore.error" class="text-xs text-red-500 font-text">{{ localError || authStore.error }}</p>

      <!-- Submit Button -->
      <button 
        type="submit" 
        :disabled="authStore.loading"
        class="w-full bg-black hover:bg-neutral-800 disabled:bg-neutral-100 disabled:text-neutral-450 disabled:cursor-not-allowed text-white font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none flex items-center justify-center gap-2"
      >
        <svg v-if="authStore.loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ authStore.loading ? 'Đang đăng ký...' : 'Đăng ký' }}</span>
      </button>
    </form>

    <!-- Footer Consent -->
    <p class="text-[11px] text-neutral-455 leading-relaxed text-center mt-6 font-text">
      Bằng cách tiếp tục, bạn chấp nhận Chính sách bảo mật và Điều khoản dịch vụ của NURFIA.
    </p>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, watch } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'

const emit = defineEmits(['success'])
const authStore = useClientAuthStore()

const localError = ref('')

const registerForm = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: ''
})

// Clear local error when user types
watch(() => [registerForm.password, registerForm.password_confirmation], () => {
  if (localError.value) localError.value = ''
})

onMounted(() => {
  authStore.clearError()
})

const handleRegister = async () => {
  // Validate password length
  if (registerForm.password.length < 8) {
    localError.value = 'Mật khẩu phải có ít nhất 8 ký tự.'
    return
  }

  // Validate password confirmation
  if (registerForm.password !== registerForm.password_confirmation) {
    localError.value = 'Mật khẩu xác nhận không khớp.'
    return
  }

  localError.value = ''

  const result = await authStore.register({
    first_name: registerForm.firstName,
    last_name: registerForm.lastName,
    email: registerForm.email,
    phone_number: registerForm.phone,
    password: registerForm.password,
    password_confirmation: registerForm.password_confirmation
  })
  if (result.success) {
    emit('success')
  }
}
</script>
