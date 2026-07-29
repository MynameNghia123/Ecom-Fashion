<template>
  <div>
    <h2 class="font-title text-[36px] font-normal text-black mb-4 tracking-[0.5px]">Quên mật khẩu</h2>
    
    <p class="text-[12px] text-neutral-500 font-text leading-relaxed mb-8">
      Vui lòng nhập địa chỉ email đã đăng ký. Chúng tôi sẽ gửi mã OTP để xác nhận đặt lại mật khẩu.
    </p>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Email Input -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">
          Địa chỉ email *
        </label>
        <input 
          type="email" 
          v-model="identifier"
          required
          placeholder="email@example.com"
          class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Error Message -->
      <div v-if="authStore.error" class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-600 font-text">
        {{ authStore.error }}
      </div>

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
        <span>{{ authStore.loading ? 'Đang gửi mã...' : 'Gửi mã OTP' }}</span>
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
const identifier = ref('')

onMounted(() => {
  authStore.clearError()
})

const handleSubmit = async () => {
  authStore.clearError()
  if (identifier.value.trim()) {
    const result = await authStore.forgotPassword(identifier.value.trim())
    if (result.success) {
      emit('submit', identifier.value.trim())
    }
  }
}
</script>
