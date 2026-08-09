<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Tạo Đơn Hàng Mới (POS)</h1>
      <button @click="$router.push('/admin/orders')" class="px-4 py-2 border rounded-md hover:bg-gray-50">
        Hủy & Quay lại
      </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Cột Trái: Sản phẩm -->
      <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Sản phẩm</h2>
        
        <!-- Ô tìm kiếm sản phẩm -->
        <div class="mb-4 relative">
          <input 
            v-model="searchQuery"
            @input="searchProducts"
            type="text" 
            placeholder="Tìm kiếm sản phẩm theo tên..." 
            class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-black"
          >
          <div v-if="searchResults.length > 0 && searchQuery" class="absolute z-10 w-full bg-white border mt-1 max-h-60 overflow-y-auto rounded shadow-lg">
            <div 
              v-for="prod in searchResults" 
              :key="prod.id"
              class="p-2 border-b hover:bg-gray-50"
            >
              <div class="font-medium text-sm">{{ prod.name }}</div>
              <div class="flex gap-2 mt-2 flex-wrap">
                <button 
                  v-for="variant in prod.variants" 
                  :key="variant.id"
                  @click="addVariantToCart(prod, variant)"
                  class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded border transition-colors"
                  :class="{'opacity-50 cursor-not-allowed text-red-500': variant.stock_quantity <= 0}"
                  :disabled="variant.stock_quantity <= 0"
                >
                  {{ getVariantName(variant) }} (Tồn: {{ variant.stock_quantity }})
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Giỏ hàng -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[500px]">
            <thead>
              <tr class="bg-gray-50 border-b text-sm">
                <th class="p-3">Sản phẩm</th>
                <th class="p-3 w-24">Số lượng</th>
                <th class="p-3 w-32">Đơn giá</th>
                <th class="p-3 w-32">Thành tiền</th>
                <th class="p-3 w-10"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="cart.length === 0">
                <td colspan="5" class="p-6 text-center text-gray-500 text-sm">Chưa có sản phẩm nào được chọn.</td>
              </tr>
              <tr v-for="(item, index) in cart" :key="index" class="border-b">
                <td class="p-3">
                  <div class="font-medium text-sm text-gray-800">{{ item.product_name }}</div>
                  <div class="text-xs text-gray-500 mt-1">{{ item.variant_name }}</div>
                </td>
                <td class="p-3">
                  <input type="number" v-model.number="item.quantity" min="1" :max="item.max_stock" class="w-16 border rounded p-1 text-center text-sm focus:outline-none focus:border-black">
                </td>
                <td class="p-3 text-sm text-gray-600">{{ formatPrice(item.price) }}đ</td>
                <td class="p-3 font-semibold text-sm text-black">{{ formatPrice(item.price * item.quantity) }}đ</td>
                <td class="p-3">
                  <button @click="removeItem(index)" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded-full hover:bg-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Cột Phải: Khách hàng & Thanh toán -->
      <div class="space-y-6">
        
        <!-- Khách hàng -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Thông tin giao hàng</h2>
          <div class="space-y-4 text-sm">
            <div>
              <label class="block text-gray-700 font-medium mb-1">Họ tên người nhận <span class="text-red-500">*</span></label>
              <input v-model="order.shipping_name" type="text" :class="['w-full border p-2 rounded focus:outline-none focus:border-black', errors.shipping_name ? 'border-red-500' : '']" placeholder="Nhập họ tên...">
              <p v-if="errors.shipping_name" class="text-red-500 text-xs mt-1">{{ errors.shipping_name }}</p>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Số điện thoại <span class="text-red-500">*</span></label>
              <input v-model="order.shipping_phone" type="text" :class="['w-full border p-2 rounded focus:outline-none focus:border-black', errors.shipping_phone ? 'border-red-500' : '']" placeholder="Nhập số điện thoại...">
              <p v-if="errors.shipping_phone" class="text-red-500 text-xs mt-1">{{ errors.shipping_phone }}</p>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Địa chỉ giao hàng <span class="text-red-500">*</span></label>
              <textarea v-model="order.shipping_address" :class="['w-full border p-2 rounded focus:outline-none focus:border-black', errors.shipping_address ? 'border-red-500' : '']" rows="3" placeholder="Nhập địa chỉ chi tiết..."></textarea>
              <p v-if="errors.shipping_address" class="text-red-500 text-xs mt-1">{{ errors.shipping_address }}</p>
            </div>
          </div>
        </div>

        <!-- Thanh toán -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Thanh toán</h2>
          
          <div class="flex justify-between items-center mb-3 text-sm">
            <span class="text-gray-600">Tổng tiền hàng:</span>
            <span class="font-medium">{{ formatPrice(subTotal) }}đ</span>
          </div>
          
          <div class="flex justify-between items-center mb-3 text-sm">
            <span class="text-gray-600">Phí vận chuyển:</span>
            <input v-model.number="order.shipping_fee" type="number" class="w-24 border p-1 rounded text-right focus:outline-none focus:border-black">
          </div>
          
          <div class="flex justify-between items-center mb-4 text-sm">
            <span class="text-gray-600">Giảm giá thêm:</span>
            <input v-model.number="order.discount_amount" type="number" class="w-24 border p-1 rounded text-right focus:outline-none focus:border-black text-red-500">
          </div>

          <div class="flex justify-between items-center py-4 border-t border-b mb-5 bg-gray-50 px-3 rounded">
            <span class="font-bold text-gray-800 uppercase text-sm">Khách phải trả:</span>
            <span class="font-bold text-black text-xl">{{ formatPrice(finalAmount) }}đ</span>
          </div>

          <div class="space-y-4 text-sm mb-6">
            <div>
              <label class="block text-gray-700 font-medium mb-1">Phương thức thanh toán</label>
              <select v-model="order.payment_method" class="w-full border p-2 rounded focus:outline-none focus:border-black bg-white">
                <option value="cash">Tiền mặt (tại quầy)</option>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="bank_transfer">Chuyển khoản</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Trạng thái thanh toán</label>
              <select v-model="order.payment_status" class="w-full border p-2 rounded focus:outline-none focus:border-black bg-white">
                <option value="pending">Chưa thanh toán</option>
                <option value="paid">Đã thanh toán</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Ghi chú đơn hàng</label>
              <textarea v-model="order.note" class="w-full border p-2 rounded focus:outline-none focus:border-black" rows="2" placeholder="Ghi chú thêm..."></textarea>
            </div>
          </div>

          <button 
            @click="submitOrder" 
            :disabled="isSubmitting || cart.length === 0"
            class="w-full bg-black text-white font-bold py-3.5 rounded-lg hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors uppercase tracking-wider text-sm shadow-md"
          >
            {{ isSubmitting ? 'Đang xử lý...' : 'Xác Nhận Tạo Đơn' }}
          </button>
        </div>

      </div>
    </div>

    <!-- Custom Toast -->
    <Transition name="toast-fade">
      <div 
        v-if="toastState.show" 
        class="fixed bottom-6 right-6 px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 z-50 text-sm font-medium text-white transition-all"
        :class="toastState.type === 'error' ? 'bg-red-600' : (toastState.type === 'warning' ? 'bg-amber-500' : 'bg-slate-800')"
      >
        <span>{{ toastState.message }}</span>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { productService } from '@/services/admin/productService'
