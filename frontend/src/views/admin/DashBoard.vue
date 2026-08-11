<template>
  <div class="dashboard-page space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Trang Dashboard Tổng Quan</h2>
        <p class="text-sm text-slate-500 mt-1">Xin chào Admin, đây là báo cáo hiệu suất kinh doanh hôm nay.</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="flex items-center gap-2 px-4 py-2 border border-slate-200 bg-white rounded-lg text-sm text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm cursor-pointer">
          <svg class="w-4.5 h-4.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
          </svg>
          Hôm nay: {{ currentDate }}
        </button>
        <button @click="router.push('/admin/statistics')" class="flex items-center gap-2 px-4 py-2 bg-black text-white rounded-lg text-sm font-semibold hover:bg-neutral-800 transition-colors shadow-sm cursor-pointer">
          <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
          Xem báo cáo chi tiết
        </button>
      </div>
    </div>

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
          <span
            :class="[
              'inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold',
              statStore.overview.revenue_change_percent >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
            ]"
          >
            {{ statStore.overview.revenue_change_percent >= 0 ? '+' : '' }}{{ statStore.overview.revenue_change_percent }}%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline v-if="statStore.overview.revenue_change_percent >= 0" points="18 15 12 9 6 15" />
              <polyline v-else points="6 9 12 15 18 9" />
            </svg>
          </span>
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
          <span
            :class="[
              'inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold',
              statStore.overview.orders_change_percent >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
            ]"
          >
            {{ statStore.overview.orders_change_percent >= 0 ? '+' : '' }}{{ statStore.overview.orders_change_percent }}%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline v-if="statStore.overview.orders_change_percent >= 0" points="18 15 12 9 6 15" />
              <polyline v-else points="6 9 12 15 18 9" />
            </svg>
          </span>
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
          <span
            :class="[
              'inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold',
              statStore.overview.customers_change_percent >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
            ]"
          >
            {{ statStore.overview.customers_change_percent >= 0 ? '+' : '' }}{{ statStore.overview.customers_change_percent }}%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline v-if="statStore.overview.customers_change_percent >= 0" points="18 15 12 9 6 15" />
              <polyline v-else points="6 9 12 15 18 9" />
            </svg>
          </span>
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
          <span
            :class="[
              'inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold',
              statStore.overview.aov_change_percent >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
            ]"
          >
            {{ statStore.overview.aov_change_percent >= 0 ? '+' : '' }}{{ statStore.overview.aov_change_percent }}%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline v-if="statStore.overview.aov_change_percent >= 0" points="18 15 12 9 6 15" />
              <polyline v-else points="6 9 12 15 18 9" />
            </svg>
          </span>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Giá trị đơn TB</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-24 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ formatPrice(statStore.overview.average_order_value) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Weekly Revenue Chart -->
      <div class="lg:col-span-2 bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-base font-bold text-slate-900">Hiệu suất doanh thu &amp; lợi nhuận</h3>
            <p class="text-xs text-slate-400 mt-0.5">Biểu đồ so sánh doanh thu và lợi nhuận gộp thực tế</p>
          </div>
          <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-black"></span>Doanh thu</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-neutral-400"></span>Lợi nhuận</span>
          </div>
        </div>

        <div v-if="statStore.loadingDashboard" class="h-[220px] flex items-center justify-center">
          <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
        </div>
        <div v-else class="relative w-full overflow-hidden mt-2">
          <svg class="w-full h-[220px]" viewBox="0 0 600 220" preserveAspectRatio="none">
            <!-- Horizontal Grid Lines -->
            <line x1="30" y1="30" x2="570" y2="30" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="75" x2="570" y2="75" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="120" x2="570" y2="120" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="165" x2="570" y2="165" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="200" x2="570" y2="200" stroke="#e2e8f0" stroke-width="1.5" />

            <!-- Bar Charts for Revenue & Profit -->
            <g v-for="(label, i) in statStore.revenueChart.labels" :key="'g-'+i">
              <!-- Revenue Bar (Black) -->
              <rect
                :x="getBarX(i)"
                :y="getBarY(statStore.revenueChart.revenue[i], maxChartValue)"
                :width="barWidth"
                :height="getBarH(statStore.revenueChart.revenue[i], maxChartValue)"
                rx="4"
                fill="#000000"
                :fill-opacity="hoveredBar === i ? 0.85 : 1"
                class="transition-all duration-150 cursor-pointer"
                @mouseenter="hoveredBar = i"
                @mouseleave="hoveredBar = -1"
              />
              <!-- Profit Bar (Dark Gray) -->
              <rect
                :x="getBarX(i) + barWidth + 2"
                :y="getBarY(statStore.revenueChart.profit[i] || 0, maxChartValue)"
                :width="barWidth"
                :height="getBarH(statStore.revenueChart.profit[i] || 0, maxChartValue)"
                rx="4"
                fill="#a3a3a3"
                :fill-opacity="hoveredBar === i ? 0.8 : 1"
                class="transition-all duration-150 cursor-pointer"
                @mouseenter="hoveredBar = i"
                @mouseleave="hoveredBar = -1"
              />
            </g>

            <!-- Tooltip overlay or popovers -->
            <defs>
              <linearGradient id="chart-gradient" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#000000" />
                <stop offset="100%" stop-color="#000000" stop-opacity="0" />
              </linearGradient>
            </defs>

            <!-- X-Axis Labels in SVG -->
            <text
              v-for="(label, i) in statStore.revenueChart.labels"
              :key="'lbl-'+i"
              :x="getBarX(i) + barWidth"
              y="215"
              text-anchor="middle"
              class="text-[10px] font-bold fill-slate-400"
            >
              {{ formatChartLabel(label) }}
            </text>
          </svg>
        </div>
      </div>

      <!-- Share of Categories Chart -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between">
        <h3 class="text-base font-bold text-slate-900 mb-6">Thị phần ngành hàng</h3>

        <div v-if="statStore.loadingDashboard" class="h-44 flex items-center justify-center">
          <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
        </div>
        <template v-else>
          <div v-if="donutSegments.length > 0" class="relative w-44 h-44 mx-auto flex items-center justify-center my-2">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
              <!-- Background circle -->
              <circle cx="100" cy="100" r="70" stroke="#f1f5f9" stroke-width="16" fill="transparent" />
              <!-- Slices -->
              <circle
                v-for="(seg, i) in donutSegments"
                :key="i"
                cx="100"
                cy="100"
                r="70"
                :stroke="categoryColors[i % categoryColors.length]"
                stroke-width="16"
                fill="transparent"
                :stroke-dasharray="`${seg.dash} ${circumference}`"
                :stroke-dashoffset="seg.offset"
                stroke-linecap="round"
              />
            </svg>
            <div class="absolute flex flex-col items-center justify-center text-center">
              <span class="text-2xl font-extrabold text-slate-900">100%</span>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tổng thể</span>
            </div>
          </div>
          <div v-else class="h-44 flex items-center justify-center text-slate-400 text-xs">
            Chưa có dữ liệu danh mục
          </div>
        </template>

        <!-- Legend -->
        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs font-semibold text-slate-600 mt-6 max-h-[80px] overflow-y-auto">
          <div
            v-for="(label, i) in statStore.categoryChart.labels"
            :key="'leg-'+i"
            class="flex items-center gap-2"
          >
            <span :style="{ backgroundColor: categoryColors[i % categoryColors.length] }" class="w-2.5 h-2.5 rounded-full shrink-0"></span>
            <span class="truncate">{{ label }} ({{ statStore.categoryChart.percentages[i] }}%)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Section: Recent Orders & Top Products -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <!-- Recent Orders Section -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm">
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

      <!-- Top Products Section -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-base font-bold text-slate-900">Sản phẩm bán chạy</h3>
          <router-link to="/admin/products" class="text-black text-sm font-bold hover:underline no-underline">Xem kho</router-link>
        </div>

        <div v-if="statStore.loadingTopProducts" class="py-12 flex items-center justify-center">
          <div class="w-8 h-8 border-4 border-slate-200 border-t-black rounded-full animate-spin"></div>
        </div>
        <template v-else>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[500px]">
              <thead>
                <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                  <th class="py-3 px-4">Sản phẩm</th>
                  <th class="py-3 px-4 text-center">Đã bán</th>
                  <th class="py-3 px-4 text-right">Doanh thu</th>
                </tr>
              </thead>
              <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                <tr v-for="product in statStore.topProducts.slice(0,5)" :key="product.id" class="hover:bg-slate-50/50 transition-colors">
                  <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-medium">No img</div>
                      </div>
                      <div class="min-w-0">
                        <p class="text-slate-800 font-semibold truncate">{{ product.name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate">{{ product.category_name || 'Khác' }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4 text-center text-slate-800 font-bold whitespace-nowrap">{{ product.total_sold || 0 }}</td>
                  <td class="py-3 px-4 text-right text-emerald-600 font-bold font-mono whitespace-nowrap">{{ formatPrice(product.total_revenue || 0) }}</td>
                </tr>
                <tr v-if="statStore.topProducts.length === 0">
                  <td colspan="3" class="text-center py-8 text-slate-400 font-medium">Chưa có dữ liệu sản phẩm bán chạy</td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
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

const hoveredBar = ref(-1)

// SVGs configuration
const svgWidth = 600
const svgHeight = 220
const chartPadding = 20

const maxChartValue = computed(() => {
  const allVals = [
    ...(statStore.revenueChart.revenue ?? []),
    ...(statStore.revenueChart.profit ?? [])
  ]
  return allVals.length > 0 ? Math.max(...allVals) * 1.15 : 1
})

const barWidth = computed(() => {
  const n = statStore.revenueChart.labels?.length || 1
  const availableWidth = svgWidth - 50 - 10
  const totalBarWidth = availableWidth / n
  return Math.max(Math.min(totalBarWidth * 0.3, 16), 6)
})

function getBarX(i) {
  const n = statStore.revenueChart.labels?.length || 1
  const availableWidth = svgWidth - 50 - 10
  const slotWidth = availableWidth / n
  return 40 + i * slotWidth + (slotWidth - barWidth.value * 2 - 2) / 2
}

function getBarY(value, maxVal) {
  const chartH = svgHeight - chartPadding - 30
  const h = (value / maxVal) * chartH
  return chartPadding + chartH - h
}

function getBarH(value, maxVal) {
  const chartH = svgHeight - chartPadding - 30
  return Math.max((value / maxVal) * chartH, 1)
}

function formatChartLabel(label) {
  if (!label) return ''
  if (label.includes('W')) return label.slice(-4)
  if (label.length === 7) {
    const [y, m] = label.split('-')
    return `T${parseInt(m)}/${y.slice(2)}`
  }
  return label.slice(5) // MM-DD
}

// Donut Chart logic
const circumference = 2 * Math.PI * 70
const categoryColors = [
  '#000000',
  '#404040',
  '#737373',
  '#a3a3a3',
  '#d4d4d4',
  '#171717',
  '#525252',
  '#e5e5e5'
]

const donutSegments = computed(() => {
  const data = statStore.categoryChart.data ?? []
  const total = data.reduce((s, v) => s + v, 0) || 1
  let offset = 0
  return data.map(v => {
    const dash = (v / total) * circumference
    const seg = { dash, offset: -offset }
    offset += dash
    return seg
  })
})

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
