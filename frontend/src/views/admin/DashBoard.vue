<template>
  <div class="dashboard-page space-y-6">


    <!-- Error Alert -->
    <div
      v-if="statStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700 animate-fade-in"
    >
      <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ statStore.error }}
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Card 1: Revenue -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="2" />
              <line x1="12" y1="10" x2="12" y2="14" />
              <line x1="8" y1="12" x2="16" y2="12" />
            </svg>
          </div>
          <svg class="w-16 h-8 text-emerald-500 opacity-60" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M0 25 L20 20 L40 25 L60 15 L80 15 L100 5"></path>
          </svg>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng doanh thu</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-28 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ formatPrice(statStore.overview.total_revenue) }}</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Orders -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
              <line x1="3" y1="6" x2="21" y2="6" />
              <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>
          </div>
          <svg class="w-16 h-8 text-blue-500 opacity-60" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M0 20 L20 10 L40 15 L60 5 L80 10 L100 5"></path>
          </svg>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đơn hàng mới</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-16 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ statStore.overview.total_orders.toLocaleString('vi-VN') }}</span>
          </div>
        </div>
      </div>

      <!-- Card 3: Customers -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <svg class="w-16 h-8 text-amber-500 opacity-60" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M0 15 L20 20 L40 10 L60 15 L80 5 L100 10"></path>
          </svg>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Khách hàng mới</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-16 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ statStore.overview.new_customers.toLocaleString('vi-VN') }}</span>
          </div>
        </div>
      </div>

      <!-- Card 4: AOV -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
          </div>
          <svg class="w-16 h-8 text-indigo-500 opacity-60" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M0 25 L20 15 L40 20 L60 10 L80 5 L100 5"></path>
          </svg>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tỷ lệ hoàn thành</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-24 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ statStore.overview.total_orders > 0 ? ((statStore.orderStatus.completed / statStore.overview.total_orders) * 100).toFixed(1) : 0 }}%</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Middle Section: Recent Orders & Alerts -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Recent Orders Section (Wider) -->
      <div class="xl:col-span-2 bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-base font-bold text-slate-900">Đơn hàng gần đây</h3>
          <router-link to="/admin/orders" class="text-black text-sm font-bold hover:underline no-underline">Xem tất cả</router-link>
        </div>

        <div v-if="statStore.loadingDashboard" class="py-12 flex items-center justify-center">
          <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
        </div>
        <template v-else>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[500px]">
              <thead>
                <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                  <th class="py-3 px-4">Mã đơn</th>
                  <th class="py-3 px-4">Khách hàng</th>
                  <th class="py-3 px-4">Tổng tiền</th>
                  <th class="py-3 px-4">Trạng thái</th>
                </tr>
              </thead>
              <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                <tr v-for="order in statStore.recentOrders.slice(0,5)" :key="order.id" class="hover:bg-slate-50/50 transition-colors">
                  <td class="py-4 px-4 text-black font-semibold font-mono whitespace-nowrap">{{ order.order_code }}</td>
                  <td class="py-4 px-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-extrabold bg-neutral-900 text-white shrink-0">
                        {{ (order.customer_name || 'K').charAt(0).toUpperCase() }}
                      </div>
                      <span class="text-slate-800 font-semibold">{{ order.customer_name || 'Khách vãng lai' }}</span>
                    </div>
                  </td>
                  <td class="py-4 px-4 text-slate-800 font-bold font-mono whitespace-nowrap">{{ formatPrice(order.final_amount) }}</td>
                  <td class="py-4 px-4 whitespace-nowrap">
                    <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold ${getStatusClass(order.status)}`">
                      {{ getStatusText(order.status) }}
                    </span>
                  </td>
                </tr>
                <tr v-if="statStore.recentOrders.length === 0">
                  <td colspan="4" class="text-center py-8 text-slate-400 font-medium">Chưa có đơn hàng nào</td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>

      <!-- Alert Panel Section -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between">
        <div>
          <h3 class="text-base font-bold text-slate-900 mb-6">Cần xử lý ngay</h3>
          <div class="space-y-4">
            <!-- Pending Orders -->
            <div class="flex items-center justify-between p-4 bg-rose-50/50 border border-rose-100 rounded-xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div>
                  <p class="text-sm font-bold text-slate-900">{{ statStore.alerts?.pending_orders || 0 }} Đơn hàng</p>
                  <p class="text-xs font-medium text-slate-500 mt-0.5">Đang chờ xác nhận</p>
                </div>
              </div>
              <router-link to="/admin/orders?status=pending" class="px-3 py-1.5 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 transition-colors">Xem</router-link>
            </div>
            
            <!-- Return Requests -->
            <div class="flex items-center justify-between p-4 bg-amber-50/50 border border-amber-100 rounded-xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                </div>
                <div>
                  <p class="text-sm font-bold text-slate-900">{{ statStore.alerts?.return_requests || 0 }} Yêu cầu</p>
                  <p class="text-xs font-medium text-slate-500 mt-0.5">Hoàn trả cần xử lý</p>
                </div>
              </div>
              <router-link to="/admin/returns" class="px-3 py-1.5 bg-white border border-amber-200 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-50 transition-colors">Xem</router-link>
            </div>

            <!-- Low Stock -->
            <div class="flex items-center justify-between p-4 bg-orange-50/50 border border-orange-100 rounded-xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div>
                  <p class="text-sm font-bold text-slate-900">{{ statStore.alerts?.low_stock || 0 }} Sản phẩm</p>
                  <p class="text-xs font-medium text-slate-500 mt-0.5">Sắp hết hàng</p>
                </div>
              </div>
              <router-link to="/admin/products" class="px-3 py-1.5 bg-white border border-orange-200 text-orange-600 rounded-lg text-xs font-bold hover:bg-orange-50 transition-colors">Xem</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Section: Top Products Ranked List -->
    <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-base font-bold text-slate-900">Sản phẩm bán chạy nhất</h3>
        <router-link to="/admin/products" class="text-black text-sm font-bold hover:underline no-underline">Xem kho</router-link>
      </div>

      <div v-if="statStore.loadingTopProducts" class="py-8 flex items-center justify-center">
        <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
      </div>
      <template v-else>
        <div class="space-y-5">
          <div v-for="(product, index) in statStore.topProducts.slice(0,5)" :key="product.id" class="flex items-center gap-4">
            <!-- Rank -->
            <div class="w-6 font-bold text-lg text-center" :class="index === 0 ? 'text-amber-500' : index === 1 ? 'text-slate-400' : index === 2 ? 'text-amber-700' : 'text-slate-300'">
              #{{ index + 1 }}
            </div>
            
            <!-- Image -->
            <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center">
              <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover" />
              <div v-else class="text-slate-400 text-[10px] font-medium">No img</div>
            </div>
            
            <!-- Info & Progress bar -->
            <div class="flex-1 min-w-0">
              <div class="flex justify-between items-end mb-1.5">
                <div>
                  <p class="text-sm font-bold text-slate-900 truncate">{{ product.name }}</p>
                  <p class="text-xs text-slate-500 mt-0.5 truncate">{{ product.category_name || 'Khác' }} • Đã bán: {{ product.total_sold || 0 }}</p>
                </div>
                <p class="text-sm font-bold text-emerald-600 font-mono">{{ formatPrice(product.total_revenue || 0) }}</p>
              </div>
              <!-- Progress bar -->
              <div class="w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-black h-1.5 rounded-full" :style="{ width: Math.max(2, ((product.total_revenue || 0) / (statStore.topProducts[0]?.total_revenue || 1)) * 100) + '%' }"></div>
              </div>
            </div>
          </div>
          
          <div v-if="statStore.topProducts.length === 0" class="text-center py-6 text-slate-400 font-medium text-sm">
            Chưa có dữ liệu sản phẩm bán chạy
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStatisticStore } from '@/stores/admin/statisticStore'

const router = useRouter()
const statStore = useStatisticStore()

// Custom Vietnamese current date formatted
const d = new Date()
const day = String(d.getDate()).padStart(2, '0')
const month = String(d.getMonth() + 1).padStart(2, '0')
const year = d.getFullYear()
const currentDate = `${day}/${month}/${year}`


function formatPrice(value) {
  if (!value) return '0 đ'
  return Number(value).toLocaleString('vi-VN') + ' đ'
}

function getStatusClass(status) {
  const map = {
    pending: 'bg-amber-50 text-amber-700',
    confirmed: 'bg-blue-50 text-blue-700',
    shipping: 'bg-indigo-50 text-indigo-700',
    completed: 'bg-emerald-50 text-emerald-700',
    cancelled: 'bg-rose-50 text-rose-700'
  }
  return map[status] || 'bg-slate-50 text-slate-600'
}

function getStatusText(status) {
  const map = {
    pending: 'Chờ xử lý',
    confirmed: 'Đã xác nhận',
    shipping: 'Đang giao',
    completed: 'Đã giao',
    cancelled: 'Đã hủy'
  }
  return map[status] || status
}

onMounted(() => {
  statStore.fetchDashboard()
  statStore.fetchTopProducts()
})
</script>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style>
