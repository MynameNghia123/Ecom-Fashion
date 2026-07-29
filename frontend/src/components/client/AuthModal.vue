<template>
  <transition name="modal-fade">
    <div v-if="isOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/60 backdrop-blur-xs" @click="close"></div>

      <!-- Modal Card -->
      <div class="relative bg-white w-full max-w-[960px] min-h-[580px] md:h-[620px] rounded flex overflow-hidden shadow-2xl z-10 max-md:flex-col animate-modal-in">
        <!-- Close Button -->
        <button 
          @click="close"
          class="absolute top-5 right-5 z-50 text-neutral-400 hover:text-black transition-colors cursor-pointer bg-transparent border-none"
          aria-label="Close modal"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>

        <!-- Left Panel: Brand/Model Image -->
        <div 
          class="hidden md:block md:w-[45%] lg:w-[50%] bg-cover bg-center relative shrink-0"
          style="background-image: url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop');"
        >
          <div class="absolute inset-0 bg-neutral-900/5"></div>
        </div>

        <!-- Right Panel: Forms -->
        <div class="w-full md:w-[55%] lg:w-[50%] flex flex-col justify-between bg-white relative overflow-hidden">
          <!-- Form content (Scrollable) -->
          <div class="grow p-8 lg:p-12 overflow-y-auto max-h-[510px] md:max-h-[550px] scrollbar-thin">
            
            <!-- ── REGISTER FORM ── -->
            <div v-if="mode === 'register'">
              <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Register</h2>
              <form @submit.prevent="handleRegister" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                  <div>
                    <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">First Name *</label>
                    <input type="text" v-model="registerForm.firstName" required
                      class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                  </div>
                  <div>
                    <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Last Name *</label>
                    <input type="text" v-model="registerForm.lastName" required
                      class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                  </div>
                </div>
                <div>
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Email address *</label>
                  <input type="email" v-model="registerForm.email" required
                    class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                </div>
                <div>
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Phone Number</label>
                  <input type="tel" v-model="registerForm.phone"
                    class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                </div>
                <div>
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Password *</label>
                  <input type="password" v-model="registerForm.password" required
                    class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                </div>
                <p v-if="formError" class="text-xs text-red-500 font-text">{{ formError }}</p>
                <button type="submit" :disabled="authStore.loading"
                  class="w-full bg-black hover:bg-neutral-800 disabled:bg-neutral-300 text-white font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none flex items-center justify-center gap-2">
                  <svg v-if="authStore.loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                  {{ authStore.loading ? 'Đang đăng ký...' : 'Register' }}
                </button>
              </form>
              <p class="text-[11px] text-neutral-400 leading-relaxed text-center mt-6 font-text">
                By continuing, you accept the Privacy Policy and Terms of Service.
              </p>
            </div>

            <!-- ── LOGIN FORM ── -->
            <div v-else-if="mode === 'login'">
              <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Log in</h2>
              <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Email address *</label>
                  <input type="email" v-model="loginForm.email" required
                    class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                </div>
                <div>
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Password *</label>
                  <input type="password" v-model="loginForm.password" required
                    class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800" />
                </div>
                <div class="flex items-center">
                  <label class="flex items-center text-xs text-neutral-600 font-text cursor-pointer select-none">
                    <input type="checkbox" v-model="loginForm.remember" class="mr-2 accent-black w-4 h-4" />
                    Remember me
                  </label>
                </div>
                <p v-if="formError" class="text-xs text-red-500 font-text">{{ formError }}</p>
                <button type="submit" :disabled="authStore.loading"
                  class="w-full bg-[#eaeaea] hover:bg-black hover:text-white disabled:bg-neutral-200 text-black font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none flex items-center justify-center gap-2">
                  <svg v-if="authStore.loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                  {{ authStore.loading ? 'Đang đăng nhập...' : 'Log in' }}
                </button>
              </form>
              <div class="text-center mt-6">
                <button @click="mode = 'forgot'" class="text-[12px] text-neutral-600 hover:text-black font-text underline bg-transparent border-none cursor-pointer">
                  Quên mật khẩu?
                </button>
              </div>
              <p class="text-[11px] text-neutral-400 leading-relaxed text-center mt-4 font-text">
                By continuing, you accept the Privacy Policy.
              </p>
            </div>

            <!-- ── FORGOT PASSWORD FORM ── -->
            <div v-else-if="mode === 'forgot'">
              <ForgotPasswordForm @submit="onForgotSubmit" @back="mode = 'login'" />
            </div>

            <!-- ── OTP FORM ── -->
            <div v-else-if="mode === 'otp'">
              <OtpForm @verify-success="onOtpSuccess" @back="mode = 'forgot'" />
            </div>

            <!-- ── RESET PASSWORD FORM ── -->
            <div v-else-if="mode === 'reset'">
              <ResetPasswordForm @submit="onResetSuccess" @back="mode = 'login'" />
            </div>

          </div>

          <!-- Bottom bar (Toggle Login/Register) -->
          <div v-if="mode === 'login' || mode === 'register'"
            class="h-[70px] border-t border-neutral-100 flex items-center justify-center bg-[#fafafa] shrink-0">
            <p v-if="mode === 'register'" class="text-[13px] font-text text-neutral-600">
              Already have an account?
              <button @click="mode = 'login'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Log in instead
              </button>
            </p>
            <p v-else class="text-[13px] font-text text-neutral-600">
              Don't have an account yet?
              <button @click="mode = 'register'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Register Now
              </button>
            </p>
          </div>

          <!-- Back to login for OTP flow -->
          <div v-if="mode === 'forgot' || mode === 'otp' || mode === 'reset'"
            class="h-[70px] border-t border-neutral-100 flex items-center justify-center bg-[#fafafa] shrink-0">
            <button @click="mode = 'login'" class="text-[13px] font-text text-neutral-600 hover:text-black underline bg-transparent border-none cursor-pointer">
              ← Quay lại đăng nhập
            </button>
          </div>

        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'
