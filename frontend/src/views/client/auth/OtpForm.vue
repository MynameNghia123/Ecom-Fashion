<template>
  <div>
    <!-- Tiêu đề đồng bộ với LoginForm -->
    <h2 class="font-title text-[36px] font-normal text-black mb-4 tracking-[0.5px]">Xác thực OTP</h2>
    
    <p class="text-[12px] text-neutral-500 font-text leading-relaxed mb-8">
      Mã xác thực đã được gửi tới email/số điện thoại của bạn. Vui lòng nhập mã 6 số vào ô bên dưới.
    </p>

    <form @submit.prevent="handleVerifyOtp" class="space-y-6">
      <!-- Cụm 6 ô nhập mã OTP rời -->
      <div class="flex justify-between gap-2">
        <input 
          v-for="(digit, index) in 6" 
          :key="index"
          :id="'otp-' + index"
          type="text" 
          maxlength="1"
          v-model="otpDigits[index]"
          @input="handleInput(index, $event)"
          @keydown.delete="handleDelete(index, $event)"
          class="w-12 h-14 border border-neutral-200 text-center outline-none focus:border-black transition-colors bg-transparent text-xl font-bold text-neutral-800"
          required
        />
      </div>

      <!-- Bộ đếm ngược / Gửi lại mã -->
      <div class="text-center text-[12px] font-text py-2">
        <span v-if="countdown > 0" class="text-neutral-500">
          Gửi lại mã sau <strong class="text-black font-semibold">{{ countdown }}s</strong>
        </span>
        <button 
          v-else 
          type="button"
          @click="handleResendOtp"
          class="text-neutral-600 hover:text-black underline cursor-pointer font-medium bg-transparent border-none p-0"
        >
          Gửi lại mã OTP
        </button>
      </div>

      <!-- Submit Button đồng bộ 100% với LoginForm -->
      <button 
        type="submit" 
        class="w-full bg-[#eaeaea] hover:bg-black hover:text-white text-black font-text text-[12px] font-bold tracking-wider py-4 mt-4 transition-colors duration-300 uppercase cursor-pointer border-none"
      >
        Xác nhận mã
      </button>
    </form>

    <!-- Nút quay lại luồng trước -->
    <div class="text-center mt-6">
      <button 
        type="button" 
        @click="$emit('back')" 
        class="text-[12px] text-neutral-600 hover:text-black font-text underline bg-transparent border-none cursor-pointer"
      >
        Quay lại đăng nhập
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['verify-success', 'back'])

// Mảng chứa 6 chữ số của OTP
const otpDigits = reactive(['', '', '', '', '', ''])

// Thời gian đếm ngược (để 60 giây tĩnh)
const countdown = ref(60)
let timerInterval = null

// Tự động chuyển ô tiếp theo khi gõ chữ số
const handleInput = (index, event) => {
  const value = event.target.value
  // Chỉ cho phép nhập số
  if (!/^\d*$/.test(value)) {
    otpDigits[index] = ''
    return
  }

  if (value && index < 5) {
    const nextInput = document.getElementById(`otp-${index + 1}`)
    if (nextInput) nextInput.focus()
  }
}

// Tự động quay lại ô trước khi bấm nút Backspace/Delete
const handleDelete = (index, event) => {
  if (!otpDigits[index] && index > 0) {
    const prevInput = document.getElementById(`otp-${index - 1}`)
    if (prevInput) {
      prevInput.focus()
      otpDigits[index - 1] = '' // Xóa luôn số ở ô trước đó cho mượt
    }
  }
}

// Hàm đếm ngược thời gian gửi lại mã
const startCountdown = () => {
  countdown.value = 60
  clearInterval(timerInterval)
  timerInterval = setInterval(() => {
    if (countdown.value > 0) {
      countdown.value--
    } else {
      clearInterval(timerInterval)
    }
  }, 1000)
}

// Gửi lại mã OTP
const handleResendOtp = () => {
  alert('[Mock Client] Đã gửi lại mã OTP mới!')
  startCountdown()
}

// Xác nhận mã OTP
const handleVerifyOtp = () => {
  const finalCode = otpDigits.join('')
  alert(`[Mock Client] Xác thực mã OTP: ${finalCode}`)
  emit('verify-success', finalCode)
}

// Vòng đời đếm ngược
onMounted(() => {
  startCountdown()
})

onUnmounted(() => {
  clearInterval(timerInterval)
})
</script>