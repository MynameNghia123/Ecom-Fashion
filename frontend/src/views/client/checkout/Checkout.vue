<template>
  <div class="max-w-[1200px] mx-auto px-5 py-12 lg:py-20 font-text">
    
    <!-- IF NOT AUTHENTICATED -->
    <div v-if="!authStore.isAuthenticated" class="max-w-[500px] mx-auto text-center py-16 space-y-6">
      <div class="w-16 h-16 bg-neutral-50 text-neutral-400 rounded-full flex items-center justify-center mx-auto border border-neutral-100">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
      <h2 class="text-xl font-bold uppercase tracking-wider text-neutral-800">Yêu cầu đăng nhập</h2>
      <p class="text-sm text-neutral-500 max-w-[360px] mx-auto">
        Bạn cần đăng nhập tài khoản thành viên để thực hiện đặt hàng và thanh toán.
      </p>
      <div class="pt-4 flex flex-col gap-3">
        <button 
          @click="isAuthModalOpen = true"
          class="w-full bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-xs py-4.5 transition-colors duration-300 border-none cursor-pointer"
        >
          Đăng nhập hoặc Đăng ký
        </button>
        <router-link 
          to="/cart"
          class="inline-block text-sm text-neutral-500 underline hover:text-black transition-colors"
        >
          Quay lại giỏ hàng
        </router-link>
      </div>

      <!-- Auth Modal Inline -->
      <AuthModal 
        :is-open="isAuthModalOpen" 
        @close="isAuthModalOpen = false" 
      />
    </div>

    <!-- IF AUTHENTICATED BUT CART EMPTY -->
    <div v-else-if="cartStore.isEmpty" class="max-w-[500px] mx-auto text-center py-16 space-y-6">
      <div class="w-16 h-16 bg-neutral-50 text-neutral-400 rounded-full flex items-center justify-center mx-auto border border-neutral-100">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
      </div>
      <h2 class="text-xl font-bold uppercase tracking-wider text-neutral-800">Giỏ hàng trống</h2>
      <p class="text-sm text-neutral-500">
        Không có sản phẩm nào trong giỏ để thực hiện thanh toán.
      </p>
      <div class="pt-4">
        <router-link 
          to="/"
          class="inline-block bg-black hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-xs px-8 py-4.5 transition-colors duration-300"
        >
          Tiếp tục mua hàng
        </router-link>
      </div>
    </div>

    <!-- MAIN CHECKOUT FORM -->
    <div v-else class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">
      
      <!-- LEFT COLUMN: Forms -->
      <form @submit.prevent="submitOrder" class="w-full lg:w-[60%] space-y-12">
        
        <!-- 1. THÔNG TIN GIAO HÀNG -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center">1</span>
            <h2 class="font-title text-[20px] md:text-[22px] tracking-[1px] text-black uppercase font-medium">Thông tin giao hàng</h2>
          </div>

          <div class="space-y-6">
            <!-- Full Name -->
            <div class="relative">
              <label class="block text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-1">Họ và tên người nhận</label>
              <input 
                type="text" 
                v-model="shippingForm.fullName" 
                required
                placeholder="Nguyễn Văn A"
                class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
              />
            </div>

            <!-- Street / Detail address -->
            <div class="relative">
              <label class="block text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-1">Số nhà, tên đường</label>
              <input 
                type="text" 
                v-model="shippingForm.address" 
                required
                placeholder="123 Đường Lê Lợi..."
                class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
              />
            </div>

            <!-- Province / District / Ward -->
            <ProvincePicker
              :initialProvince="shippingForm.province"
              :initialDistrict="shippingForm.district"
              :initialWard="shippingForm.ward"
              variant="checkout"
              :use-ghn="true"
              @change="onLocationChange"
            />

            <!-- Phone -->
            <div class="relative">
              <label class="block text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-1">Số điện thoại</label>
              <input 
                type="tel" 
                v-model="shippingForm.phone" 
                required
                placeholder="Ví dụ: 0912345678"
                class="w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800"
              />
            </div>
          </div>
        </div>

        <!-- 2. PHƯƠNG THỨC VẬN CHUYỂN -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center">2</span>
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
                  <p class="text-sm font-semibold text-neutral-900">GIAO HÀNG TIÊU CHUẨN</p>
                  <p class="text-xs text-neutral-400 mt-0.5">3 - 5 ngày làm việc</p>
                </div>
              </div>
              <span class="text-sm font-semibold text-neutral-800">Miễn phí</span>
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
                  <p class="text-sm font-semibold text-neutral-900">GIAO HÀNG HỎA TỐC</p>
                  <p class="text-xs text-neutral-400 mt-0.5">Trong vòng 24 giờ</p>
                </div>
              </div>
              <span class="text-sm font-semibold text-neutral-800">150.000đ</span>
            </div>
          </div>
        </div>

        <!-- 3. PHƯƠNG THỨC THANH TOÁN -->
        <div class="space-y-6">
          <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
            <span class="w-6 h-6 rounded-full bg-black text-white text-[11px] font-bold flex items-center justify-center">3</span>
            <h2 class="font-title text-[20px] md:text-[22px] tracking-[1px] text-black uppercase font-medium">Thanh toán</h2>
          </div>

          <div class="border border-neutral-200 rounded-lg overflow-hidden">
            <!-- Tabs header -->
            <div class="flex border-b border-neutral-200 bg-neutral-50/50">
              <button 
                type="button"
                @click="paymentMethod = 'cod'"
                :class="[
                  'flex-1 py-4 px-6 text-xs font-bold uppercase tracking-wider border-r border-neutral-200 transition-colors cursor-pointer border-none',
                  paymentMethod === 'cod' ? 'bg-white text-black border-b-2 border-b-black' : 'text-neutral-500 hover:text-black bg-transparent'
                ]"
              >
                COD (Nhận hàng trả tiền)
              </button>
              <button 
                type="button"
                @click="paymentMethod = 'vnpay'"
                :class="[
                  'flex-1 py-4 px-6 text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer border-none',
                  paymentMethod === 'vnpay' ? 'bg-white text-black border-b-2 border-b-black' : 'text-neutral-500 hover:text-black bg-transparent'
                ]"
              >
                VNPAY Sandbox
              </button>
            </div>

            <!-- Tabs content -->
            <div class="p-6 bg-white min-h-[140px] flex items-center justify-center text-center">
              <div v-if="paymentMethod === 'cod'" class="space-y-2 animate-fade-in">
                <div class="w-14 h-8 bg-neutral-100 text-[10px] font-bold text-neutral-700 flex items-center justify-center border border-neutral-300 rounded mx-auto select-none">COD</div>
                <p class="text-sm font-semibold text-neutral-800 mt-4">Thanh toán khi nhận hàng</p>
                <p class="text-xs text-neutral-500 max-w-[420px] mx-auto leading-relaxed">
                  Nhân viên giao nhận sẽ thu tiền mặt trực tiếp tại địa chỉ giao nhận khi giao kiện hàng cho quý khách.
                </p>
              </div>
              <div v-else class="space-y-2 animate-fade-in">
                <div class="w-14 h-8 bg-blue-50 text-[10px] font-bold text-blue-700 flex items-center justify-center border border-blue-200 rounded mx-auto select-none">VNPAY</div>
                <p class="text-sm font-semibold text-neutral-800 mt-4">Cổng thanh toán điện tử VNPAY</p>
                <p class="text-xs text-neutral-500 max-w-[420px] mx-auto leading-relaxed">
                  Hệ thống sẽ chuyển bạn sang cổng thanh toán VNPAY Sandbox để quét mã QR hoặc nhập thẻ test ngân hàng nội địa để thanh toán giả lập.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button hidden on desktop layout but handled by the sidebar button -->
        <button type="submit" ref="submitButtonRef" class="hidden">Submit</button>
      </form>

      <!-- RIGHT COLUMN: Order Summary -->
      <div class="w-full lg:w-[40%] bg-[#fafafa] border border-neutral-100 p-8 lg:p-10 sticky top-[100px] rounded">
        <h2 class="font-title text-[22px] md:text-[24px] tracking-[1.5px] text-black uppercase font-medium mb-8">Tóm tắt đơn hàng</h2>

        <!-- Product list -->
        <div class="space-y-6 mb-8 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin">
          <div 
            v-for="item in cartStore.selectedItems" 
            :key="item.product_variant_id" 
            class="flex gap-4 items-center"
          >
            <div class="w-16 h-20 bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200/50 rounded">
              <img 
                :src="getImageUrl(item.product_thumbnail)" 
                :alt="item.product_name" 
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1 flex justify-between items-start">
              <div class="space-y-1">
                <h4 class="text-[13px] font-bold text-black uppercase">{{ item.product_name }}</h4>
                <p class="text-xs text-neutral-400">
                  <span v-if="item.attributes && item.attributes.length > 0">
                    {{ item.attributes.map(a => a.value).join(' / ') }}
                  </span>
                </p>
                <p class="text-xs text-neutral-500">Số lượng: {{ item.quantity }}</p>
              </div>
              <span class="text-[13px] font-semibold text-black">{{ formatPrice(item.price * item.quantity) }}đ</span>
            </div>
          </div>
        </div>

        <!-- Discount / Coupon -->
        <div class="border-t border-neutral-200/60 pt-6 mb-6">
          <label class="block text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-2">Mã giảm giá</label>
          <div class="flex gap-2">
            <input 
              type="text" 
              v-model="couponCode" 
              placeholder="Nhập mã giảm giá..."
              :disabled="appliedCoupon !== null"
              class="flex-1 border border-neutral-200 px-4 py-3 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 disabled:bg-neutral-50 disabled:text-neutral-400"
            />
            <button 
              v-if="!appliedCoupon"
              @click="applyCoupon"
              :disabled="!couponCode || applyingCoupon"
              class="bg-black hover:bg-neutral-800 disabled:bg-neutral-300 text-white font-bold uppercase tracking-wider text-[11px] px-6 transition-colors duration-300 cursor-pointer border-none"
            >
              {{ applyingCoupon ? 'Đang áp dụng...' : 'Áp dụng' }}
            </button>
            <button 
              v-else
              @click="removeCoupon"
              class="bg-red-50 hover:bg-red-100 text-red-600 font-bold uppercase tracking-wider text-[11px] px-6 transition-colors duration-300 cursor-pointer border border-red-200"
            >
              Hủy
            </button>
          </div>
          <p v-if="couponMessage" :class="['text-xs mt-2', couponError ? 'text-red-500' : 'text-green-600']">
            {{ couponMessage }}
          </p>
        </div>

        <!-- Pricing calculation -->
        <div class="border-t border-neutral-200/60 pt-6 space-y-3.5 text-[13px] text-neutral-600 mb-6">
          <div class="flex justify-between">
            <span>Tạm tính <span class="text-neutral-400">({{ cartStore.selectedItems.length }} sp)</span></span>
            <span class="font-medium text-black">{{ formatPrice(cartStore.selectedTotal) }}đ</span>
          </div>
          <div v-if="discountAmount > 0" class="flex justify-between text-green-600">
            <span>Giảm giá ({{ appliedCoupon.code }})</span>
            <span class="font-medium">-{{ formatPrice(discountAmount) }}đ</span>
          </div>
          <div class="flex justify-between">
            <span>Phí vận chuyển</span>
            <span class="font-medium text-black">{{ shippingFeeText }}</span>
          </div>
        </div>

        <!-- Total Price -->
        <div class="border-t border-neutral-200/80 pt-6 flex justify-between items-end mb-8">
          <span class="font-title text-[24px] uppercase tracking-[1px] text-black">Tổng cộng</span>
          <span class="font-title text-[26px] font-bold text-black">{{ formatPrice(total) }}đ</span>
        </div>

        <!-- Order Button -->
        <button 
          @click="triggerSubmit"
          :disabled="submitting"
          class="w-full bg-black hover:bg-neutral-800 disabled:bg-neutral-450 text-white font-text text-[12px] font-bold tracking-wider py-4.5 uppercase transition-colors duration-300 text-center shadow-sm cursor-pointer border-none"
        >
          {{ submitting ? 'Đang xử lý...' : 'Hoàn tất đặt hàng' }}
        </button>

        <!-- Terms Disclaimer -->
        <p class="text-[11px] text-neutral-400 leading-relaxed text-center mt-4">
          Bằng cách đặt hàng, bạn đồng ý với các <a href="#" class="underline text-neutral-500 hover:text-black">Điều khoản Dịch vụ</a> của chúng tôi.
        </p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useClientAuthStore } from '@/stores/client/authStore'