import http from '@/services/shared/http'

const router = useRouter()

const toastState = ref({ show: false, message: '', type: 'success' })
let toastTimer = null

const showToast = (message, type = 'success') => {
  toastState.value = { show: true, message, type }
  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => { toastState.value.show = false }, 3000)
}

const toast = {
  success: (msg) => showToast(msg, 'success'),
  error: (msg) => showToast(msg, 'error'),
  warning: (msg) => showToast(msg, 'warning')
}

const errors = ref({})

const searchQuery = ref('')
const searchResults = ref([])
const cart = ref([])
const isSubmitting = ref(false)

const order = ref({
  shipping_name: '',
  shipping_phone: '',
  shipping_address: '',
  shipping_fee: 0,
  discount_amount: 0,
  payment_method: 'cash',
  payment_status: 'paid',
  note: ''
})

const searchTimeout = ref(null)

const searchProducts = () => {
  clearTimeout(searchTimeout.value)
  if (!searchQuery.value) {
    searchResults.value = []
    return
  }
  searchTimeout.value = setTimeout(async () => {
    try {
      const res = await productService.getAll({ search: searchQuery.value, per_page: 5 })
      searchResults.value = res.data?.data || []
    } catch (e) {
      console.error(e)
    }
  }, 300)
}

const getVariantName = (variant) => {
  if (!variant.attribute_values || variant.attribute_values.length === 0) return variant.sku
  return variant.attribute_values.map(a => a.value).join(' - ')
}

const addVariantToCart = (prod, variant) => {
  if (variant.stock_quantity <= 0) return;

  const existing = cart.value.find(i => i.product_variant_id === variant.id)
  if (existing) {
    if (existing.quantity < variant.stock_quantity) {
      existing.quantity++
      toast.success('Đã tăng số lượng sản phẩm!')
    } else {
      toast.warning('Vượt quá số lượng tồn kho!')
    }
  } else {
    cart.value.push({
      product_variant_id: variant.id,
      product_name: prod.name,
      variant_name: getVariantName(variant),
      price: variant.sale_price || variant.price,
      quantity: 1,
      max_stock: variant.stock_quantity
    })
    toast.success('Đã thêm vào giỏ!')
  }
  searchQuery.value = ''
  searchResults.value = []
}

const removeItem = (index) => {
  cart.value.splice(index, 1)
}

const subTotal = computed(() => {
  return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const finalAmount = computed(() => {
  const total = subTotal.value + (order.value.shipping_fee || 0) - (order.value.discount_amount || 0)
  return total > 0 ? total : 0
})

const formatPrice = (value) => {
  return new Intl.NumberFormat('vi-VN').format(value)
}

const submitOrder = async () => {
  if (cart.value.length === 0) return toast.error('Giỏ hàng trống!')
  
  errors.value = {}
  if (!order.value.shipping_name) errors.value.shipping_name = 'Vui lòng nhập họ tên người nhận.'
  if (!order.value.shipping_phone) errors.value.shipping_phone = 'Vui lòng nhập số điện thoại.'
  if (!order.value.shipping_address) errors.value.shipping_address = 'Vui lòng nhập địa chỉ giao hàng.'
  
  if (Object.keys(errors.value).length > 0) {
    return toast.error('Vui lòng kiểm tra lại thông tin bắt buộc!')
  }

  isSubmitting.value = true
  try {
    const payload = {
      ...order.value,
      status: 'completed', 
      items: cart.value.map(i => ({
        product_variant_id: i.product_variant_id,
        quantity: i.quantity
      }))
    }

    if (payload.payment_method === 'cod') {
        payload.status = 'pending'
        payload.payment_status = 'pending'
    }

    await http.post('/admin/orders', payload)
    toast.success('Tạo đơn hàng thành công!')
    router.push('/admin/orders')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Có lỗi xảy ra!')
  } finally {
    isSubmitting.value = false
  }
}
</script>
<style scoped>
.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s ease;
}
.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
