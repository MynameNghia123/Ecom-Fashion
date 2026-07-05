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

        <!-- Right Panel: Forms -->
        <div class="w-full md:w-[55%] lg:w-[50%] flex flex-col justify-between bg-white relative overflow-hidden">
          <!-- Form Header & Fields (Scrollable area) -->
          <div class="grow p-8 lg:p-12 overflow-y-auto max-h-[510px] md:max-h-[550px] scrollbar-thin">
            
            <!-- REGISTER FORM -->
            <div v-if="mode === 'register'">
              <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Register</h2>
              <form @submit.prevent="handleRegister" class="space-y-6">
                <!-- Names Grid -->
                <div class="grid grid-cols-2 gap-6">
                  <div class="relative">
                    <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">First Name *</label>
                    <input 
                      type="text" 
                      v-model="registerForm.firstName"
                      required
                      class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
                    />
                  </div>
                  <div class="relative">
                    <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Last Name *</label>
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
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Email address *</label>
                  <input 
                    type="email" 
                    v-model="registerForm.email"
                    required
                    class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
                  />
                </div>

                <!-- Phone -->
                <div class="relative">
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Phone Number</label>
                  <input 
                    type="tel" 
                    v-model="registerForm.phone"
                    class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
                  />
                </div>

                <!-- Password -->
                <div class="relative">
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Password *</label>
                  <input 
                    type="password" 
                    v-model="registerForm.password"
                    required
                    class="w-full border-b border-neutral-250 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
                  />
                </div>

                <!-- Submit Button -->
                <button 
                  type="submit" 
                  class="w-full bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none"
                >
                  Register
                </button>
              </form>

              <!-- Footer Consent -->
              <p class="text-[11px] text-neutral-450 leading-relaxed text-center mt-6 font-text">
                By continuing, you accept the Privacy Policy and Terms of Service of NURFIA.
              </p>
            </div>

            <!-- LOGIN FORM -->
            <div v-else>
              <h2 class="font-title text-[36px] font-normal text-black mb-8 tracking-[0.5px]">Log in</h2>
              <form @submit.prevent="handleLogin" class="space-y-6">
                <!-- Username/Email -->
                <div class="relative">
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Username or email address *</label>
                  <input 
                    type="text" 
                    v-model="loginForm.username"
                    required
                    class="w-full border border-neutral-200 px-3 py-2.5 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
                  />
                </div>

                <!-- Password -->
                <div class="relative">
                  <label class="block text-[11px] font-text uppercase tracking-wider text-neutral-500 font-semibold mb-1">Password *</label>
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
                    Remember me
                  </label>
                </div>

                <!-- Submit Button -->
                <button 
                  type="submit" 
                  class="w-full bg-[#eaeaea] hover:bg-black hover:text-white text-black font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none"
                >
                  Log in
                </button>
              </form>

              <!-- Lost Password Link -->
              <div class="text-center mt-6">
                <a href="#" class="text-[12px] text-neutral-600 hover:text-black font-text underline">Lost your password?</a>
              </div>

              <!-- Footer Consent -->
              <p class="text-[11px] text-neutral-450 leading-relaxed text-center mt-6 font-text">
                By continuing, you accept the Website Regulations, Regulations for the sale of alcoholic beverages and the Privacy Policy.
              </p>
            </div>

          </div>

          <!-- Bottom bar (Toggle Mode) -->
          <div class="h-[70px] border-t border-neutral-100 flex items-center justify-center bg-[#fafafa] shrink-0">
            <p v-if="mode === 'register'" class="text-[13px] font-text text-neutral-600">
              Already have an account? 
              <button @click="mode = 'login'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Log in instead
              </button>
            </p>
            <p v-else class="text-[13px] font-text text-neutral-600">
              You don't have an account yet? 
              <button @click="mode = 'register'" class="text-black font-semibold underline hover:no-underline ml-1 bg-transparent border-none cursor-pointer">
                Register Now
              </button>
            </p>
          </div>

        </div>

      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

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

const registerForm = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  password: ''
})

const loginForm = reactive({
  username: '',
  password: '',
  remember: false
})

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    mode.value = props.initialMode
    // Clear forms when modal opens
    registerForm.firstName = ''
    registerForm.lastName = ''
    registerForm.email = ''
    registerForm.phone = ''
    registerForm.password = ''
    
    loginForm.username = ''
    loginForm.password = ''
    loginForm.remember = false
  }
})

const close = () => {
  emit('close')
}

const handleLogin = () => {
  alert(`[Mock Client] Đăng nhập: ${loginForm.username}`)
  close()
}

const handleRegister = () => {
  alert(`[Mock Client] Đăng ký: ${registerForm.firstName} ${registerForm.lastName} (${registerForm.email})`)
  close()
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
</style>