import { useCartStore } from '@/stores/client/cartStore'
import { orderService } from '@/services/client/orderService'
import { profileService } from '@/services/client/profileService'
import { couponService } from '@/services/client/couponService'
import { shippingService } from '@/services/client/shippingService'
import AuthModal from '@/views/client/auth/AuthModal.vue'
import ProvincePicker from '@/components/client/ProvincePicker.vue'

const router = useRouter()
const authStore = useClientAuthStore()
const cartStore = useCartStore()

// State
const isAuthModalOpen = ref(false)
const submitting = ref(false)
const submitButtonRef = ref(null)

const ghnFee = ref(0)
const calculatingGhnFee = ref(false)
const ghnCalculated = ref(false)

const shippingForm = reactive({
  fullName: '',
  address: '',
  province: '',
  district: '',
  ward: '',
  district_id: null,
  ward_code: null,
  phone: ''
})

const calculateGhnShipping = async (districtId, wardCode) => {
  if (!districtId || !wardCode) return
  calculatingGhnFee.value = true
  try {
    const sRes = await shippingService.getServices(districtId)
    const services = sRes.data?.data || []
    const activeService = services.find(s => s.service_type_id === 2) || services[0]
    
    if (activeService) {
      const feeRes = await shippingService.calculateFee({
        district_id: districtId,
        ward_code: String(wardCode),
        service_id: activeService.service_id,
        weight: 500
      })
      if (feeRes.data && feeRes.data.success) {
        ghnFee.value = feeRes.data.total
        ghnCalculated.value = true
      }
    }
  } catch (err) {
    console.warn('Không thể tự động tính phí GHN:', err)
  } finally {
    calculatingGhnFee.value = false
  }
}

