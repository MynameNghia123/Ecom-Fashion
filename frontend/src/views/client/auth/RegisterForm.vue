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

      <!-- Submit Button -->
      <button 
        type="submit" 
        class="w-full bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4 mt-8 transition-colors duration-300 uppercase cursor-pointer border-none"
      >
        Đăng ký
      </button>
    </form>

    <!-- Footer Consent -->
    <p class="text-[11px] text-neutral-455 leading-relaxed text-center mt-6 font-text">
      Bằng cách tiếp tục, bạn chấp nhận Chính sách bảo mật và Điều khoản dịch vụ của NURFIA.
    </p>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const emit = defineEmits(['success'])

const registerForm = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  password: ''
})

const handleRegister = () => {
  alert(`[Mock Client] Đăng ký: ${registerForm.firstName} ${registerForm.lastName} (${registerForm.email})`)
  emit('success', { ...registerForm })
}
</script>
