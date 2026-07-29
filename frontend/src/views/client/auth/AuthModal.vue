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
          <!-- Subtle overlay to make it look premium -->
          <div class="absolute inset-0 bg-neutral-900/5"></div>
        </div>

        <!-- Success Toast (hiển thị ở góc dưới modal) -->
        <Transition name="toast-fade">
          <div
            v-if="successMessage"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 z-50 bg-black text-white text-[12px] font-text px-5 py-3 rounded shadow-lg whitespace-nowrap flex items-center gap-2"
          >
            <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ successMessage }}
          </div>
        </Transition>

        <!-- Right Panel: Forms -->
        <div class="w-full md:w-[55%] lg:w-[50%] flex flex-col justify-between bg-white relative overflow-hidden">
          <!-- Form Header & Fields (Scrollable area) -->
          <div class="grow p-8 lg:p-12 overflow-y-auto max-h-[510px] md:max-h-[550px] scrollbar-thin">
            <RegisterForm 
              v-if="mode === 'register'" 
              @success="handleAuthSuccess" 
            />
            <ForgotPasswordForm
              v-else-if="mode === 'forgot-password'"
              @submit="handleForgotPasswordSubmit"
              @back="mode = 'login'"
            />
            <OtpForm
              v-else-if="mode === 'otp'"
              @verify-success="handleOtpSuccess"
              @back="mode = 'forgot-password'"
            />
            <ResetPasswordForm
              v-else-if="mode === 'reset-password'"
              @submit="handleResetPasswordSubmit"
              @back="mode = 'login'"
            />
            <LoginForm 
              v-else 
              @success="handleAuthSuccess" 
              @forgot-password="mode = 'forgot-password'"
            />
          </div>

          <!-- Bottom bar (Toggle Mode) -->
          <div class="h-[70px] border-t border-neutral-100 flex items-center justify-center bg-[#fafafa] shrink-0">
            <p v-if="mode === 'register'" class="text-[13px] font-text text-neutral-600">
              Bạn đã có tài khoản? 
              <button @click="mode = 'login'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Đăng nhập ngay
              </button>
            </p>
            <p v-else-if="mode === 'forgot-password' || mode === 'otp' || mode === 'reset-password'" class="text-[13px] font-text text-neutral-600">
              Đã nhớ mật khẩu?
              <button @click="mode = 'login'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Đăng nhập ngay
              </button>
            </p>
            <p v-else class="text-[13px] font-text text-neutral-600">
              Bạn chưa có tài khoản? 
              <button @click="mode = 'register'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Đăng ký ngay
              </button>
            </p>
          </div>

        </div>

      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue'
import LoginForm from './LoginForm.vue'
import RegisterForm from './RegisterForm.vue'
import ForgotPasswordForm from './ForgotPasswordForm.vue'
import OtpForm from './OtpForm.vue'
import ResetPasswordForm from './ResetPasswordForm.vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  initialMode: {
    type: String,
    default: 'login' // 'login' or 'register'
  }
})

const emit = defineEmits(['close'])

const mode = ref(props.initialMode)
const successMessage = ref('')

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    mode.value = props.initialMode
    successMessage.value = ''
  }
})

const close = () => {
  emit('close')
}

const handleAuthSuccess = (data) => {
  close()
}

const handleForgotPasswordSubmit = (identifier) => {
  mode.value = 'otp'
}

const handleOtpSuccess = (code) => {
  mode.value = 'reset-password'
}

const handleResetPasswordSubmit = () => {
  successMessage.value = 'Mật khẩu đã được đặt lại thành công!'
  setTimeout(() => {
    successMessage.value = ''
    mode.value = 'login'
  }, 2000)
}
</script>

<style scoped>
/* Modal Transition */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

@keyframes modalIn {
  from {
    opacity: 0;
    transform: scale(0.96) translateY(8px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-modal-in {
  animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Custom minimal scrollbar for inner container */
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #e5e5e5;
  border-radius: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #ccc;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(10px);
}
</style>
