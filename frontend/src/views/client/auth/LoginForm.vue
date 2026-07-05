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

      <!-- Submit Button -->
      <button 
        type="submit" 
        class="w-full bg-[#eaeaea] hover:bg-black hover:text-white text-black font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none"
      >
        Đăng nhập
      </button>
    </form>

    <!-- Lost Password Link -->
    <div class="text-center mt-6">
      <a href="#" class="text-[12px] text-neutral-600 hover:text-black font-text underline">Quên mật khẩu?</a>
    </div>

    <!-- Footer Consent -->
    <p class="text-[11px] text-neutral-450 leading-relaxed text-center mt-6 font-text">
      Bằng cách tiếp tục, bạn chấp nhận các Quy định Website, Quy chế giao dịch và Chính sách bảo mật của chúng tôi.
    </p>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const emit = defineEmits(['success'])

const loginForm = reactive({
  username: '',
  password: '',
  remember: false
})

const handleLogin = () => {
  alert(`[Mock Client] Đăng nhập: ${loginForm.username}`)
  emit('success', { ...loginForm })
}
</script>
