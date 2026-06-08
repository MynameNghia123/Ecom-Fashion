<template>
  <div class="p-4 border border-gray-200 bg-gray-50 rounded-sm">
    <h4 class="text-[13px] font-bold uppercase mb-2">Tìm size phù hợp với cơ thể bạn</h4>
    
    <div class="grid grid-cols-2 gap-3 mb-3">
      <div>
        <label class="block text-[11px] text-gray-500 uppercase mb-1">Chiều cao (cm)</label>
        <input 
          type="number" 
          v-model.number="heightInput" 
          placeholder="Ví dụ: 172"
          class="w-full px-3 py-1.5 border border-gray-300 text-[13px] focus:outline-none focus:border-black bg-white"
        >
      </div>
      <div>
        <label class="block text-[11px] text-gray-500 uppercase mb-1">Cân nặng (kg)</label>
        <input 
          type="number" 
          v-model.number="weightInput" 
          placeholder="Ví dụ: 68"
          class="w-full px-3 py-1.5 border border-gray-300 text-[13px] focus:outline-none focus:border-black bg-white"
        >
      </div>
    </div>
    
    <div class="flex gap-4 items-center mb-3">
      <label class="text-[12px] font-semibold text-gray-700">Giới tính:</label>
      <label class="inline-flex items-center text-[12px] gap-1 cursor-pointer">
        <input type="radio" value="nam" v-model="genderInput" class="accent-black"> Nam
      </label>
      <label class="inline-flex items-center text-[12px] gap-1 cursor-pointer">
        <input type="radio" value="nu" v-model="genderInput" class="accent-black"> Nữ
      </label>
    </div>
    
    <div class="flex justify-between items-center bg-white p-2 border border-gray-200">
      <span class="text-[12px] text-gray-600 font-medium">Khuyên dùng cho bạn:</span>
      <span class="text-[14px] font-bold text-red-600 uppercase">{{ suggestedSize || 'Chưa đủ thông tin' }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const heightInput = ref(null)
const weightInput = ref(null)
const genderInput = ref('nam')

const suggestedSize = computed(() => {
  if (!heightInput.value || !weightInput.value) return ''

  const height = heightInput.value / 100 // convert to meters
  const weight = weightInput.value

  if (genderInput.value === 'nam') {
    if (height >= 1.60 && height <= 1.65 && weight >= 55 && weight <= 60) return 'S'
    if (height >= 1.64 && height <= 1.69 && weight >= 60 && weight <= 65) return 'M'
    if (height >= 1.70 && height <= 1.74 && weight >= 66 && weight <= 70) return 'L'
    if (height >= 1.74 && height <= 1.76 && weight >= 70 && weight <= 76) return 'XL'
    if (height >= 1.65 && height <= 1.77 && weight >= 76 && weight <= 80) return 'XXL'
    // fallback approximations
    if (weight > 80) return 'XXL'
    if (weight > 70) return 'XL'
    if (weight > 65) return 'L'
    if (weight > 60) return 'M'
    return 'S'
  } else {
    if (height >= 1.48 && height <= 1.53 && weight >= 38 && weight <= 43) return 'S'
    if (height >= 1.53 && height <= 1.55 && weight >= 43 && weight <= 46) return 'M'
    if (height >= 1.53 && height <= 1.58 && weight >= 46 && weight <= 53) return 'L'
    if (height >= 1.55 && height <= 1.62 && weight >= 53 && weight <= 57) return 'XL'
    if (height >= 1.55 && height <= 1.66 && weight >= 57 && weight <= 66) return 'XXL'
    // fallback approximations
    if (weight > 57) return 'XXL'
    if (weight > 53) return 'XL'
    if (weight > 46) return 'L'
    if (weight > 43) return 'M'
    return 'S'
  }
})
</script>