const onLocationChange = ({ province, district, ward, district_id, ward_code }) => {
  shippingForm.province = province
  shippingForm.district = district
  shippingForm.ward = ward
  shippingForm.district_id = district_id
  shippingForm.ward_code = ward_code

  if (district_id && ward_code) {
    calculateGhnShipping(district_id, ward_code)
  }
}

const shippingMethod = ref('standard')
const paymentMethod = ref('cod') // cod hoặc vnpay

// Coupon State
const couponCode = ref('')
const appliedCoupon = ref(null)
const discountAmount = ref(0)
const applyingCoupon = ref(false)
const couponMessage = ref('')
const couponError = ref(false)

// Prefill values when user loads checkout
onMounted(async () => {
  // 1. Fallback default from user profile
  if (authStore.user) {
    const firstName = authStore.user.first_name || ''
    const lastName = authStore.user.last_name || ''
    shippingForm.fullName = `${lastName} ${firstName}`.trim()
    shippingForm.phone = authStore.user.phone_number || ''
  }

  // 2. Fetch customer addresses and load default if exists
  if (authStore.isAuthenticated) {
    try {
      const res = await profileService.getAddresses()
      if (res.data && res.data.success && res.data.data.length > 0) {
        const defaultAddr = res.data.data.find(a => a.is_default) || res.data.data[0]
        if (defaultAddr) {
          shippingForm.fullName = defaultAddr.receiver_name
          shippingForm.phone = defaultAddr.receiver_phone
          
          const addressParts = [
            defaultAddr.detail_address,
            defaultAddr.ward,
            defaultAddr.district
          ].filter(Boolean)
          
          shippingForm.address = addressParts.join(', ')
          shippingForm.province = defaultAddr.province || ''
          shippingForm.district = defaultAddr.district || ''
          shippingForm.ward = defaultAddr.ward || ''
        }
      }
    } catch (err) {
      console.error('Error fetching addresses for checkout prefill:', err)
    }
  }
})

