<template>
  <div class="max-w-[800px] mx-auto px-5 py-16 md:py-24 flex flex-col items-center font-text">
    
    <!-- Success Icon -->
    <div class="w-16 h-16 rounded-full border border-emerald-500 flex items-center justify-center text-emerald-500 mb-8 bg-emerald-50/30 animate-scale-in">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="4 12 9 17 20 6" class="animate-draw-check"></polyline>
      </svg>
    </div>

    <!-- Thank You Header -->
    <h1 class="font-title text-3xl md:text-4xl font-normal text-black text-center mb-4 tracking-[0.5px]">Cảm ơn bạn đã đặt hàng!</h1>
    <p class="text-sm text-neutral-600 text-center leading-relaxed max-w-[500px] mb-12">
      Đơn hàng 
      <span class="font-semibold text-black">#{{ orderCode || 'N/A' }}</span>
      của bạn đã được tiếp nhận thành công.
      <br>
      Chúng tôi sẽ xử lý và giao hàng sớm nhất có thể!
    </p>

    <!-- Divider -->
    <div class="w-full h-px bg-neutral-100 mb-10"></div>

    <!-- Order Detail (if loaded) -->
    <div v-if="order" class="w-full space-y-6 mb-10">
      <!-- Shipping info + payment method grid -->
      <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
        <!-- Shipping Info -->
        <div class="space-y-4">
          <h3 class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">Thông tin giao hàng</h3>
          <div class="space-y-1 text-[13px] text-neutral-600 leading-relaxed">
            <p class="font-semibold text-black text-sm">{{ order.shipping_name }}</p>
            <p>{{ order.shipping_address }}</p>
            <p>{{ order.shipping_phone }}</p>
          </div>
        </div>

        <!-- Payment Info -->
        <div class="space-y-4">
          <h3 class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">Thanh toán</h3>
          <div class="space-y-2">
            <div class="flex items-center gap-3 text-[13px] text-neutral-850 leading-relaxed">
              <div class="w-8 h-8 rounded flex items-center justify-center text-[10px] font-bold border"
                :class="order.payment_method === 'vnpay' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-neutral-100 text-neutral-700 border-neutral-300'">
                {{ order.payment_method === 'vnpay' ? 'VNP' : 'COD' }}
              </div>
              <div>
                <p class="font-semibold">{{ order.payment_method === 'vnpay' ? 'Cổng VNPAY' : 'Thanh toán khi nhận hàng (COD)' }}</p>
                <p class="text-xs" 
                  :class="order.payment_status === 'paid' ? 'text-emerald-600' : 'text-neutral-500'">
                  {{ order.payment_status === 'paid' ? '✓ Đã thanh toán' : 'Chờ thanh toán khi nhận hàng' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order items -->
      <div class="border border-neutral-100 rounded-lg overflow-hidden">
        <div class="bg-neutral-50 px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-neutral-400 border-b border-neutral-100">
          Sản phẩm đã đặt
        </div>
        <div class="divide-y divide-neutral-50">
          <div 
            v-for="detail in order.details" 
            :key="detail.id"
            class="px-5 py-4 flex justify-between items-center text-[13px]"
          >
            <div>
              <p class="font-semibold text-black">{{ detail.product_variant?.product?.name || 'Sản phẩm' }}</p>
              <p class="text-neutral-400 text-xs">SKU: {{ detail.product_variant?.sku }} &times; {{ detail.quantity }}</p>
            </div>
            <div class="flex items-center gap-4">
              <span class="font-semibold text-black">{{ formatPrice(detail.unit_price * detail.quantity) }}đ</span>
              
              <!-- Đánh giá nút -->
              <button 
                v-if="order.status === 'completed'" 
                @click="openReviewModal(detail)"
                :disabled="detail.review"
                :class="[
                  'px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest border transition-all cursor-pointer font-text',
                  detail.review 
                    ? 'border-neutral-200 text-neutral-400 bg-neutral-50 cursor-not-allowed'
                    : 'border-black text-black bg-white hover:bg-black hover:text-white'
                ]"
              >
                {{ detail.review ? 'Đã đánh giá' : 'Đánh giá' }}
              </button>
            </div>
          </div>
        </div>
        <div class="bg-neutral-50 px-5 py-4 border-t border-neutral-100 flex justify-between items-center">
          <span class="text-sm text-neutral-600">Phí vận chuyển</span>
          <span class="text-sm font-medium">{{ order.shipping_fee > 0 ? formatPrice(order.shipping_fee) + 'đ' : 'Miễn phí' }}</span>
        </div>
        <div class="px-5 py-4 border-t border-neutral-200 flex justify-between items-center">
          <span class="font-title text-xl uppercase tracking-wider text-black">Tổng cộng</span>
          <span class="font-title text-2xl font-bold text-black">{{ formatPrice(order.final_amount) }}đ</span>
        </div>
      </div>
    </div>

    <!-- Loading state -->
    <div v-else-if="loadingOrder" class="text-sm text-neutral-400 mb-10">Đang tải thông tin đơn hàng...</div>

    <!-- Divider -->
    <div class="w-full h-px bg-neutral-100 mb-10"></div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 w-full justify-center mb-16">
      <router-link 
        to="/profile/order-history" 
        class="bg-black hover:bg-neutral-800 text-white text-[12px] font-bold tracking-wider py-4 px-8 uppercase transition-colors duration-300 text-center min-w-[220px] shadow-sm no-underline"
      >
        Xem đơn hàng của tôi
      </router-link>
      <router-link 
        to="/" 
        class="border border-neutral-300 hover:border-black bg-white hover:bg-neutral-50 text-black text-[12px] font-bold tracking-wider py-4 px-8 uppercase transition-all duration-300 text-center min-w-[220px] shadow-sm no-underline"
      >
        Tiếp tục mua sắm
      </router-link>
    </div>

    <!-- Footer Image -->
    <div class="w-full max-w-[640px] aspect-[16/9] overflow-hidden rounded bg-neutral-50 border border-neutral-100 shadow-xs mb-10">
      <img 
        src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop" 
        alt="Success order" 
        class="w-full h-full object-cover grayscale contrast-105 brightness-95"
      />
    </div>

    <!-- Review Modal -->
    <transition name="fade">
      <div v-if="isReviewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 font-text">
        <div class="bg-white border border-neutral-200 w-full max-w-[500px] p-6 space-y-6 relative animate-scale-in">
          <!-- Close button -->
          <button @click="closeReviewModal" class="absolute top-4 right-4 text-neutral-400 hover:text-black cursor-pointer bg-transparent border-none">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>

          <!-- Title -->
          <div class="space-y-1">
            <h3 class="font-title text-[18px] font-bold uppercase tracking-wider text-black">Đánh giá sản phẩm</h3>
            <p class="text-xs text-neutral-400">Chia sẻ trải nghiệm của bạn về sản phẩm này.</p>
          </div>

          <!-- Product Details -->
          <div v-if="selectedDetail" class="flex gap-4 items-center bg-neutral-50 p-3 border border-neutral-100 rounded">
            <div class="space-y-0.5">
              <p class="text-xs font-bold text-neutral-900 leading-snug">{{ selectedDetail.product_variant?.product?.name }}</p>
              <p class="text-[11px] text-neutral-400 font-mono">SKU: {{ selectedDetail.product_variant?.sku }}</p>
            </div>
          </div>

          <!-- Alert -->
          <div v-if="reviewAlert.show" :class="['px-4 py-2.5 text-xs font-medium border', reviewAlert.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700']">
            {{ reviewAlert.message }}
          </div>

          <!-- Review Form -->
          <form @submit.prevent="submitReview" class="space-y-5">
            <!-- Stars -->
            <div class="space-y-2">
              <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-500">Đánh giá số sao *</label>
              <div class="flex items-center gap-2">
                <button
                  v-for="star in 5"
                  :key="star"
                  type="button"
                  @click="reviewForm.rating = star"
                  @mouseover="hoveredStar = star"
                  @mouseleave="hoveredStar = 0"
                  class="bg-transparent border-none cursor-pointer p-1 transition-transform hover:scale-110"
                >
                  <svg
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    :fill="(hoveredStar || reviewForm.rating) >= star ? '#FBBF24' : 'none'"
                    :stroke="(hoveredStar || reviewForm.rating) >= star ? '#FBBF24' : '#D1D5DB'"
                    stroke-width="1.5"
                  >
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Comment -->
            <div class="space-y-2">
              <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-500">Bình luận, đánh giá chi tiết</label>
              <textarea
                v-model="reviewForm.comment"
                rows="4"
                placeholder="Sản phẩm rất đẹp, chất vải mềm mát, đáng tiền..."
                class="w-full border border-neutral-200 p-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors rounded resize-none"
              ></textarea>
            </div>

            <!-- Submit -->
            <div class="pt-2">
              <button
                type="submit"
                :disabled="submittingReview || !reviewForm.rating"
                class="w-full bg-black hover:bg-neutral-800 disabled:bg-neutral-300 text-white font-bold uppercase tracking-wider text-xs py-3.5 transition-colors cursor-pointer border-none"
              >
                {{ submittingReview ? 'Đang gửi đánh giá...' : 'Gửi đánh giá' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { orderService } from '@/services/client/orderService'
import { reviewService } from '@/services/client/reviewService'

const route = useRoute()

const orderCode = ref(route.query.code || '')
const order = ref(null)
const loadingOrder = ref(false)

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

onMounted(async () => {
  if (!orderCode.value) return
  loadingOrder.value = true
  try {
    const res = await orderService.getOrder(orderCode.value)
    if (res.data && res.data.success) {
      order.value = res.data.data
    }
  } catch (err) {
    console.warn('Không tải được chi tiết đơn hàng:', err)
  } finally {
    loadingOrder.value = false
  }
})

// Review logic
const isReviewModalOpen = ref(false)
const selectedDetail = ref(null)
const submittingReview = ref(false)
const hoveredStar = ref(0)
const reviewAlert = ref({ show: false, type: 'success', message: '' })

const reviewForm = ref({
  rating: 0,
  comment: ''
})

const openReviewModal = (detail) => {
  selectedDetail.value = detail
  reviewForm.value = {
    rating: 0,
    comment: ''
  }
  reviewAlert.value = { show: false, type: 'success', message: '' }
  isReviewModalOpen.value = true
}

const closeReviewModal = () => {
  isReviewModalOpen.value = false
  selectedDetail.value = null
}

const showReviewAlert = (type, message) => {
  reviewAlert.value = { show: true, type, message }
  setTimeout(() => {
    reviewAlert.value.show = false
  }, 4000)
}

const submitReview = async () => {
  if (!selectedDetail.value || !reviewForm.value.rating) return
  submittingReview.value = true
  try {
    const res = await reviewService.submitReview({
      order_detail_id: selectedDetail.value.id,
      rating: reviewForm.value.rating,
      comment: reviewForm.value.comment
    })
    if (res.data && res.data.success) {
      showReviewAlert('success', res.data.message)
      selectedDetail.value.review = res.data.data
      setTimeout(() => {
        closeReviewModal()
      }, 1500)
    }
  } catch (err) {
    showReviewAlert('error', err.response?.data?.message || 'Không thể gửi đánh giá lúc này.')
  } finally {
    submittingReview.value = false
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

@keyframes scaleIn {
  0% { transform: scale(0.9); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}

@keyframes drawCheck {
  0% { stroke-dashoffset: 24; }
  100% { stroke-dashoffset: 0; }
}

.animate-scale-in {
  animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-draw-check {
  stroke-dasharray: 24;
  stroke-dashoffset: 24;
  animation: drawCheck 0.4s ease-out 0.45s forwards;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
