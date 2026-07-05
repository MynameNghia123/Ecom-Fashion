<template>
  <div class="max-w-[1200px] mx-auto px-5 py-12 lg:py-20">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">
      
      <!-- LEFT COLUMN: Forms -->
      <div class="w-full lg:w-[60%] space-y-12">
        
        <!-- 1. THÔNG TIN GIAO HÀNG -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center font-text">1</span>
            <h2 class="font-title text-[20px] md:text-[22px] tracking-[1px] text-black uppercase font-medium">Thông tin giao hàng</h2>
          </div>

          <div class="space-y-6">
            <!-- Full Name -->
            <div class="relative">
              <label class="block text-[10px] font-text uppercase tracking-wider text-neutral-400 font-semibold mb-1">Họ và tên</label>
              <input 
                type="text" 
                v-model="shippingForm.fullName" 
                placeholder="Nguyễn Văn A"
                class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 font-text"
              />
            </div>

            <!-- Address -->
            <div class="relative">
              <label class="block text-[10px] font-text uppercase tracking-wider text-neutral-400 font-semibold mb-1">Địa chỉ</label>
              <input 
                type="text" 
                v-model="shippingForm.address" 
                placeholder="Số nhà, tên đường, phường/xã"
                class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 font-text"
              />
            </div>

            <!-- City and Phone Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label class="block text-[10px] font-text uppercase tracking-wider text-neutral-400 font-semibold mb-1">Thành phố</label>
                <input 
                  type="text" 
                  v-model="shippingForm.city" 
                  placeholder="Hồ Chí Minh"
                  class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 font-text"
                />
              </div>
              <div class="relative">
                <label class="block text-[10px] font-text uppercase tracking-wider text-neutral-400 font-semibold mb-1">Số điện thoại</label>
                <input 
                  type="tel" 
                  v-model="shippingForm.phone" 
                  placeholder="+84 000 000 000"
                  class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 font-text"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- 2. PHƯƠNG THỨC VẬN CHUYỂN -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center font-text">2</span>
            <h2 class="font-title text-[20px] md:text-[22px] tracking-[1px] text-black uppercase font-medium">Phương thức vận chuyển</h2>
          </div>

          <div class="space-y-4">
            <!-- Standard Shipping -->
            <div 
              @click="shippingMethod = 'standard'"
              :class="[
                'flex items-center justify-between border p-5 rounded-lg cursor-pointer transition-all duration-300 select-none',
                shippingMethod === 'standard' ? 'border-black bg-neutral-50/50' : 'border-neutral-200 hover:border-neutral-400'
              ]"
            >
              <div class="flex items-center gap-4">
                <div class="w-4 h-4 rounded-full border border-neutral-300 flex items-center justify-center bg-white shrink-0">
                  <div v-show="shippingMethod === 'standard'" class="w-2.5 h-2.5 rounded-full bg-black"></div>
                </div>
                <div>
                  <p class="text-sm font-semibold text-neutral-900 font-text">GIAO HÀNG TIÊU CHUẨN</p>
                  <p class="text-xs text-neutral-400 font-text mt-0.5">3 - 5 ngày làm việc</p>
                </div>
              </div>
              <span class="text-sm font-semibold text-neutral-850 font-text">Miễn phí</span>
            </div>

            <!-- Express Shipping -->
            <div 
              @click="shippingMethod = 'express'"
              :class="[
                'flex items-center justify-between border p-5 rounded-lg cursor-pointer transition-all duration-300 select-none',
                shippingMethod === 'express' ? 'border-black bg-neutral-50/50' : 'border-neutral-200 hover:border-neutral-400'
              ]"
            >
              <div class="flex items-center gap-4">
                <div class="w-4 h-4 rounded-full border border-neutral-300 flex items-center justify-center bg-white shrink-0">
                  <div v-show="shippingMethod === 'express'" class="w-2.5 h-2.5 rounded-full bg-black"></div>
                </div>
                <div>
                  <p class="text-sm font-semibold text-neutral-900 font-text">GIAO HÀNG HỎA TỐC</p>
                  <p class="text-xs text-neutral-400 font-text mt-0.5">Trong vòng 24 giờ</p>
                </div>
              </div>
              <span class="text-sm font-semibold text-neutral-850 font-text">150.000đ</span>
            </div>
          </div>
        </div>

        <!-- 3. THANH TOÁN -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center font-text">3</span>
            <h2 class="font-title text-[20px] md:text-[22px] tracking-[1px] text-black uppercase font-medium">Thanh toán</h2>
          </div>

          <div class="border border-neutral-200 rounded-lg overflow-hidden">
            <!-- Tabs header -->
            <div class="flex border-b border-neutral-200 bg-neutral-50/50">
              <button 
                type="button"
                @click="paymentMethod = 'vnpay'"
                :class="[
                  'flex-1 py-4 px-6 text-xs font-bold uppercase tracking-wider font-text border-r border-neutral-200 transition-colors cursor-pointer border-none',
                  paymentMethod === 'vnpay' ? 'bg-white text-black border-b-2 border-b-black' : 'text-neutral-500 hover:text-black bg-transparent'
                ]"
              >
                VNPAY
              </button>
              <button 
                type="button"
                @click="paymentMethod = 'momo'"
                :class="[
                  'flex-1 py-4 px-6 text-xs font-bold uppercase tracking-wider font-text transition-colors cursor-pointer border-none',
                  paymentMethod === 'momo' ? 'bg-white text-black border-b-2 border-b-black' : 'text-neutral-500 hover:text-black bg-transparent'
                ]"
              >
                MOMO
              </button>
            </div>

            <!-- Tabs content -->
            <div class="p-6 bg-white min-h-[140px] flex items-center justify-center text-center">
              <div v-if="paymentMethod === 'vnpay'" class="space-y-2 animate-fade-in">
                <div class="w-14 h-8 bg-blue-50 text-[10px] font-bold text-blue-700 flex items-center justify-center border border-blue-200 rounded mx-auto select-none">VNPAY</div>
                <p class="text-sm font-semibold text-neutral-800 font-text mt-4">Cổng thanh toán VNPAY</p>
                <p class="text-xs text-neutral-500 font-text max-w-[420px] mx-auto leading-relaxed">
                  Bạn sẽ được chuyển hướng sang cổng VNPAY để quét mã QR hoặc nhập thông tin thẻ ATM/Tài khoản ngân hàng của bạn.
                </p>
              </div>
              <div v-else class="space-y-2 animate-fade-in">
                <div class="w-14 h-8 bg-pink-50 text-[10px] font-bold text-pink-655 flex items-center justify-center border border-pink-200 rounded mx-auto select-none">MOMO</div>
                <p class="text-sm font-semibold text-neutral-800 font-text mt-4">Ví điện tử MoMo</p>
                <p class="text-xs text-neutral-500 font-text max-w-[420px] mx-auto leading-relaxed">
                  Bạn sẽ được chuyển hướng sang ứng dụng ví MoMo để hoàn tất thanh toán của bạn qua ví điện tử.
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Order Summary -->
      <div class="w-full lg:w-[40%] bg-[#fafafa] border border-neutral-100 p-8 lg:p-10 sticky top-[100px] rounded">
        <h2 class="font-title text-[22px] md:text-[24px] tracking-[1.5px] text-black uppercase font-medium mb-8">Tóm tắt đơn hàng</h2>

        <!-- Product list -->
        <div class="space-y-6 mb-8 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin">
          <!-- Item 1 -->
          <div class="flex gap-4 items-center">
            <div class="w-16 h-20 bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200/50 rounded">
              <img 
                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop" 
                alt="Áo khoác wool dạ" 
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 flex justify-between items-start">
              <div class="space-y-1">
                <h4 class="text-[13px] font-bold text-black uppercase font-text">Áo khoác wool dạ</h4>
                <p class="text-xs text-neutral-400 font-text">Size: M | Black</p>
                <p class="text-xs text-neutral-500 font-text">Qty: 1</p>
              </div>
              <span class="text-[13px] font-semibold text-black font-text">4.500.000đ</span>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="flex gap-4 items-center">
            <div class="w-16 h-20 bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200/50 rounded">
              <img 
                src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=300&auto=format&fit=crop" 
                alt="Sơ mi lụa trắng" 
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 flex justify-between items-start">
              <div class="space-y-1">
                <h4 class="text-[13px] font-bold text-black uppercase font-text">Sơ mi lụa trắng</h4>
                <p class="text-xs text-neutral-400 font-text">Size: S | Ivory</p>
                <p class="text-xs text-neutral-500 font-text">Qty: 1</p>
              </div>
              <span class="text-[13px] font-semibold text-black font-text">2.200.000đ</span>
            </div>
          </div>
        </div>

        <!-- Pricing calculation -->
        <div class="border-t border-neutral-200/60 pt-6 space-y-3.5 font-text text-[13px] text-neutral-600 mb-6">
          <div class="flex justify-between">
            <span>Tạm tính</span>
            <span class="font-medium text-black">6.700.000đ</span>
          </div>
          <div class="flex justify-between text-rose-600">
            <span>Mã giảm giá (Coupon)</span>
            <span class="font-medium">-670.000đ (-10%)</span>
          </div>
          <div class="flex justify-between">
            <span>Vận chuyển</span>
            <span class="font-medium text-black">{{ shippingFeeText }}</span>
          </div>
        </div>

        <!-- Total Price -->
        <div class="border-t border-neutral-200/80 pt-6 flex justify-between items-end mb-8">
          <span class="font-title text-[24px] uppercase tracking-[1px] text-black">Tổng cộng</span>
          <span class="font-title text-[26px] font-bold text-black">{{ formattedTotal }}đ</span>
        </div>

        <!-- Order Button -->
        <button 
          @click="submitOrder"
          class="w-full bg-black hover:bg-neutral-800 text-white font-text text-[12px] font-bold tracking-wider py-4.5 uppercase transition-colors duration-300 text-center shadow-sm cursor-pointer border-none"
        >
          Hoàn tất đặt hàng
        </button>

        <!-- Terms Disclaimer -->
        <p class="text-[11px] text-neutral-400 leading-relaxed text-center mt-4 font-text">
          Bằng cách đặt hàng, bạn đồng ý với các <a href="#" class="underline text-neutral-500 hover:text-black font-text">Điều khoản Dịch vụ</a> của chúng tôi.
        </p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const shippingForm = reactive({
  fullName: '',
  address: '',
  city: '',
  phone: ''
})

const shippingMethod = ref('standard')
const paymentMethod = ref('vnpay')

const subtotal = 6700000
const discount = 670000

const shippingFee = computed(() => {
  return shippingMethod.value === 'express' ? 150000 : 0
})

const shippingFeeText = computed(() => {
  return shippingMethod.value === 'express' ? '150.000đ' : 'Miễn phí'
})

const total = computed(() => {
  return subtotal - discount + shippingFee.value
})

const formattedTotal = computed(() => {
  return total.value.toLocaleString('vi-VN')
})

const submitOrder = () => {
  // Chuyển hướng sang trang cảm ơn đặt hàng
  router.push('/checkout/success')
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Scrollbar thin styling */
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