const shippingFee = computed(() => {
  const baseFee = ghnFee.value > 0 ? ghnFee.value : (shippingMethod.value === 'express' ? 150000 : 30000)
  return shippingMethod.value === 'express' ? baseFee + 50000 : baseFee
})

const shippingFeeText = computed(() => {
  if (calculatingGhnFee.value) return 'Đang tính phí GHN...'
  if (ghnCalculated.value) return formatPrice(shippingFee.value) + 'đ (GHN)'
  return shippingMethod.value === 'express' ? '150.000đ' : '30.000đ'
})

const total = computed(() => {
  return Math.max(0, cartStore.selectedTotal - discountAmount.value) + shippingFee.value
})

const applyCoupon = async () => {
  if (!couponCode.value || applyingCoupon.value) return
  
  applyingCoupon.value = true
  couponMessage.value = ''
  couponError.value = false

  try {
    const res = await couponService.applyCoupon(couponCode.value, cartStore.selectedTotal)
    if (res.data && res.data.success) {
      appliedCoupon.value = res.data.coupon
      discountAmount.value = res.data.discount
      couponMessage.value = res.data.message || 'Áp dụng mã giảm giá thành công!'
    } else {
      couponError.value = true
      couponMessage.value = res.data?.message || 'Mã giảm giá không hợp lệ.'
    }
  } catch (err) {
    couponError.value = true
    couponMessage.value = err.response?.data?.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá.'
  } finally {
    applyingCoupon.value = false
  }
}

