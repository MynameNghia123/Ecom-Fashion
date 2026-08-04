<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Thống kê & Phân tích</h1>
        <p class="text-sm text-slate-500 mt-0.5">Báo cáo hiệu suất kinh doanh theo thời gian thực</p>
      </div>

      <!-- Date Range Controls -->
      <div class="flex flex-wrap items-center gap-2">
        <!-- Preset buttons -->
        <div class="flex items-center gap-1 bg-white border border-slate-200 rounded-xl p-1">
          <button
            v-for="preset in datePresets"
            :key="preset.key"
            @click="applyPreset(preset)"
            :class="[
              'px-3 py-1.5 text-xs font-semibold rounded-lg transition-all',
              activePreset === preset.key
                ? 'bg-[#0258cb] text-white shadow-sm'
                : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'
            ]"
          >
            {{ preset.label }}
          </button>
        </div>

        <!-- Custom date inputs -->
        <div class="flex items-center gap-2">
          <input
            v-model="customStart"
            type="date"
            class="text-xs border border-slate-200 rounded-xl px-3 py-2 text-slate-700 bg-white focus:border-[#0258cb] focus:outline-none transition-all"
          />
          <span class="text-slate-400 text-xs font-medium">→</span>
          <input
            v-model="customEnd"
            type="date"
            class="text-xs border border-slate-200 rounded-xl px-3 py-2 text-slate-700 bg-white focus:border-[#0258cb] focus:outline-none transition-all"
          />
          <button
            @click="applyCustomRange"
            class="px-3 py-2 bg-[#0258cb] text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors"
          >
            Áp dụng
          </button>
        </div>
      </div>
    </div>

    <!-- Error Alert -->
    <div
      v-if="statStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700"
    >
      <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ statStore.error }}
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <div
        v-for="kpi in kpiCards"
        :key="kpi.key"
        class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col gap-4"
      >
        <div class="flex items-start justify-between">
          <div :class="`w-11 h-11 rounded-xl flex items-center justify-center ${kpi.iconBg}`">
            <component :is="kpi.icon" class="w-5 h-5" :class="kpi.iconColor" />
          </div>
          <span
            :class="[
              'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold',
              kpi.change >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
            ]"
          >
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline v-if="kpi.change >= 0" points="18 15 12 9 6 15"/>
              <polyline v-else points="6 9 12 15 18 9"/>
            </svg>
            {{ Math.abs(kpi.change) }}%
          </span>
        </div>
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ kpi.label }}</p>
          <p class="text-2xl font-bold text-slate-800 mt-1">
            <span v-if="statStore.loadingDashboard" class="inline-block w-24 h-7 bg-slate-100 rounded animate-pulse"></span>
            <span v-else>{{ kpi.value }}</span>
          </p>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      <!-- Revenue Chart -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
          <div>
            <h3 class="text-base font-bold text-slate-900">Doanh thu & Lợi nhuận</h3>
            <p class="text-xs text-slate-400 mt-0.5">So sánh doanh thu và lợi nhuận gộp theo thời gian</p>
          </div>
          <div class="flex items-center gap-1 bg-slate-50 rounded-lg p-1">
            <button
              v-for="gb in groupByOptions"
              :key="gb.value"
              @click="changeGroupBy(gb.value)"
              :class="[
                'px-3 py-1.5 text-xs font-semibold rounded-md transition-all',
                statStore.dateRange.group_by === gb.value
                  ? 'bg-white text-slate-800 shadow-sm'
                  : 'text-slate-400 hover:text-slate-600'
              ]"
            >
              {{ gb.label }}
            </button>
          </div>
        </div>

        <!-- Loading skeleton -->
        <div v-if="statStore.loadingDashboard" class="h-52 flex items-center justify-center">
          <div class="flex flex-col items-center gap-2 text-slate-400">
            <svg class="w-8 h-8 animate-spin text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
            <span class="text-sm">Đang tải biểu đồ...</span>
          </div>
        </div>

        <!-- Chart -->
        <div v-else-if="chartData.labels.length > 0" class="relative">
          <!-- Legend -->
          <div class="flex items-center gap-5 mb-3 text-xs font-semibold text-slate-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#0258cb]"></span>Doanh thu</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-emerald-500"></span>Lợi nhuận gộp</span>
          </div>
          <!-- SVG Bar Chart -->
          <svg :viewBox="`0 0 ${svgWidth} ${svgHeight}`" class="w-full" :style="`height:${svgHeight}px`" xmlns="http://www.w3.org/2000/svg">
            <!-- Y-axis guide lines -->
            <line v-for="i in 5" :key="i"
              x1="40" :y1="chartPadding + ((svgHeight - chartPadding - 30) / 4) * (i - 1)"
              :x2="svgWidth - 10" :y2="chartPadding + ((svgHeight - chartPadding - 30) / 4) * (i - 1)"
              stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4"
            />
            <!-- Bars -->
            <template v-for="(label, i) in chartData.labels" :key="i">
              <!-- Revenue bar -->
              <rect
                :x="getBarX(i)"
                :y="getBarY(chartData.revenue[i], maxChartValue)"
                :width="barWidth"
                :height="getBarH(chartData.revenue[i], maxChartValue)"
                rx="3"
                :fill="hoveredBar === i ? '#0246a8' : '#0258cb'"
                @mouseenter="hoveredBar = i; tooltipIndex = i"
                @mouseleave="hoveredBar = -1; tooltipIndex = -1"
                class="transition-colors cursor-pointer"
              />
              <!-- Profit bar -->
              <rect
                :x="getBarX(i) + barWidth + 2"
                :y="getBarY(chartData.profit[i], maxChartValue)"
                :width="barWidth"
                :height="getBarH(chartData.profit[i], maxChartValue)"
                rx="3"
                fill="#10b981"
                opacity="0.85"
              />
              <!-- X label -->
              <text
                :x="getBarX(i) + barWidth"
                :y="svgHeight - 8"
                text-anchor="middle"
                font-size="9"
                font-weight="600"
                fill="#94a3b8"
              >{{ formatChartLabel(label) }}</text>
            </template>

            <!-- Tooltip -->
            <template v-if="tooltipIndex >= 0">
              <rect
                :x="Math.min(getBarX(tooltipIndex) + barWidth - 40, svgWidth - 130)"
                :y="Math.max(getBarY(chartData.revenue[tooltipIndex], maxChartValue) - 60, 5)"
                width="125"
                height="52"
                rx="6"
                fill="#1e293b"
                opacity="0.95"
              />
              <text
                :x="Math.min(getBarX(tooltipIndex) + barWidth - 40, svgWidth - 130) + 8"
                :y="Math.max(getBarY(chartData.revenue[tooltipIndex], maxChartValue) - 60, 5) + 16"
                font-size="10"
                fill="white"
                font-weight="600"
              >{{ chartData.labels[tooltipIndex] }}</text>
              <text
                :x="Math.min(getBarX(tooltipIndex) + barWidth - 40, svgWidth - 130) + 8"
                :y="Math.max(getBarY(chartData.revenue[tooltipIndex], maxChartValue) - 60, 5) + 31"
                font-size="9"
                fill="#93c5fd"
              >DT: {{ formatCompact(chartData.revenue[tooltipIndex]) }}</text>
              <text
                :x="Math.min(getBarX(tooltipIndex) + barWidth - 40, svgWidth - 130) + 8"
                :y="Math.max(getBarY(chartData.revenue[tooltipIndex], maxChartValue) - 60, 5) + 46"
                font-size="9"
                fill="#6ee7b7"
              >LN: {{ formatCompact(chartData.profit[tooltipIndex]) }}</text>
            </template>
          </svg>
        </div>

        <!-- Empty state -->
        <div v-else class="h-52 flex items-center justify-center text-slate-400">
          <div class="text-center">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
              <line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/>
            </svg>
            <p class="text-sm">Chưa có dữ liệu trong khoảng thời gian này</p>
          </div>
        </div>
      </div>

      <!-- Category Doughnut Chart -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col">
        <h3 class="text-base font-bold text-slate-900 mb-1">Doanh thu theo danh mục</h3>
        <p class="text-xs text-slate-400 mb-5">Phân bổ doanh thu theo ngành hàng</p>

        <div v-if="statStore.loadingDashboard" class="flex-1 flex items-center justify-center">
          <div class="w-32 h-32 rounded-full bg-slate-100 animate-pulse"></div>
        </div>

        <template v-else-if="categoryData.labels.length > 0">
          <!-- SVG Doughnut -->
          <div class="relative w-36 h-36 mx-auto">
            <svg viewBox="0 0 120 120" class="w-full h-full transform -rotate-90">
              <circle cx="60" cy="60" r="48" fill="none" stroke="#f1f5f9" stroke-width="16"/>
              <template v-for="(seg, i) in donutSegments" :key="i">
                <circle
                  cx="60" cy="60" r="48"
                  fill="none"
                  :stroke="categoryColors[i % categoryColors.length]"
                  stroke-width="16"
                  :stroke-dasharray="`${seg.dash} ${circumference}`"
                  :stroke-dashoffset="seg.offset"
                  stroke-linecap="round"
                />
              </template>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
              <span class="text-lg font-extrabold text-slate-800">100%</span>
              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Tổng thể</span>
            </div>
          </div>

          <!-- Legend -->
          <div class="mt-5 space-y-2.5 flex-1">
            <div v-for="(label, i) in categoryData.labels" :key="i" class="flex items-center justify-between text-xs">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="`background:${categoryColors[i % categoryColors.length]}`"></span>
                <span class="text-slate-600 font-medium truncate max-w-[100px]">{{ label }}</span>
              </div>
              <span class="font-bold text-slate-700">{{ categoryData.percentages[i] }}%</span>
            </div>
          </div>
        </template>

        <div v-else class="flex-1 flex items-center justify-center text-slate-400 text-center text-sm">
          Chưa có dữ liệu danh mục
        </div>
      </div>
    </div>

    <!-- Order Status + Recent Orders Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      <!-- Order Status Distribution -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-900 mb-5">Phân bổ trạng thái đơn hàng</h3>

        <div v-if="statStore.loadingDashboard" class="space-y-3">
          <div v-for="i in 5" :key="i" class="h-9 bg-slate-100 rounded-xl animate-pulse"></div>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="status in orderStatusList"
            :key="status.key"
            class="flex items-center gap-3"
          >
            <div :class="`w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${status.bg}`">
              <span :class="`w-2 h-2 rounded-full ${status.dot}`"></span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold text-slate-600">{{ status.label }}</span>
                <span class="text-xs font-bold text-slate-800">{{ statStore.orderStatus[status.key] }}</span>
              </div>
              <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div
                  :class="`h-full rounded-full transition-all duration-700 ${status.bar}`"
                  :style="`width:${getStatusPercent(status.key)}%`"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h3 class="text-base font-bold text-slate-900">Đơn hàng gần đây</h3>
          <router-link
            to="/admin/orders"
            class="text-[#0258cb] text-xs font-bold hover:underline no-underline"
          >Xem tất cả →</router-link>
        </div>

        <div v-if="statStore.loadingDashboard" class="p-4 space-y-3">
          <div v-for="i in 4" :key="i" class="h-14 bg-slate-100 rounded-xl animate-pulse"></div>
        </div>

        <div v-else-if="statStore.recentOrders.length === 0" class="py-12 text-center text-slate-400 text-sm">
          Chưa có đơn hàng nào
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[500px]">
            <thead>
              <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                <th class="py-3 px-5">Mã đơn</th>
                <th class="py-3 px-5">Khách hàng</th>
                <th class="py-3 px-5 text-right">Tổng tiền</th>
                <th class="py-3 px-5">Trạng thái</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
              <tr
                v-for="order in statStore.recentOrders"
                :key="order.id"
                class="hover:bg-slate-50/50 transition-colors"
              >
                <td class="py-3.5 px-5 font-mono font-bold text-[#0258cb] text-xs">{{ order.order_code }}</td>
                <td class="py-3.5 px-5 text-slate-700 font-medium">{{ order.customer_name }}</td>
                <td class="py-3.5 px-5 text-right font-bold text-slate-800 font-mono text-xs">{{ formatPrice(order.final_amount) }}</td>
                <td class="py-3.5 px-5">
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold" :class="getStatusClass(order.status)">
                    {{ getStatusText(order.status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Top Products + Low Stock Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      <!-- Top Selling Products -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <div>
            <h3 class="text-base font-bold text-slate-900">Top sản phẩm bán chạy</h3>
            <p class="text-xs text-slate-400 mt-0.5">Xếp hạng theo số lượng bán</p>
          </div>
          <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          </div>
        </div>

        <div v-if="statStore.loadingTopProducts" class="p-4 space-y-3">
          <div v-for="i in 5" :key="i" class="h-12 bg-slate-100 rounded-xl animate-pulse"></div>
        </div>

        <div v-else-if="statStore.topProducts.length === 0" class="py-10 text-center text-slate-400 text-sm">
          Chưa có dữ liệu bán hàng trong khoảng thời gian này
        </div>

        <div v-else class="divide-y divide-slate-50">
          <div
            v-for="(product, i) in statStore.topProducts.slice(0, 8)"
            :key="product.id"
            class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/50 transition-colors"
          >
            <!-- Rank -->
            <span
              :class="[
                'w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-extrabold shrink-0',
                i === 0 ? 'bg-amber-100 text-amber-700' :
                i === 1 ? 'bg-slate-200 text-slate-600' :
                i === 2 ? 'bg-orange-100 text-orange-700' :
                'bg-slate-100 text-slate-500'
              ]"
            >{{ i + 1 }}</span>

            <!-- Thumbnail -->
            <img
              :src="getImageUrl(product.thumbnail)"
              class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-100 shrink-0"
              :alt="product.name"
            />

            <!-- Info -->
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-800 truncate">{{ product.name }}</p>
              <p class="text-xs text-slate-400 font-medium mt-0.5">Đã bán: <span class="text-slate-600 font-bold">{{ product.total_sold }}</span> sản phẩm</p>
            </div>

            <!-- Revenue -->
            <div class="text-right shrink-0">
              <p class="text-sm font-bold text-slate-800 font-mono">{{ formatCompact(product.total_revenue) }}</p>
              <p class="text-[10px] text-slate-400">doanh thu</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Low Stock Alert -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <div>
            <h3 class="text-base font-bold text-slate-900">Cảnh báo tồn kho thấp</h3>
            <p class="text-xs text-slate-400 mt-0.5">Biến thể có tồn kho ≤ {{ lowStockThreshold }} sản phẩm</p>
          </div>
          <div class="flex items-center gap-2">
            <span v-if="statStore.lowStockProducts.length > 0" class="px-2 py-0.5 bg-rose-50 text-rose-600 text-xs font-bold rounded-full">
              {{ statStore.lowStockProducts.length }} SKU
            </span>
            <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
          </div>
        </div>

        <div v-if="statStore.loadingLowStock" class="p-4 space-y-3">
          <div v-for="i in 5" :key="i" class="h-12 bg-slate-100 rounded-xl animate-pulse"></div>
        </div>

        <div v-else-if="statStore.lowStockProducts.length === 0" class="py-10 text-center">
          <div class="flex flex-col items-center gap-2 text-slate-400">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-600">Tồn kho ổn định</p>
            <p class="text-xs text-slate-400">Không có sản phẩm nào sắp hết hàng</p>
          </div>
        </div>

        <div v-else class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto">
          <div
            v-for="variant in statStore.lowStockProducts"
            :key="variant.variant_id"
            class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/50 transition-colors"
          >
            <!-- Thumbnail -->
            <img
              :src="getImageUrl(variant.thumbnail)"
              class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-100 shrink-0"
              :alt="variant.product_name"
            />

            <!-- Info -->
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-800 truncate">{{ variant.product_name }}</p>
              <p class="text-[11px] font-mono text-slate-400 mt-0.5">{{ variant.sku }}</p>
            </div>

            <!-- Stock badge -->
            <div class="shrink-0 text-right">
              <span
                :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold',
                  variant.stock_quantity === 0
                    ? 'bg-rose-100 text-rose-700'
                    : variant.stock_quantity <= 5
                      ? 'bg-orange-50 text-orange-700'
                      : 'bg-amber-50 text-amber-700'
                ]"
              >
                {{ variant.stock_quantity === 0 ? 'Hết hàng' : `Còn ${variant.stock_quantity}` }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStatisticStore } from '@/stores/admin/statisticStore'

const statStore = useStatisticStore()

// ─── Date Presets ─────────────────────────────────────────────────────────────
const activePreset = ref('30d')
const customStart  = ref(statStore.dateRange.start_date)
const customEnd    = ref(statStore.dateRange.end_date)
const hoveredBar   = ref(-1)
const tooltipIndex = ref(-1)
const lowStockThreshold = 10

const datePresets = [
  { key: 'today', label: 'Hôm nay' },
  { key: '7d',    label: '7 ngày'  },
  { key: '30d',   label: '30 ngày' },
  { key: 'quarter', label: 'Quý này' },
  { key: 'year',  label: 'Năm nay' },
]

const groupByOptions = [
  { value: 'day',   label: 'Ngày'  },
  { value: 'week',  label: 'Tuần'  },
  { value: 'month', label: 'Tháng' },
]

function todayStr() {
  return new Date().toISOString().slice(0, 10)
}
function daysAgo(n) {
  const d = new Date()
  d.setDate(d.getDate() - n)
  return d.toISOString().slice(0, 10)
}
function quarterStart() {
  const d = new Date()
  const q = Math.floor(d.getMonth() / 3)
  return new Date(d.getFullYear(), q * 3, 1).toISOString().slice(0, 10)
}
function yearStart() {
  return `${new Date().getFullYear()}-01-01`
}

function applyPreset(preset) {
  activePreset.value = preset.key
  let start, end = todayStr()
  switch (preset.key) {
    case 'today':   start = todayStr();    break
    case '7d':      start = daysAgo(6);   break
    case '30d':     start = daysAgo(29);  break
    case 'quarter': start = quarterStart(); break
    case 'year':    start = yearStart();   break
    default:        start = daysAgo(29);
  }
  customStart.value = start
  customEnd.value   = end

  const groupBy = preset.key === 'year' ? 'month'
                : preset.key === 'quarter' ? 'week'
                : 'day'

  statStore.setDateRange({ start_date: start, end_date: end, group_by: groupBy })
  statStore.fetchLowStock()
}

function applyCustomRange() {
  activePreset.value = ''
  statStore.setDateRange({ start_date: customStart.value, end_date: customEnd.value })
  statStore.fetchLowStock()
}

function changeGroupBy(gb) {
  statStore.setDateRange({ group_by: gb })
}

// ─── KPI Cards ────────────────────────────────────────────────────────────────
const kpiCards = computed(() => {
  const ov = statStore.overview
  return [
    {
      key:       'revenue',
      label:     'Tổng doanh thu',
      value:     formatPrice(ov.total_revenue),
      change:    ov.revenue_change_percent,
      iconBg:    'bg-blue-50',
      iconColor: 'text-[#0258cb]',
      icon:      RevenueIcon,
    },
    {
      key:       'orders',
      label:     'Tổng đơn hàng',
      value:     ov.total_orders.toLocaleString('vi-VN'),
      change:    ov.orders_change_percent,
      iconBg:    'bg-orange-50',
      iconColor: 'text-orange-500',
      icon:      OrderIcon,
    },
    {
      key:       'customers',
      label:     'Khách hàng mới',
      value:     ov.new_customers.toLocaleString('vi-VN'),
      change:    ov.customers_change_percent,
      iconBg:    'bg-emerald-50',
      iconColor: 'text-emerald-500',
      icon:      CustomerIcon,
    },
    {
      key:       'aov',
      label:     'Giá trị đơn TB',
      value:     formatPrice(ov.average_order_value),
      change:    ov.aov_change_percent,
      iconBg:    'bg-indigo-50',
      iconColor: 'text-indigo-500',
      icon:      AovIcon,
    },
  ]
})

// ─── Chart Data ───────────────────────────────────────────────────────────────
const chartData = computed(() => statStore.revenueChart)
const categoryData = computed(() => statStore.categoryChart)

const svgWidth  = 600
const svgHeight = 220
const chartPadding = 20
const maxChartValue = computed(() => {
  const allVals = [...(chartData.value.revenue ?? []), ...(chartData.value.profit ?? [])]
  return allVals.length > 0 ? Math.max(...allVals) * 1.15 : 1
})

const barWidth = computed(() => {
  const n = chartData.value.labels?.length || 1
  const availableWidth = svgWidth - 50 - 10
  const totalBarWidth  = availableWidth / n
  return Math.max(Math.min(totalBarWidth * 0.35, 22), 6)
})

function getBarX(i) {
  const n = chartData.value.labels?.length || 1
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
  if (label.includes('W')) return label.slice(-4) // week
  if (label.length === 7) {
    const [y, m] = label.split('-')
    return `T${parseInt(m)}/${y.slice(2)}`
  }
  return label.slice(5) // day: MM-DD
}

// ─── Donut Chart ─────────────────────────────────────────────────────────────
const circumference = 2 * Math.PI * 48
const categoryColors = ['#0258cb', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#ec4899', '#64748b']

const donutSegments = computed(() => {
  const data   = categoryData.value.data ?? []
  const total  = data.reduce((s, v) => s + v, 0)
  let offset   = 0
  return data.map(v => {
    const dash = (v / total) * circumference
    const seg  = { dash, offset: -offset }
    offset += dash
    return seg
  })
})

// ─── Order Status ─────────────────────────────────────────────────────────────
const orderStatusList = [
  { key: 'pending',   label: 'Chờ xử lý',   bg: 'bg-amber-50',   dot: 'bg-amber-500',   bar: 'bg-amber-400'   },
  { key: 'confirmed', label: 'Đã xác nhận', bg: 'bg-blue-50',    dot: 'bg-blue-500',    bar: 'bg-blue-500'    },
  { key: 'shipping',  label: 'Đang giao',   bg: 'bg-indigo-50',  dot: 'bg-indigo-500',  bar: 'bg-indigo-500'  },
  { key: 'completed', label: 'Đã giao',     bg: 'bg-emerald-50', dot: 'bg-emerald-500', bar: 'bg-emerald-500' },
  { key: 'cancelled', label: 'Đã hủy',      bg: 'bg-rose-50',    dot: 'bg-rose-500',    bar: 'bg-rose-400'    },
]

const totalOrdersInPeriod = computed(() => {
  const s = statStore.orderStatus
  return (s.pending + s.confirmed + s.shipping + s.completed + s.cancelled) || 1
})

function getStatusPercent(key) {
  return Math.round((statStore.orderStatus[key] / totalOrdersInPeriod.value) * 100)
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function formatPrice(value) {
  if (!value) return '0 đ'
  return Number(value).toLocaleString('vi-VN') + ' đ'
}

function formatCompact(value) {
  if (!value) return '0đ'
  const n = Number(value)
  if (n >= 1_000_000_000) return (n / 1_000_000_000).toFixed(1) + 'T'
  if (n >= 1_000_000)     return (n / 1_000_000).toFixed(1) + 'Tr'
  if (n >= 1_000)         return (n / 1_000).toFixed(0) + 'K'
  return n.toLocaleString('vi-VN') + 'đ'
}

function getImageUrl(path) {
  if (!path) return 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?q=80&w=100&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  const base = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  return `${base}/storage/${path}`
}

function getStatusClass(status) {
  const map = {
    pending:   'bg-amber-50 text-amber-700',
    confirmed: 'bg-blue-50 text-blue-700',
    shipping:  'bg-indigo-50 text-indigo-700',
    completed: 'bg-emerald-50 text-emerald-700',
    cancelled: 'bg-rose-50 text-rose-700',
  }
  return map[status] || 'bg-slate-50 text-slate-600'
}

function getStatusText(status) {
  const map = {
    pending: 'Chờ xử lý', confirmed: 'Đã xác nhận',
    shipping: 'Đang giao', completed: 'Đã giao', cancelled: 'Đã hủy',
  }
  return map[status] || status
}

// ─── Icon Components (inline SVG as functional components) ────────────────────
const RevenueIcon = {
  template: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="12" y1="10" x2="12" y2="14"/><line x1="8" y1="12" x2="16" y2="12"/></svg>`
}
const OrderIcon = {
  template: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>`
}
const CustomerIcon = {
  template: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`
}
const AovIcon = {
  template: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`
}

// ─── Lifecycle ────────────────────────────────────────────────────────────────
onMounted(async () => {
  await Promise.all([
    statStore.fetchDashboard(),
    statStore.fetchTopProducts(),
    statStore.fetchLowStock(),
  ])
})
</script>
