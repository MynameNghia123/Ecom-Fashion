<template>
  <div class="space-y-6">

    <!-- ═══════════════════════════════════════════════════════════
         PAGE HEADER
    ════════════════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Đơn hàng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Theo dõi, xử lý và cập nhật trạng thái đơn hàng trong hệ thống</p>
      </div>
      <button
        id="btn-open-create-order"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        Tạo đơn hàng mới
      </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         STATS CARDS
    ════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

      <!-- Tổng đơn hàng -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tổng đơn hàng</p>
          <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-slate-800">1,284</p>
        <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          +12% so với tháng trước
        </p>
      </div>

      <!-- Chờ xác nhận -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Chờ xác nhận</p>
          <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-slate-800">45</p>
        <p class="text-xs text-red-500 font-semibold mt-1 flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
          -2% so với tháng trước
        </p>
      </div>

      <!-- Đang xử lý -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Đang xử lý</p>
          <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-slate-800">128</p>
        <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          +5% so với tháng trước
        </p>
      </div>

      <!-- Hoàn thành -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Hoàn thành</p>
          <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-slate-800">1,092</p>
        <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          +18% so với tháng trước
        </p>
      </div>

      <!-- Đã hủy -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Đã hủy</p>
          <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-slate-800">19</p>
        <p class="text-xs text-slate-400 font-semibold mt-1">0% thay đổi</p>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         TABLE CARD
    ════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">

        <!-- Search -->
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-xs">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            type="text"
            placeholder="Tìm theo mã đơn, khách hàng..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>

        <!-- Filter: Trạng thái -->
        <div class="relative">
          <select class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer">
            <option value="">Trạng thái</option>
            <option value="pending">Chờ xác nhận</option>
            <option value="processing">Đang xử lý</option>
            <option value="shipping">Đang giao</option>
            <option value="completed">Hoàn thành</option>
            <option value="cancelled">Đã hủy</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>

        <!-- Filter: Thanh toán -->
        <div class="relative">
          <select class="appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 cursor-pointer">
            <option value="">Thanh toán</option>
            <option value="paid">Đã thanh toán</option>
            <option value="unpaid">Chưa thanh toán</option>
            <option value="refunded">Đã hoàn tiền</option>
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>

        <!-- Date picker -->
        <input
          type="date"
          class="px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        />

        <!-- Advanced filter -->
        <button class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 hover:bg-white hover:border-slate-300 transition-all duration-150 font-medium">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
          </svg>
          Lọc nâng cao
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Mã đơn hàng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khách hàng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[120px]">Ngày tạo</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Tổng tiền</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[130px]">Thanh toán</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Thao tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">

            <!-- Row mẫu: Đang giao + Đã thanh toán -->
            <tr class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-5">
                <span class="font-bold text-[#0258cb] hover:underline cursor-pointer">#ORD-1024</span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">HN</div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">Hoàng Nam</p>
                    <p class="text-xs text-slate-400">nam.h@gmail.com</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-slate-500">24 Th10, 2023</td>
              <td class="py-4 px-4 text-right font-semibold text-slate-800">2,450,000đ</td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  Đang giao
                </span>
              </td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  Đã thanh toán
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all duration-150" title="In hóa đơn">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Row mẫu: Chờ xác nhận + Chưa thanh toán -->
            <tr class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-5">
                <span class="font-bold text-[#0258cb] hover:underline cursor-pointer">#ORD-1023</span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shrink-0">LA</div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">Lan Anh</p>
                    <p class="text-xs text-slate-400">lananh@email.com</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-slate-500">24 Th10, 2023</td>
              <td class="py-4 px-4 text-right font-semibold text-slate-800">890,000đ</td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                  Chờ xác nhận
                </span>
              </td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Chưa thanh toán
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all duration-150" title="In hóa đơn">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Row mẫu: Hoàn thành + Đã thanh toán -->
            <tr class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-5">
                <span class="font-bold text-[#0258cb] hover:underline cursor-pointer">#ORD-1022</span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-bold shrink-0">MT</div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">Minh Tuấn</p>
                    <p class="text-xs text-slate-400">mtuan@gmail.com</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-slate-500">23 Th10, 2023</td>
              <td class="py-4 px-4 text-right font-semibold text-slate-800">5,200,000đ</td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Hoàn thành
                </span>
              </td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  Đã thanh toán
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all duration-150" title="In hóa đơn">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Row mẫu: Đã hủy -->
            <tr class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-5">
                <span class="font-bold text-[#0258cb] hover:underline cursor-pointer">#ORD-1021</span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white text-xs font-bold shrink-0">TL</div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">Thu Linh</p>
                    <p class="text-xs text-slate-400">thulinh@mail.vn</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-slate-500">23 Th10, 2023</td>
              <td class="py-4 px-4 text-right font-semibold text-slate-500 line-through">1,100,000đ</td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                  Đã hủy
                </span>
              </td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                  —
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-200 cursor-not-allowed" title="Không thể chỉnh sửa đơn đã hủy" disabled>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all duration-150" title="In hóa đơn">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                  </button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100">
        <p class="text-sm text-slate-500">Hiển thị 1–10 trong tổng số 1,284 đơn hàng</p>
        <div class="flex items-center gap-1">
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold bg-[#0258cb] text-white shadow-sm">1</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition-colors">2</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition-colors">3</button>
          <span class="w-8 h-8 flex items-center justify-center text-slate-400 text-sm">...</span>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition-colors">128</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         MODAL: CHI TIẾT ĐƠN HÀNG (View)
    ════════════════════════════════════════════════════════════════ -->
    <div class="fixed inset-0 z-[9998] flex items-start justify-center p-4 pt-16 hidden">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[85vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <h2 class="text-base font-bold text-slate-800">Chi tiết đơn hàng</h2>
            <span class="text-sm text-slate-500 font-mono">#ORD-99281-BFD</span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              Đã hoàn thành
            </span>
          </div>
          <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-7 py-5 overflow-y-auto space-y-5">

          <!-- 3 info cards -->
          <div class="grid grid-cols-3 gap-4">
            <!-- Thông tin khách hàng -->
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-[#0258cb] flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Thông tin khách hàng
              </p>
              <p class="text-sm font-semibold text-slate-800">Nguyễn Văn A</p>
              <p class="text-sm text-slate-500 mt-0.5">vana.nguyen@email.com</p>
            </div>
            <!-- Thông tin giao hàng -->
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-[#0258cb] flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Thông tin giao hàng
              </p>
              <p class="text-sm font-semibold text-slate-800">Nguyễn Văn A</p>
              <p class="text-sm text-slate-500 mt-0.5">(+84) 987 654 321</p>
              <p class="text-sm text-slate-500 mt-0.5">123 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh</p>
            </div>
            <!-- Lịch sử đơn hàng -->
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-[#0258cb] flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Lịch sử đơn hàng
              </p>
              <div class="space-y-2">
                <div class="flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 mt-1"></span>
                  <div><p class="text-xs font-semibold text-slate-700">Đã thanh toán</p><p class="text-xs text-slate-400">15:30 - 24/05/2024</p></div>
                </div>
                <div class="flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1"></span>
                  <div><p class="text-xs font-semibold text-slate-700">Đã tạo đơn hàng</p><p class="text-xs text-slate-400">14:15 - 24/05/2024</p></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Products -->
          <div>
            <p class="text-sm font-bold text-slate-800 mb-3">Sản phẩm đã chọn</p>
            <table class="w-full text-sm border border-slate-100 rounded-xl overflow-hidden">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                  <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm</th>
                  <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                  <th class="py-3 px-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Số lượng</th>
                  <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đơn giá</th>
                  <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng cộng</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/40x40/e2e8f0/94a3b8?text=SP" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800">Giày Chạy Bộ Performance X</p>
                        <p class="text-xs text-slate-400">Size: 42, Color: Red</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-xs text-slate-500">SKU-9920-RED</td>
                  <td class="py-3.5 px-4 text-center font-semibold text-slate-700">1</td>
                  <td class="py-3.5 px-4 text-right text-slate-700">2,500,000đ</td>
                  <td class="py-3.5 px-4 text-right font-semibold text-slate-800">2,500,000đ</td>
                </tr>
                <tr>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/40x40/e2e8f0/94a3b8?text=SW" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800">Smartwatch Elite S3</p>
                        <p class="text-xs text-slate-400">Color: Silver</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-xs text-slate-500">SKU-SW-8812</td>
                  <td class="py-3.5 px-4 text-center font-semibold text-slate-700">2</td>
                  <td class="py-3.5 px-4 text-right text-slate-700">1,200,000đ</td>
                  <td class="py-3.5 px-4 text-right font-semibold text-slate-800">2,400,000đ</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Summary + Note -->
          <div class="grid grid-cols-2 gap-4">
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ghi chú từ khách hàng</p>
              <p class="text-sm text-slate-600 italic">"Vui lòng giao vào giờ hành chính, gọi điện trước khi đến 15 phút. Cảm ơn shop!"</p>
            </div>
            <div class="border border-slate-100 rounded-xl p-4 space-y-2">
              <div class="flex justify-between text-sm text-slate-600"><span>Tạm tính:</span><span>4,900,000đ</span></div>
              <div class="flex justify-between text-sm text-red-500 font-semibold"><span>Giảm giá (COUPON500):</span><span>- 500,000đ</span></div>
              <div class="flex justify-between text-sm text-slate-600"><span>Phí vận chuyển:</span><span>30,000đ</span></div>
              <div class="border-t border-slate-100 pt-2 flex justify-between font-bold text-slate-800"><span class="text-base">Tổng thanh toán:</span><span class="text-xl text-[#0258cb]">4,430,000đ</span></div>
              <p class="text-xs text-slate-400 text-right flex items-center justify-end gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Đã thanh toán qua VNPay
              </p>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
          <button class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
          <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            In hóa đơn
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         MODAL: CHỈNH SỬA ĐƠN HÀNG (Edit)
    ════════════════════════════════════════════════════════════════ -->
    <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4 hidden">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[800px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
          <div>
            <h2 class="text-base font-bold text-slate-800">Chỉnh sửa đơn hàng</h2>
            <p class="text-xs text-slate-400 mt-0.5">#ORD-1024 · Ngày tạo: 12/10/2023 14:30</p>
          </div>
          <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body: 2 columns -->
        <div class="flex gap-0 overflow-y-auto flex-1">

          <!-- Left: Status + Shipping -->
          <div class="w-[220px] shrink-0 border-r border-slate-100 p-5 space-y-5">
            <!-- Trạng thái -->
            <div>
              <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Trạng thái
              </p>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Trạng thái đơn hàng</label>
                  <div class="relative">
                    <select class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:outline-none focus:border-[#0258cb] cursor-pointer pr-8">
                      <option>Đang xử lý</option>
                      <option>Đang giao</option>
                      <option>Hoàn thành</option>
                      <option>Đã hủy</option>
                    </select>
                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Trạng thái thanh toán</label>
                  <div class="relative">
                    <select class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:outline-none focus:border-[#0258cb] cursor-pointer pr-8">
                      <option>Đã thanh toán</option>
                      <option>Chưa thanh toán</option>
                    </select>
                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Giao hàng -->
            <div>
              <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Giao hàng
              </p>
              <div class="space-y-2.5">
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Người nhận</label>
                  <input type="text" value="Nguyễn Văn A" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Số điện thoại</label>
                  <input type="text" value="0901234567" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Địa chỉ</label>
                  <textarea rows="3" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] resize-none">123 Đường ABC, Phường 4, Quận Tân Bình, TP. Hồ Chí Minh</textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Products -->
          <div class="flex-1 p-5 space-y-4">
            <div class="flex items-center justify-between">
              <p class="text-sm font-bold text-slate-800">Sản phẩm trong đơn</p>
              <button class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0258cb] hover:underline">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Thêm sản phẩm
              </button>
            </div>

            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-slate-100">
                  <th class="pb-2.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm</th>
                  <th class="pb-2.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đơn giá</th>
                  <th class="pb-2.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-[90px]">Số lượng</th>
                  <th class="pb-2.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng</th>
                  <th class="pb-2.5 w-8"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr>
                  <td class="py-3">
                    <div class="flex items-center gap-2.5">
                      <div class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/40x40/e2e8f0/94a3b8?text=P1" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800 text-xs">iPhone 15 Pro Max 256GB</p>
                        <p class="text-[11px] text-slate-400">SKU: APP-IP15-PM-256-BL</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 text-right text-slate-700">32,000,000đ</td>
                  <td class="py-3 text-center">
                    <input type="number" value="1" min="1" class="w-16 text-center px-2 py-1.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-[#0258cb]">
                  </td>
                  <td class="py-3 text-right font-semibold text-slate-800">32,000,000đ</td>
                  <td class="py-3 text-center">
                    <button class="p-1 rounded text-red-400 hover:bg-red-50 transition-colors">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td class="py-3">
                    <div class="flex items-center gap-2.5">
                      <div class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/40x40/e2e8f0/94a3b8?text=P2" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800 text-xs">Ốp iPhone MagSafe Silicone</p>
                        <p class="text-[11px] text-slate-400">SKU: ACC-CSF-SIL-NAV</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 text-right text-slate-700">1,200,000đ</td>
                  <td class="py-3 text-center">
                    <input type="number" value="2" min="1" class="w-16 text-center px-2 py-1.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-[#0258cb]">
                  </td>
                  <td class="py-3 text-right font-semibold text-slate-800">2,400,000đ</td>
                  <td class="py-3 text-center">
                    <button class="p-1 rounded text-red-400 hover:bg-red-50 transition-colors">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Summary -->
            <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
              <div class="flex justify-between text-slate-600"><span>Tạm tính:</span><span>34,400,000đ</span></div>
              <div class="flex justify-between text-slate-600"><span>Phí vận chuyển:</span><span>30,000đ</span></div>
              <div class="flex justify-between text-emerald-600 font-semibold"><span>Khuyến mãi (SALE10):</span><span>-3,440,000đ</span></div>
              <div class="flex justify-between font-bold text-slate-800 text-base pt-1 border-t border-slate-100"><span>Tổng cộng:</span><span class="text-[#0258cb] text-lg">30,990,000đ</span></div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-7 py-5 border-t border-slate-100">
          <button class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-500 hover:text-red-600 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            Hủy đơn hàng
          </button>
          <div class="flex gap-3">
            <button class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
            <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Cập nhật đơn hàng
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         MODAL: TẠO ĐƠN HÀNG MỚI (Create)
    ════════════════════════════════════════════════════════════════ -->
    <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4 hidden">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[900px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
              <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div>
              <h2 class="text-base font-bold text-slate-800">Tạo đơn hàng mới</h2>
              <p class="text-xs text-slate-400">Nhập thông tin khách hàng và danh sách sản phẩm</p>
            </div>
          </div>
          <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body: 2 columns -->
        <div class="grid grid-cols-[280px_1fr] gap-0 overflow-y-auto flex-1">

          <!-- Left: Customer + Shipping -->
          <div class="border-r border-slate-100 p-6 space-y-5">
            <!-- Chọn khách hàng -->
            <div>
              <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Chọn khách hàng</p>
              <!-- Search customer -->
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input type="text" placeholder="Tìm tên, SĐT khách hàng..." class="w-full pl-8 pr-3.5 py-2 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all">
              </div>
              <!-- Selected customer -->
              <div class="mt-3 p-3 bg-blue-50/50 border border-blue-100 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">NL</div>
                  <div>
                    <p class="text-sm font-semibold text-slate-800">Nguyen Lan Anh</p>
                    <p class="text-xs text-slate-500">0901234567</p>
                  </div>
                </div>
                <button class="text-xs font-semibold text-[#0258cb] hover:underline">Thay đổi</button>
              </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div>
              <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Thông tin giao hàng</p>
              <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2.5">
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Tên người nhận</label>
                    <input type="text" value="Nguyên Lan Anh" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                  </div>
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Số điện thoại</label>
                    <input type="text" value="0901234567" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Tỉnh/Thành phố</label>
                    <div class="relative">
                      <select class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] cursor-pointer pr-7">
                        <option>TP. HCM</option>
                        <option>Hà Nội</option>
                      </select>
                      <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Quận/Huyện</label>
                    <div class="relative">
                      <select class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] cursor-pointer pr-7">
                        <option>Quận 1</option>
                        <option>Quận 3</option>
                      </select>
                      <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                  </div>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Phường/Xã</label>
                  <input type="text" value="Phường Bến Thành" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Địa chỉ chi tiết</label>
                  <input type="text" value="123 Đường Lê Lợi" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Products -->
          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
              <p class="text-sm font-bold text-slate-800">Danh sách sản phẩm (OrderDetail)</p>
              <!-- Search product -->
              <div class="relative w-52">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input type="text" placeholder="Tìm sản phẩm, SKU..." class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all">
                <!-- Smart search dropdown (sample) -->
                <div class="absolute top-full left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-10 overflow-hidden hidden">
                  <div class="p-1.5 space-y-0.5">
                    <div class="flex items-start gap-3 px-3 py-2.5 hover:bg-slate-50 rounded-lg cursor-pointer">
                      <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Nike Air Zoom Pegasus - Đỏ, 42</p>
                        <p class="text-xs text-slate-400">SKU: NKE-P38-001</p>
                      </div>
                      <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-slate-800">1,200,000đ</p>
                        <p class="text-xs text-emerald-600 font-semibold">Tồn: 45</p>
                      </div>
                    </div>
                    <div class="flex items-start gap-3 px-3 py-2.5 hover:bg-slate-50 rounded-lg cursor-pointer">
                      <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Nike Air Force 1 - Trắng, 40</p>
                        <p class="text-xs text-slate-400">SKU: NKE-AF1-002</p>
                      </div>
                      <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-slate-800">2,500,000đ</p>
                        <p class="text-xs text-emerald-600 font-semibold">Tồn: 12</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Product table -->
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-slate-100">
                  <th class="pb-2.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm / Biến thể</th>
                  <th class="pb-2.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đơn giá</th>
                  <th class="pb-2.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Tồn kho</th>
                  <th class="pb-2.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-[100px]">Số lượng</th>
                  <th class="pb-2.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Thành tiền</th>
                  <th class="pb-2.5 w-8"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr>
                  <td class="py-3">
                    <div class="flex items-center gap-2.5">
                      <div class="w-9 h-9 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/36x36/e2e8f0/94a3b8?text=NK" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800 text-xs">Nike Air Zoom Pegasus</p>
                        <p class="text-[11px] text-slate-400">Red, Size 42 · NKE-P38-001</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 text-right text-slate-700">1,200,000<br><span class="text-xs text-slate-400">đ</span></td>
                  <td class="py-3 text-center text-sm text-slate-600">45</td>
                  <td class="py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <button class="w-6 h-6 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold">−</button>
                      <input type="number" value="1" min="1" class="w-10 text-center py-0.5 text-sm border border-slate-200 rounded-md focus:outline-none">
                      <button class="w-6 h-6 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold">+</button>
                    </div>
                  </td>
                  <td class="py-3 text-right font-semibold text-slate-800">1,200,000<br><span class="text-xs text-slate-400">đ</span></td>
                  <td class="py-3 text-center">
                    <button class="p-1 rounded text-red-400 hover:bg-red-50 transition-colors">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td class="py-3">
                    <div class="flex items-center gap-2.5">
                      <div class="w-9 h-9 rounded-lg bg-slate-100 shrink-0 overflow-hidden">
                        <img src="https://placehold.co/36x36/e2e8f0/94a3b8?text=MW" alt="" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800 text-xs">Minimalist Smart Watch</p>
                        <p class="text-[11px] text-slate-400">White · WTC-GEN2-WHT</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 text-right text-slate-700">3,500,000<br><span class="text-xs text-slate-400">đ</span></td>
                  <td class="py-3 text-center text-sm text-slate-600">2</td>
                  <td class="py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <button class="w-6 h-6 rounded-md border border-red-300 text-red-500 hover:bg-red-50 flex items-center justify-center text-sm font-bold">−</button>
                      <input type="number" value="3" min="1" class="w-10 text-center py-0.5 text-sm border border-red-300 rounded-md focus:outline-none text-red-600">
                      <button class="w-6 h-6 rounded-md border border-red-300 text-red-500 hover:bg-red-50 flex items-center justify-center text-sm font-bold">+</button>
                    </div>
                    <p class="text-[10px] text-red-500 text-center mt-0.5">Vượt tồn kho</p>
                  </td>
                  <td class="py-3 text-right font-semibold text-slate-800">10,500,000<br><span class="text-xs text-slate-400">đ</span></td>
                  <td class="py-3 text-center">
                    <button class="p-1 rounded text-red-400 hover:bg-red-50 transition-colors">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Coupon + Summary -->
            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
              <!-- Coupon -->
              <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mã giảm giá (Coupon)</p>
                <div class="flex gap-2">
                  <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </span>
                    <input type="text" placeholder="Nhập mã coupon..." class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:outline-none transition-all">
                  </div>
                  <button class="px-3.5 py-2 text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">Áp dụng</button>
                </div>
                <div class="mt-2">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg">
                    SAVE20 (-20%) 
                    <button class="hover:text-red-500 transition-colors">×</button>
                  </span>
                </div>
              </div>
              <!-- Summary -->
              <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-slate-600"><span>Tạm tính (Subtotal):</span><span>11,700,000 đ</span></div>
                <div class="flex justify-between text-red-500 font-semibold"><span>Chiết khấu (Discount):</span><span>-1,640,000 đ</span></div>
                <div class="flex justify-between text-slate-600"><span>Phí vận chuyển:</span><span>30,000 đ</span></div>
                <div class="flex justify-between font-bold text-slate-800 text-base pt-2 border-t border-slate-100"><span>Tổng cộng:</span><span class="text-[#0258cb]">10,090,000 đ</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
          <button class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Hủy</button>
          <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98] shadow-md shadow-blue-200">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Xác nhận & Lưu đơn hàng
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
