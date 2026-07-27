<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterView } from 'vue-router'
import { useClientAuthStore } from '@/stores/client/authStore'
import ProfileSidebar from '@/components/client/profile/ProfileSidebar.vue'
import { orderService } from '@/services/client/orderService'

const route = useRoute()
const authStore = useClientAuthStore()

const orders = ref([])
const loadingOrders = ref(false)

const fetchOrders = async () => {
  loadingOrders.value = true
  try {
    const res = await orderService.getOrders()
    if (res.data?.success) orders.value = res.data.data
  } catch (e) {
    console.error('Lỗi tải đơn hàng:', e)
  } finally {
    loadingOrders.value = false
  }
}

onMounted(() => {
  authStore.fetchMe()
  fetchOrders()
})

const isDashboard = computed(() => route.path === '/profile' || route.path === '/profile/')

const userFullName = computed(() => {
  if (!authStore.user) return ''
  return `${authStore.user.first_name} ${authStore.user.last_name}`
})

const userFirstName = computed(() => {
  if (!authStore.user) return ''
  return authStore.user.first_name
})

const completedCount = computed(() => orders.value.filter(o => o.status === 'completed').length)
const recentOrders   = computed(() => orders.value.slice(0, 3))

const STATUS_MAP = {
  pending:   { text: 'ĐANG XỬ LÝ',      cls: 'border-amber-400 text-amber-600 bg-white' },
  confirmed: { text: 'ĐÃ XÁC NHẬN',     cls: 'border-blue-400 text-blue-600 bg-white' },
  shipping:  { text: 'ĐANG VẬN CHUYỂN', cls: 'border-black bg-black text-white' },
  completed: { text: 'ĐÃ GIAO',          cls: 'border-gray-400 text-gray-700 bg-white' },
  cancelled: { text: 'ĐÃ HỦY',          cls: 'border-red-400 text-red-600 bg-white' },
}
const statusText  = (s) => STATUS_MAP[s]?.text  || (s || '').toUpperCase()
const statusClass = (s) => [
  'inline-block text-[9px] font-bold tracking-wider uppercase border px-2 py-0.5 rounded-none',
  STATUS_MAP[s]?.cls || 'border-gray-400 text-gray-700 bg-white'
]

const formatDate = (d) => {
  if (!d) return ''
  return new Date(d).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
const formatPrice = (v) => {
  if (v == null) return '0 đ'
  return Number(v).toLocaleString('vi-VN') + ' đ'
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 select-none">
    
    <div class="flex flex-col md:flex-row gap-8">
      
      <div class="w-full md:w-64 flex-shrink-0">
        <ProfileSidebar />
      </div>

      <div class="flex-grow bg-white text-[#111111]">
        
        <div v-if="isDashboard" class="space-y-10 animate-fade-in">
          <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Chào bạn, {{ userFirstName }}!</h1>
            <p class="text-sm text-gray-400 mt-1 font-normal">Chào mừng bạn quay lại</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="border border-gray-900 bg-[#FBFBFB] p-6 min-h-[140px] flex flex-col justify-between">
              <p class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold">Đơn hàng của tôi</p>
              <div class="mt-4">
                <span class="text-4xl font-medium tracking-tight">{{ orders.length }}</span>
                <p class="text-xs text-gray-400 mt-1">Tổng số đơn hàng</p>
              </div>
            </div>

            <div class="border border-gray-200 bg-white p-6 min-h-[140px] flex flex-col justify-between">
              <p class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold">Hoàn thành</p>
              <div class="mt-4">
                <span class="text-4xl font-medium tracking-tight">{{ completedCount }}</span>
                <p class="text-xs text-gray-400 mt-1">Đơn hàng đã giao thành công</p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border border-gray-200 p-6 bg-[#FBFBFB] flex flex-col justify-between min-h-[260px]">
              <div>
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-sm font-bold uppercase tracking-wider">Thông tin cá nhân</h3>
                  <button @click="$router.push('/profile/information')" class="text-xs font-bold uppercase tracking-wider underline hover:text-gray-600 transition-colors">
                    EDIT
                  </button>
                </div>
                
                <div class="space-y-4">
                  <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Tên</p>
                    <p class="text-sm font-normal text-gray-800 mt-0.5">{{ userFullName || 'Chưa cập nhật' }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Email</p>
                    <p class="text-sm font-normal text-gray-800 mt-0.5">{{ authStore.user?.email }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Số điện thoại</p>
                    <p class="text-sm font-normal text-gray-800 mt-0.5">{{ authStore.user?.phone_number || 'Chưa cập nhật' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="relative overflow-hidden bg-black min-h-[260px] flex items-center justify-center p-8 group">
              <img 
                src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop" 
                alt="New Collection" 
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale group-hover:scale-105 transition-transform duration-700"
              />
              <div class="absolute inset-0 bg-black/20"></div>
              
              <div class="relative z-10 text-center space-y-4 w-full">
                <div>
                  <span class="inline-block bg-white text-[9px] font-bold uppercase tracking-[0.2em] text-gray-900 px-3 py-1">
                    New Collection
                  </span>
                </div>
                <h4 class="text-2xl font-bold tracking-[0.15em] text-white uppercase leading-tight">
                  Essentials<br>Autumn '24
                </h4>
                <div>
                  <button class="inline-block border border-white text-white text-[10px] font-bold uppercase tracking-widest px-6 py-2.5 hover:bg-white hover:text-gray-900 transition-all duration-300">
                    Shop Now
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="space-y-4 pt-4">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-bold uppercase tracking-wider">Đơn đã đặt</h3>
              <router-link to="/profile/order-history" class="text-xs font-bold uppercase tracking-wider underline hover:text-gray-600 transition-colors">
                XEM TẤT CẢ
              </router-link>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[650px]">
                <thead>
                  <tr class="border-b-2 border-gray-900">
                    <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Order ID</th>
                    <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Date</th>
                    <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Status</th>
                    <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Total</th>
                    <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs font-medium">
                  <!-- Loading -->
                  <tr v-if="loadingOrders">
                    <td colspan="5" class="py-6 text-center text-xs text-gray-400">Đang tải...</td>
                  </tr>
                  <!-- Empty -->
                  <tr v-else-if="recentOrders.length === 0">
                    <td colspan="5" class="py-6 text-center text-xs text-gray-400">Chưa có đơn hàng nào.</td>
                  </tr>
                  <!-- Rows -->
                  <tr
                    v-else
                    v-for="order in recentOrders"
                    :key="order.id"
                    class="hover:bg-gray-50/50 transition-colors"
                  >
                    <td class="py-4 font-bold tracking-wide font-mono">{{ order.order_code }}</td>
                    <td class="py-4 text-gray-500 font-normal">{{ formatDate(order.created_at) }}</td>
                    <td class="py-4">
                      <span :class="statusClass(order.status)">
                        {{ statusText(order.status) }}
                      </span>
                    </td>
                    <td class="py-4 font-normal text-gray-800 font-mono">{{ formatPrice(order.final_amount) }}</td>
                    <td class="py-4 text-right">
                      <router-link
                        :to="{ name: 'CheckoutSuccess', query: { code: order.order_code } }"
                        class="text-[10px] font-bold uppercase tracking-wider underline hover:text-gray-500 text-gray-800"
                      >CHI TIẾT</router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div v-else>
          <RouterView />
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>