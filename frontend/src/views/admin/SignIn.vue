<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/admin/authStore'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('admin@ecomfashion.com')
const password = ref('password123')
const rememberMe = ref(false)
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')
const hasErrorShake = ref(false)

const triggerShake = () => {
  hasErrorShake.value = true
  setTimeout(() => {
    hasErrorShake.value = false
  }, 500)
}

const handleSignIn = async () => {
  errorMessage.value = ''
  
  if (!email.value.trim()) {
    errorMessage.value = 'Vui lòng nhập Email.'
    triggerShake()
    return
  }
  
  if (!password.value.trim()) {
    errorMessage.value = 'Vui lòng nhập mật khẩu.'
    triggerShake()
    return
  }
  
  isLoading.value = true
  
  try {
    await authStore.login({
      email: email.value.trim(),
      password: password.value.trim()
    })
    
    router.push('/admin/dashboard')
  } catch (e) {
    errorMessage.value = authStore.error || 'Tên đăng nhập hoặc mật khẩu không chính xác.'
    triggerShake()
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="signin-container min-h-screen w-full flex items-center justify-center p-4 relative overflow-hidden bg-[#f3f5fa]">
    <!-- Premium background glowing details -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-300/20 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-300/20 blur-[120px] pointer-events-none"></div>

    <!-- Sign In Card -->
    <div 
      class="w-full max-w-[460px] bg-white rounded-2xl border border-slate-100 shadow-[0_12px_40px_rgba(0,0,0,0.03)] p-8 md:p-10 z-10 transition-all duration-300 transform scale-100 hover:shadow-[0_16px_48px_rgba(0,0,0,0.06)]"
      :class="{ 'animate-shake': hasErrorShake }"
    >
      <!-- Brand Header -->
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold tracking-wider text-black mb-1">
          ECOM-FASHION
        </h1>
        <p class="text-sm font-medium text-slate-500">
          Hệ thống quản trị thương mại điện tử
        </p>
      </div>

      <!-- Error Alert Message -->
      <div 
        v-if="errorMessage" 
        class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-sm text-red-600 flex items-center gap-3 transition-all"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSignIn" class="space-y-5">
        <!-- Username Input -->
        <div>
          <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
            Email hoặc Tên đăng nhập
          </label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
              <!-- User Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
            </span>
            <input 
              id="username"
              v-model="email"
              type="text"
              placeholder="admin@ecomfashion.com"
              class="w-full pl-12 pr-4 py-3.5 bg-[#f0f2fa]/85 text-slate-800 placeholder-slate-400/80 border border-transparent rounded-xl focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 font-medium text-sm"
              :disabled="isLoading"
            />
          </div>
        </div>

        <!-- Password Input -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="text-sm font-semibold text-slate-700">
              Mật khẩu
            </label>
            <a 
              href="#" 
              class="text-xs font-semibold text-black hover:text-[#004bb3] transition-colors"
              @click.prevent
            >
              Quên mật khẩu?
            </a>
          </div>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
              <!-- Lock Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
              </svg>
            </span>
            <input 
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              class="w-full pl-12 pr-12 py-3.5 bg-[#f0f2fa]/85 text-slate-800 placeholder-slate-400/80 border border-transparent rounded-xl focus:bg-white focus:border-black focus:ring-4 focus:ring-black/10 focus:outline-none transition-all duration-200 font-medium text-sm"
              :disabled="isLoading"
            />
            <button 
              type="button"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
              @click="showPassword = !showPassword"
              :disabled="isLoading"
            >
              <!-- Eye / Eye Off Icon -->
              <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Keep Signed In Checkbox -->
        <div class="flex items-center mt-2">
          <label class="flex items-center gap-2.5 cursor-pointer select-none group">
            <input 
              v-model="rememberMe"
              type="checkbox"
              class="w-4 h-4 rounded border-slate-300 text-black focus:ring-black/20 cursor-pointer accent-black"
              :disabled="isLoading"
            />
            <span class="text-sm font-medium text-slate-500 group-hover:text-slate-700 transition-colors">
              Duy trì đăng nhập
            </span>
          </label>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit"
          class="w-full mt-2 py-3.5 px-5 bg-black hover:bg-neutral-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/15 hover:shadow-blue-500/25 flex items-center justify-center gap-2.5 disabled:opacity-80 disabled:cursor-not-allowed group active:scale-[0.98]"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="flex items-center gap-2">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang xử lý...
          </span>
          <span v-else class="flex items-center justify-center gap-2 w-full">
            <span>Đăng nhập</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" />
            </svg>
          </span>
        </button>
      </form>

      <!-- Divider -->
      <div class="border-t border-slate-100 my-7"></div>

      <!-- SSL Footer Info -->
      <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        </svg>
        <span class="tracking-wide">Truy cập được bảo mật bởi mã hóa SSL 256-bit</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Keyframes for shake animation on errors — kept here since Tailwind cannot define custom keyframes without config */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-6px); }
  40%, 80% { transform: translateX(6px); }
}

.animate-shake {
  animation: shake 0.4s ease-in-out;
}
</style>
