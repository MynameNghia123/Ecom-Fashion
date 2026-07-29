<template>
  <div>
    <h2 class="font-title text-[36px] font-normal text-black mb-4 tracking-[0.5px]">Đặt lại mật khẩu</h2>
    
    <p class="text-[12px] text-neutral-500 font-text leading-relaxed mb-8">
      Nhập mật khẩu mới cho tài khoản của bạn bên dưới.
    </p>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- New Password -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">
          Mật khẩu mới *
        </label>
        <input 
          type="password" 
          v-model="password"
          required
          class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Confirm New Password -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">
          Xác nhận mật khẩu mới *
        </label>
        <input 
          type="password" 
          v-model="confirmPassword"
          required
          class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Error message -->
      <p v-if="error || authStore.error" class="text-xs text-red-500 font-text">
        {{ error || authStore.error }}
      </p>

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
        <span>{{ authStore.loading ? 'Đang đặt lại...' : 'Đặt lại mật khẩu' }}</span>
      </button>
    </form>

    <!-- Back to Login Link -->
    <div class="text-center mt-6">
      <button 
        type="button" 
        @click="emit('back')" 
        class="text-[12px] text-neutral-600 hover:text-black font-text underline bg-transparent border-none cursor-pointer"
      >
        Quay lại đăng nhập
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'

const emit = defineEmits(['submit', 'back'])
const authStore = useClientAuthStore()

const password = ref('')
const confirmPassword = ref('')
const error = ref('')

onMounted(() => {
  authStore.clearError()
})

const handleSubmit = async () => {
  if (password.value !== confirmPassword.value) {
    error.value = 'Mật khẩu xác nhận không khớp.'
    return
  }
  error.value = ''
  
  const result = await authStore.resetPassword({
    password: password.value,
    password_confirmation: confirmPassword.value
  })
  
  if (result.success) {
    emit('submit')
  }
}
</script>
