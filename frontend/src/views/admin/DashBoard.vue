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
          Hôm nay
        </button>
        <button class="flex items-center gap-2 px-4 py-2 bg-[#0258cb] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
          <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
          </svg>
          Xuất báo cáo
        </button>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Card 1 -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-[#0258cb]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="2" />
              <line x1="12" y1="10" x2="12" y2="14" />
              <line x1="8" y1="12" x2="16" y2="12" />
            </svg>
          </div>
          <span class="inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
            +12.5%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="18 15 12 9 6 15" />
            </svg>
          </span>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng doanh thu</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">1.284.000.000đ</div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
              <line x1="3" y1="6" x2="21" y2="6" />
              <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>
          </div>
          <span class="inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
            +8.2%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="18 15 12 9 6 15" />
            </svg>
          </span>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đơn hàng mới</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">342</div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <span class="inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">
            -2.4%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </span>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Khách hàng mới</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">84</div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
        <div class="flex items-start justify-between">
          <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-[#4f46e5]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <circle cx="12" cy="12" r="6" />
              <circle cx="12" cy="12" r="2" />
            </svg>
          </div>
          <span class="inline-flex items-center gap-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
            +1.1%
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="18 15 12 9 6 15" />
            </svg>
          </span>
        </div>
        <div class="mt-4">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tỷ lệ chuyển đổi</span>
          <div class="text-2xl font-bold text-slate-800 mt-1">4.82%</div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Weekly Revenue Chart -->
      <div class="lg:col-span-2 bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-base font-bold text-slate-900">Doanh thu theo tuần</h3>
            <p class="text-xs text-slate-400 mt-0.5">Biểu đồ so sánh doanh thu thực tế</p>
          </div>
          <div class="relative">
            <select class="appearance-none bg-white border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 text-xs font-semibold text-slate-600 outline-none cursor-pointer focus:border-[#0258cb]">
              <option>7 ngày qua</option>
              <option>30 ngày qua</option>
            </select>
            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9" />
              </svg>
            </span>
          </div>
        </div>

        <div class="relative w-full overflow-hidden mt-2">
          <svg class="w-full h-[220px]" viewBox="0 0 600 220" preserveAspectRatio="none">
            <!-- Horizontal Grid Lines -->
            <line x1="30" y1="30" x2="570" y2="30" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="75" x2="570" y2="75" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="120" x2="570" y2="120" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="165" x2="570" y2="165" stroke="#f1f5f9" stroke-width="1.5" stroke-dasharray="4" />
            <line x1="30" y1="200" x2="570" y2="200" stroke="#e2e8f0" stroke-width="1.5" />

            <!-- Bar Charts -->
            <!-- T2 -->
            <rect x="52" y="130" width="36" height="70" rx="4" fill="#dbeafe" />
            <!-- T3 -->
            <rect x="132" y="100" width="36" height="100" rx="4" fill="#dbeafe" />
            <!-- T4 (Active) -->
            <rect x="212" y="50" width="36" height="150" rx="4" fill="#0258cb" />
            <!-- T5 -->
            <rect x="292" y="115" width="36" height="85" rx="4" fill="#dbeafe" />
            <!-- T6 -->
            <rect x="372" y="85" width="36" height="115" rx="4" fill="#dbeafe" />
            <!-- T7 -->
            <rect x="452" y="125" width="36" height="75" rx="4" fill="#dbeafe" />
            <!-- CN -->
            <rect x="532" y="90" width="36" height="110" rx="4" fill="#dbeafe" />

            <!-- Line Chart Overlay -->
            <path d="M 70 130 L 150 100 L 230 60 L 310 115 L 390 95 L 470 75 L 550 90" fill="none" stroke="#0056c6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            
            <!-- Area under the line chart -->
            <path d="M 70 130 L 150 100 L 230 60 L 310 115 L 390 95 L 470 75 L 550 90 L 550 200 L 70 200 Z" fill="url(#chart-gradient)" opacity="0.08" />
            
            <defs>
              <linearGradient id="chart-gradient" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#0258cb" />
                <stop offset="100%" stop-color="#0258cb" stop-opacity="0" />
              </linearGradient>
            </defs>

            <!-- X-Axis Labels in SVG -->
            <text x="70" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">T2</text>
            <text x="150" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">T3</text>
            <text x="230" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-800">T4</text>
            <text x="310" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">T5</text>
            <text x="390" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">T6</text>
            <text x="470" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">T7</text>
            <text x="550" y="215" text-anchor="middle" class="text-[11px] font-bold fill-slate-400">CN</text>
          </svg>
        </div>
      </div>

      <!-- Share of Categories Chart -->
      <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm flex flex-col justify-between">
        <h3 class="text-base font-bold text-slate-900 mb-6">Thị phần ngành hàng</h3>

        <div class="relative w-44 h-44 mx-auto flex items-center justify-center my-2">
          <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
            <!-- Background circle -->
            <circle cx="100" cy="100" r="70" stroke="#f1f5f9" stroke-width="16" fill="transparent" />
            <!-- Điện tử 45% (Blue) -->
            <circle cx="100" cy="100" r="70" stroke="#0258cb" stroke-width="16" fill="transparent" stroke-dasharray="197.92 439.8" stroke-dashoffset="0" stroke-linecap="round" />
            <!-- Gia dụng 25% (Green) -->
            <circle cx="100" cy="100" r="70" stroke="#10b981" stroke-width="16" fill="transparent" stroke-dasharray="109.95 439.8" stroke-dashoffset="-197.92" stroke-linecap="round" />
            <!-- Thời trang 20% (Brown) -->
            <circle cx="100" cy="100" r="70" stroke="#b45309" stroke-width="16" fill="transparent" stroke-dasharray="87.96 439.8" stroke-dashoffset="-307.87" stroke-linecap="round" />
            <!-- Khác 10% (Gray) -->
            <circle cx="100" cy="100" r="70" stroke="#94a3b8" stroke-width="16" fill="transparent" stroke-dasharray="43.98 439.8" stroke-dashoffset="-395.83" stroke-linecap="round" />
          </svg>
          <div class="absolute flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-extrabold text-slate-900">100%</span>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tổng thể</span>
          </div>
        </div>

        <!-- Legend -->
        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-xs font-semibold text-slate-600 mt-6">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#0258cb] shrink-0"></span>
            <span class="truncate">Điện tử (45%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#10b981] shrink-0"></span>
            <span class="truncate">Gia dụng (25%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#b45309] shrink-0"></span>
            <span class="truncate">Thời trang (20%)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#94a3b8] shrink-0"></span>
            <span class="truncate">Khác (10%)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white border border-[#eef2f7] rounded-xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-5">
        <h3 class="text-base font-bold text-slate-900">Đơn hàng gần đây</h3>
        <a href="#" class="text-[#0258cb] text-sm font-bold hover:underline">Xem tất cả</a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
          <thead>
            <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
              <th class="py-3 px-4">Mã đơn hàng</th>
              <th class="py-3 px-4">Khách hàng</th>
              <th class="py-3 px-4">Ngày đặt</th>
              <th class="py-3 px-4">Tổng tiền</th>
              <th class="py-3 px-4">Trạng thái</th>
              <th class="py-3 px-4 text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50/50 transition-colors">
              <td class="py-4 px-4 text-[#0258cb] font-semibold">{{ order.code }}</td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div :class="`w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-extrabold ${order.avatarBg} text-white`">
                    {{ order.avatar }}
                  </div>
                  <span class="text-slate-800 font-semibold">{{ order.customerName }}</span>
                </div>
              </td>
              <td class="py-4 px-4 text-slate-400 font-normal">
                {{ order.date }}
              </td>
              <td class="py-4 px-4 text-slate-800 font-bold">{{ order.total }}</td>
              <td class="py-4 px-4">
                <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${order.statusClass}`">
                  {{ order.status }}
                </span>
              </td>
              <td class="py-4 px-4 text-center">
                <button class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-[#0258cb] hover:bg-slate-50 transition-all cursor-pointer" title="Xem chi tiết">
                  <svg class="w-4.5 h-4.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Table Footer / Pagination -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-slate-100">
        <span class="text-xs font-semibold text-slate-400">Hiển thị 4 trên 158 đơn hàng</span>
        <div class="flex items-center gap-1.5">
          <button class="w-8 h-8 rounded border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6" />
            </svg>
          </button>
          <button class="w-8 h-8 rounded bg-[#0258cb] text-white flex items-center justify-center font-bold text-xs cursor-pointer">
            1
          </button>
          <button class="w-8 h-8 rounded border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600 flex items-center justify-center font-semibold text-xs cursor-pointer">
            2
          </button>
          <button class="w-8 h-8 rounded border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600 flex items-center justify-center font-semibold text-xs cursor-pointer">
            3
          </button>
          <button class="w-8 h-8 rounded border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Custom Vietnamese current date formatted
const d = new Date()
const day = String(d.getDate()).padStart(2, '0')
const month = String(d.getMonth() + 1).padStart(2, '0')
const year = d.getFullYear()
const currentDate = `${day}/${month}/${year}`

const recentOrders = ref([
  {
    id: 1,
    code: '#ORD-2023-881',
    avatar: 'NT',
    avatarBg: 'bg-blue-500',
    customerName: 'Nguyễn Thành Trung',
    date: '15/10/2023, 14:30',
    total: '2.450.000đ',
    status: 'Đã thanh toán',
    statusClass: 'bg-emerald-50 text-emerald-600'
  },
  {
    id: 2,
    code: '#ORD-2023-882',
    avatar: 'LH',
    avatarBg: 'bg-orange-500',
    customerName: 'Lê Hoàng Nam',
    date: '15/10/2023, 15:12',
    total: '12.200.000đ',
    status: 'Chờ xác nhận',
    statusClass: 'bg-amber-50 text-amber-600'
  },
  {
    id: 3,
    code: '#ORD-2023-883',
    avatar: 'PT',
    avatarBg: 'bg-indigo-500',
    customerName: 'Phạm Tuyết Nhung',
    date: '15/10/2023, 16:45',
    total: '850.000đ',
    status: 'Hoàn thành',
    statusClass: 'bg-emerald-50 text-emerald-600'
  },
  {
    id: 4,
    code: '#ORD-2023-884',
    avatar: 'VD',
    avatarBg: 'bg-rose-500',
    customerName: 'Vũ Đức Mạnh',
    date: '15/10/2023, 17:20',
    total: '4.200.000đ',
    status: 'Đã hủy',
    statusClass: 'bg-rose-50 text-rose-600'
  }
])
</script>
