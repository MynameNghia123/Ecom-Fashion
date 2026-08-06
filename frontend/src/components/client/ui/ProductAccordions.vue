<template>
  <div class="border-t border-gray-200">
    <!-- Description Accordion -->
    <div class="border-b border-gray-200">
      <button 
        @click="toggleAccordion('description')"
        class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
      >
        <span>MÔ TẢ</span>
        <span class="text-[18px] font-normal font-mono">{{ accordions.description ? '-' : '+' }}</span>
      </button>
      <div 
        v-show="accordions.description" 
        class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
        v-html="product.description"
      ></div>
    </div>

    <!-- Usage Accordion -->
    <div class="border-b border-gray-200">
      <button 
        @click="toggleAccordion('hdsd')"
        class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
      >
        <span>HƯỚNG DẪN SỬ DỤNG SẢN PHẨM</span>
        <span class="text-[18px] font-normal font-mono">{{ accordions.hdsd ? '-' : '+' }}</span>
      </button>
      <div 
        v-show="accordions.hdsd" 
        class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
      >
        <ul class="list-disc pl-5 space-y-1">
          <li>Giặt máy ở chế độ nhẹ nhàng, nhiệt độ nước không quá 30°C.</li>
          <li>Không sử dụng chất tẩy rửa mạnh.</li>
          <li>Phơi trong bóng râm, tránh ánh nắng trực tiếp.</li>
          <li>Ủi ở nhiệt độ thấp hoặc vừa phải.</li>
        </ul>
      </div>
    </div>

    <!-- Shipping Accordion -->
    <div class="border-b border-gray-200">
      <button 
        @click="toggleAccordion('giaohang')"
        class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
      >
        <span>GIAO HÀNG / ĐỔI HÀNG</span>
        <span class="text-[18px] font-normal font-mono">{{ accordions.giaohang ? '-' : '+' }}</span>
      </button>
      <div 
        v-show="accordions.giaohang" 
        class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
      >
        Giao hàng miễn phí toàn quốc cho đơn hàng từ 1.000.000đ. Hỗ trợ đổi trả trong vòng 30 ngày kể từ ngày nhận hàng với điều kiện sản phẩm còn nguyên tem mác và chưa qua sử dụng. Vui lòng liên hệ bộ phận CSKH để được hỗ trợ chi tiết.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  product: {
    type: Object,
    required: true
  }
})

const accordions = ref({
  description: true,
  hdsd: false,
  giaohang: false
})

const toggleAccordion = (section) => {
  accordions.value[section] = !accordions.value[section]
}
</script>