import ForgotPasswordForm from '@/views/client/auth/ForgotPasswordForm.vue'
import OtpForm from '@/views/client/auth/OtpForm.vue'
import ResetPasswordForm from '@/views/client/auth/ResetPasswordForm.vue'

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  initialMode: { type: String, default: 'login' }
})
const emit = defineEmits(['close', 'login-success'])

const authStore = useClientAuthStore()
const mode = ref(props.initialMode)
const formError = ref('')

const registerForm = reactive({ firstName: '', lastName: '', email: '', phone: '', password: '' })
const loginForm = reactive({ email: '', password: '', remember: false })

watch(() => props.isOpen, (val) => {
  if (val) {
    mode.value = props.initialMode
    formError.value = ''
    authStore.clearError()
    Object.assign(registerForm, { firstName: '', lastName: '', email: '', phone: '', password: '' })
    Object.assign(loginForm, { email: '', password: '', remember: false })
  }
})

const close = () => emit('close')

// ── Handlers ──────────────────────────────────────────────────────────

const handleLogin = async () => {
  formError.value = ''
  const result = await authStore.login({ email: loginForm.email, password: loginForm.password })
  if (result.success) {
    emit('login-success')
    close()
  } else {
    formError.value = result.message
  }
}

const handleRegister = async () => {
  formError.value = ''
  const result = await authStore.register({
    first_name: registerForm.firstName,
    last_name: registerForm.lastName,
    email: registerForm.email,
    phone_number: registerForm.phone,
    password: registerForm.password,
    password_confirmation: registerForm.password
  })
  if (result.success) {
    emit('login-success')
    close()
  } else {
    formError.value = result.message
  }
}

// OTP Flow
const onForgotSubmit = () => { mode.value = 'otp' }
const onOtpSuccess = () => { mode.value = 'reset' }
const onResetSuccess = () => {
  mode.value = 'login'
  formError.value = ''
  // small toast handled by parent or user sees the mode changed
}
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in { animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e5e5; border-radius: 4px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #ccc; }

.font-title { font-family: var(--font-title, 'Playfair Display', serif); }
.font-text  { font-family: var(--font-text,  'Montserrat', sans-serif); }
</style>
