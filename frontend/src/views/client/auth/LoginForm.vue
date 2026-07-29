<template>
  <div>
    <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Đăng nhập</h2>
    <form @submit.prevent="handleLogin" class="space-y-6">
      <!-- Username/Email -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Tên đăng nhập hoặc địa chỉ email *</label>
        <input 
          type="text" 
          v-model="loginForm.username"
          required
          class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Password -->
      <div class="relative">
        <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Mật khẩu *</label>
        <input 
          type="password" 
          v-model="loginForm.password"
          required
          class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
        />
      </div>

      <!-- Remember Me -->
      <div class="flex items-center">
        <label class="flex items-center text-xs text-neutral-600 font-text cursor-pointer select-none">
          <input 
            type="checkbox" 
            v-model="loginForm.remember"
            class="mr-2 accent-black w-4 h-4 border border-neutral-300 rounded-sm"
          />
          Ghi nhớ đăng nhập
        </label>
      </div>

      <!-- Error Message -->
      <p v-if="authStore.error" class="text-xs text-red-500 font-text">{{ authStore.error }}</p>

      <!-- Submit Button -->
      <button 
        type="submit" 
        :disabled="authStore.loading"
        class="w-full bg-[#eaeaea] hover:bg-black hover:text-white disabled:bg-neutral-100 disabled:text-neutral-450 disabled:cursor-not-allowed text-black font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none flex items-center justify-center gap-2"
      >
        <svg v-if="authStore.loading" class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ authStore.loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}</span>
      </button>
    </form>

    <!-- Lost Password Link -->
    <div class="text-center mt-6">
      <a 
        href="#" 
        @click.prevent="emit('forgot-password')" 
        class="text-[12px] text-neutral-600 hover:text-black font-text underline"
      >
        Quên mật khẩu?
      </a>
    </div>

    <!-- Footer Consent -->
    <p class="text-[11px] text-neutral-450 leading-relaxed text-center mt-6 font-text">
      Bằng cách tiếp tục, bạn chấp nhận các Quy định Website, Quy chế giao dịch và Chính sách bảo mật của chúng tôi.
    </p>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'

const emit = defineEmits(['success', 'forgot-password'])
const authStore = useClientAuthStore()

const loginForm = reactive({
  username: '',
  password: '',
  remember: false
})

onMounted(() => {
  authStore.clearError()
})

const handleLogin = async () => {
  const result = await authStore.login({
    email: loginForm.username,
    password: loginForm.password
  })
  if (result.success) {
    emit('success')
  }
}
</script>