const removeCoupon = () => {
  couponCode.value = ''
  appliedCoupon.value = null
  discountAmount.value = 0
  couponMessage.value = ''
  couponError.value = false
}

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const triggerSubmit = () => {
  if (submitButtonRef.value) {
    submitButtonRef.value.click()
  }
}

const submitOrder = async () => {
  if (!authStore.isAuthenticated) {
    isAuthModalOpen.value = true
    return
  }
  if (cartStore.isEmpty) {
    alert('Giỏ hàng của bạn đang trống.')
    return
  }
  if (cartStore.selectedIds.size === 0) {
    alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.')
    return
  }

  submitting.value = true
  try {
    const addressParts = [shippingForm.address, shippingForm.ward, shippingForm.district, shippingForm.province].filter(Boolean)
    const payload = {
      shipping_name: shippingForm.fullName,
      shipping_phone: shippingForm.phone,
      shipping_address: addressParts.join(', '),
      shipping_fee: shippingFee.value,
      payment_method: paymentMethod.value,
      items: cartStore.toOrderItems(),
      coupon_code: appliedCoupon.value?.code || null
    }

    const res = await orderService.createOrder(payload)
    
    if (res.data && res.data.success) {
      if (paymentMethod.value === 'cod') {
        // COD thành công -> Clear giỏ hàng ngay và chuyển hướng sang trang success
        cartStore.clearCart()
        router.push({ name: 'CheckoutSuccess', query: { code: res.data.data.order_code } })
      } else if (paymentMethod.value === 'vnpay' && res.data.payment_url) {
        // Redirect sang VNPAY Sandbox (Không clear giỏ hàng ở đây, sẽ clear khi thanh toán thành công tại vnpay-return)
        window.location.href = res.data.payment_url
      }
    } else {
      alert(res.data.message || 'Đã xảy ra lỗi khi tạo đơn hàng.')
    }
  } catch (err) {
    alert(err.message || 'Lỗi hệ thống khi thanh toán.')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
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
