<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Đơn hàng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Theo dõi trạng thái, phương thức thanh toán và xử lý các đơn đặt hàng</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-4">
      <!-- Total Revenue -->
      <div class="col-span-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-5 flex flex-col justify-between text-white shadow-md shadow-blue-100">
        <div>
          <p class="text-xs font-semibold text-white/80 uppercase tracking-wider mb-1">Tổng Doanh thu (Không hủy)</p>
          <p class="text-2xl font-bold tracking-tight">{{ formatPrice(orderStore.stats.total_revenue) }}</p>
        </div>
        <div class="flex items-center gap-1.5 text-xs text-white/90 mt-2 font-medium">
          <span>Xem chi tiết doanh thu</span>
        </div>
      </div>

      <!-- Stats Cards for statuses -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Chờ xử lý</p>
          <p class="text-3xl font-bold text-slate-800">{{ orderStore.stats.pending }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
          <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã xác nhận</p>
          <p class="text-3xl font-bold text-slate-800">{{ orderStore.stats.confirmed }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
          <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang giao</p>
          <p class="text-3xl font-bold text-slate-800">{{ orderStore.stats.shipping }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
          <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã giao</p>
          <p class="text-3xl font-bold text-slate-800">{{ orderStore.stats.completed }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
          <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đã hủy</p>
          <p class="text-3xl font-bold text-slate-800">{{ orderStore.stats.cancelled }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
          <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
      </div>
    </div>

    <!-- Error Alert -->
    <div
      v-if="orderStore.error"
      class="flex items-center gap-3 px-5 py-3.5 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700 shadow-sm"
    >
      <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ orderStore.error }}
    </div>

    <!-- Table & Filters Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <!-- Toolbar / Filters -->
      <div class="flex flex-wrap items-center justify-between gap-4 p-5 border-b border-slate-100 bg-slate-50/50">
        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
          <!-- Search input -->
          <div class="relative flex items-center w-full max-w-xs">
            <span class="absolute left-3 text-slate-400">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input
              v-model="searchQuery"
              @input="onSearch"
              type="text"
              placeholder="Tìm mã đơn, tên, SĐT khách..."
              class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-white placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:outline-none transition-all"
            />
          </div>

          <!-- Status filter -->
          <select
            v-model="filterStatus"
            @change="onFilterChange"
            class="py-2.5 px-4 text-sm border border-slate-200 rounded-xl text-slate-600 bg-white focus:border-blue-600 focus:outline-none transition-all"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="pending">Chờ xử lý</option>
            <option value="confirmed">Đã xác nhận</option>
            <option value="shipping">Đang giao hàng</option>
            <option value="completed">Đã giao hàng</option>
            <option value="cancelled">Đã hủy</option>
          </select>

          <!-- Payment Status filter -->
          <select
            v-model="filterPaymentStatus"
            @change="onFilterChange"
            class="py-2.5 px-4 text-sm border border-slate-200 rounded-xl text-slate-600 bg-white focus:border-blue-600 focus:outline-none transition-all"
          >
            <option value="">Tất cả thanh toán</option>
            <option value="unpaid">Chưa thanh toán</option>
            <option value="paid">Đã thanh toán</option>
            <option value="refunded">Đã hoàn tiền</option>
          </select>

          <!-- Payment Method filter -->
          <select
            v-model="filterPaymentMethod"
            @change="onFilterChange"
            class="py-2.5 px-4 text-sm border border-slate-200 rounded-xl text-slate-600 bg-white focus:border-blue-600 focus:outline-none transition-all"
          >
            <option value="">Tất cả phương thức</option>
            <option value="cod">Thanh toán COD</option>
            <option value="vnpay">Thanh toán VNPAY</option>
          </select>
        </div>

        <button 
          @click="resetFilters" 
          class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors py-2 px-3 border border-slate-200 rounded-xl bg-white hover:bg-slate-50"
        >
          Đặt lại bộ lọc
        </button>
      </div>

      <!-- Table View -->
      <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 text-xs font-bold uppercase tracking-wider">
              <th class="py-4 px-5">Mã đơn hàng</th>
              <th class="py-4 px-5">Khách hàng</th>
              <th class="py-4 px-5 text-right">Tổng tiền</th>
              <th class="py-4 px-5">Phương thức</th>
              <th class="py-4 px-5">Trạng thái đơn</th>
              <th class="py-4 px-5">Thanh toán</th>
              <th class="py-4 px-5">Ngày tạo</th>
              <th class="py-4 px-5 text-center">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
            <!-- Loading state -->
            <tr v-if="orderStore.loading && orderStore.orders.length === 0">
              <td colspan="8" class="py-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center gap-2.5">
                  <svg class="w-8 h-8 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                  <span>Đang tải danh sách đơn hàng...</span>
                </div>
              </td>
            </tr>

            <!-- Empty state -->
            <tr v-else-if="orderStore.orders.length === 0">
              <td colspan="8" class="py-16 text-center">
                <div class="flex flex-col items-center justify-center max-w-sm mx-auto text-slate-400">
                  <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-slate-350" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  </div>
                  <h3 class="font-bold text-slate-800 text-sm">Không tìm thấy đơn hàng</h3>
                  <p class="text-xs mt-1 text-slate-400 leading-relaxed">Hãy thử kiểm tra từ khóa hoặc đặt lại các bộ lọc hiện tại.</p>
                </div>
              </td>
            </tr>

            <!-- Table Rows -->
            <tr 
              v-else 
              v-for="order in orderStore.orders" 
              :key="order.id"
              class="hover:bg-slate-50/50 transition-colors"
            >
              <td class="py-4 px-5">
                <div class="flex items-center gap-1.5">
                  <span class="font-bold text-slate-900 font-mono tracking-tight">{{ order.order_code }}</span>
                  <button 
                    @click="copyToClipboard(order.order_code)"
                    class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                    title="Copy mã đơn"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                  </button>
                </div>
              </td>
              <td class="py-4 px-5">
                <div>
                  <p class="font-semibold text-slate-800">{{ order.shipping_name }}</p>
                  <p class="text-xs text-slate-400 mt-0.5">{{ order.shipping_phone }}</p>
                </div>
              </td>
              <td class="py-4 px-5 text-right font-bold text-slate-800 font-mono">
                {{ formatPrice(order.final_amount) }}
              </td>
              <td class="py-4 px-5 text-xs font-semibold">
                <span 
                  class="px-2.5 py-1 rounded-full uppercase"
                  :class="order.payment_method === 'vnpay' ? 'bg-[#e6f4ff] text-[#0958d9]' : 'bg-[#f0f5ff] text-[#1d39c4]'"
                >
                  {{ order.payment_method === 'cod' ? 'COD' : 'VNPAY' }}
                </span>
              </td>
              <td class="py-4 px-5">
                <span 
                  class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg border uppercase"
                  :class="getStatusClass(order.status)"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(order.status)"></span>
                  {{ getStatusText(order.status) }}
                </span>
              </td>
              <td class="py-4 px-5">
                <span 
                  class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg border uppercase"
                  :class="getPaymentStatusClass(order.payment_status)"
                >
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </td>
              <td class="py-4 px-5 text-xs text-slate-500 font-medium">
                {{ formatDate(order.created_at) }}
              </td>
              <td class="py-4 px-5 text-center">
                <button
                  @click="openDetail(order.id)"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-blue-600 hover:text-blue-600 text-slate-600 text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Chi tiết
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination component -->
      <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/20">
        <Pagination
          :currentPage="orderStore.meta.current_page"
          @update:currentPage="goToPage"
          :perPage="orderStore.meta.per_page"
          @update:perPage="handlePerPageChange"
          :total="orderStore.meta.total"
          :lastPage="orderStore.meta.last_page"
          :loading="orderStore.loading"
        />
      </div>
    </div>

    <!-- Copy feedback notification toast -->
    <Teleport to="body">
      <div 
        v-if="toastMessage" 
        class="fixed bottom-5 right-5 z-[10000] flex items-center gap-2.5 px-4.5 py-3 bg-slate-800 text-white rounded-xl shadow-xl animate-fade-in text-sm font-medium"
      >
        <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
        {{ toastMessage }}
      </div>
    </Teleport>

    <!-- ========== DETAILS & ACTION MODAL ========== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showDetailModal"
          class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-[1.5px]"
          @click.self="closeDetailModal"
        >
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col animate-modal-in">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                  <h2 class="text-lg font-extrabold text-slate-850">
                    Chi tiết đơn hàng <span class="font-mono text-blue-600">{{ activeOrder?.order_code }}</span>
                  </h2>
                  <span 
                    class="px-2 py-0.5 text-xs font-bold uppercase rounded border"
                    :class="getStatusClass(activeOrder?.status)"
                  >
                    {{ getStatusText(activeOrder?.status) }}
                  </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Đặt ngày {{ formatDate(activeOrder?.created_at) }}</p>
              </div>
              <button 
                @click="closeDetailModal" 
                class="p-2 rounded-xl text-slate-400 hover:text-slate-650 hover:bg-slate-100 transition-colors"
              >
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto grow space-y-6">
              <!-- Loading details -->
              <div v-if="loadingOrderDetails" class="py-16 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center gap-2.5">
                  <svg class="w-8 h-8 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                  <span>Đang tải thông tin chi tiết đơn hàng...</span>
                </div>
              </div>

              <!-- Details display -->
              <div v-else-if="activeOrder" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Customer Info & Shipping Address -->
                <div class="md:col-span-2 space-y-6">
                  <!-- Shipping & Payment info block -->
                  <div class="bg-slate-50/60 rounded-xl p-5 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <h3 class="text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Người nhận hàng</h3>
                      <p class="font-bold text-slate-800 text-sm">{{ activeOrder.shipping_name }}</p>
                      <p class="text-xs text-slate-650 mt-1">SĐT: {{ activeOrder.shipping_phone }}</p>
                      <p class="text-xs text-slate-650 mt-1.5 leading-relaxed">Địa chỉ: {{ activeOrder.shipping_address }}</p>
                    </div>
                    <div>
                      <h3 class="text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Thông tin tài khoản</h3>
                      <p class="font-semibold text-slate-800 text-sm">{{ activeOrder.customer?.full_name || 'Khách vãng lai' }}</p>
                      <p class="text-xs text-slate-650 mt-1">{{ activeOrder.customer?.email }}</p>
                      <p class="text-xs text-slate-650 mt-1">Tài khoản SĐT: {{ activeOrder.customer?.phone_number || 'N/A' }}</p>
                    </div>
                  </div>

                  <!-- Items Table -->
                  <div>
                    <h3 class="text-sm font-extrabold text-slate-800 mb-3 flex items-center gap-1.5">
                      Danh sách sản phẩm
                      <span class="px-2 py-0.5 text-xs bg-slate-100 text-slate-600 rounded-full font-bold">{{ activeOrder.details?.length || 0 }}</span>
                    </h3>
                    <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                      <table class="w-full text-left border-collapse">
                        <thead>
                          <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-100">
                            <th class="py-3 px-4">Sản phẩm</th>
                            <th class="py-3 px-4 text-center">Số lượng</th>
                            <th class="py-3 px-4 text-right">Đơn giá</th>
                            <th class="py-3 px-4 text-right">Thành tiền</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                          <tr v-for="item in activeOrder.details" :key="item.id" class="hover:bg-slate-50/30">
                            <td class="py-3 px-4">
                              <div class="flex items-center gap-3">
                                <img 
                                  :src="getImageUrl(item.product_variant?.thumbnail || item.product_variant?.product?.thumbnail)" 
                                  class="w-11 h-11 object-cover rounded-lg bg-slate-50 border border-slate-100 shrink-0" 
                                  alt="Product image"
                                />
                                <div>
                                  <p class="font-semibold text-slate-800 line-clamp-1 leading-snug">{{ item.product_variant?.product?.name }}</p>
                                  <p class="text-xs text-slate-400 font-mono mt-0.5">{{ item.product_variant?.sku }}</p>
                                  <!-- Attributes (Size / Color) -->
                                  <div class="flex gap-1.5 mt-1 flex-wrap">
                                    <span 
                                      v-for="attrVal in item.product_variant?.attribute_values" 
                                      :key="attrVal.id" 
                                      class="inline-block text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.2 rounded font-medium"
                                    >
                                      {{ attrVal.attribute?.name }}: {{ attrVal.value }}
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800 font-mono">{{ item.quantity }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-slate-700 font-mono">{{ formatPrice(item.unit_price) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-800 font-mono">{{ formatPrice(item.unit_price * item.quantity) }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- Action Form & Invoice Totals -->
                <div class="space-y-6">
                  <!-- Pricing Summary Card -->
                  <div class="bg-slate-50/50 rounded-xl p-5 border border-slate-100 space-y-3">
                    <h3 class="text-xs font-bold text-slate-450 uppercase tracking-wider border-b border-slate-200/60 pb-2">Chi tiết thanh toán</h3>
                    
                    <div class="flex justify-between text-xs text-slate-600">
                      <span>Tạm tính:</span>
                      <span class="font-mono font-medium">{{ formatPrice(activeOrder.sub_total_amount) }}</span>
                    </div>

                    <div v-if="activeOrder.coupon_discount_amount > 0" class="flex justify-between text-xs text-rose-600">
                      <span>Giảm giá (Coupon):</span>
                      <span class="font-mono font-medium">-{{ formatPrice(activeOrder.coupon_discount_amount) }}</span>
                    </div>

                    <div class="flex justify-between text-xs text-slate-600">
                      <span>Phí giao hàng:</span>
                      <span class="font-mono font-medium">{{ formatPrice(activeOrder.shipping_fee) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-sm text-slate-800 font-extrabold pt-2 border-t border-slate-200/60">
                      <span>Tổng thanh toán:</span>
                      <span class="text-blue-600 font-mono text-base">{{ formatPrice(activeOrder.final_amount) }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60 space-y-2">
                      <div class="flex justify-between text-xs text-slate-600">
                        <span>Phương thức:</span>
                        <span class="font-semibold">{{ activeOrder.payment_method === 'cod' ? 'Thanh toán COD' : 'Ví VNPAY' }}</span>
                      </div>
                      <div v-if="activeOrder.transaction_id" class="flex justify-between text-xs text-slate-600">
                        <span>Mã giao dịch:</span>
                        <span class="font-mono text-slate-500 font-medium truncate max-w-[120px]" :title="activeOrder.transaction_id">{{ activeOrder.transaction_id }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Edit Status Form -->
                  <div class="bg-white border border-slate-150 rounded-xl p-5 shadow-sm space-y-4.5">
                    <h3 class="text-sm font-extrabold text-slate-850 flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      Xử lý trạng thái
                    </h3>
                    
                    <form @submit.prevent="handleUpdateStatus" class="space-y-4">
                      <!-- Status Dropdown -->
                      <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Trạng thái đơn hàng</label>
                        <select
                          v-model="editForm.status"
                          class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-sm focus:border-blue-600 focus:outline-none transition-all"
                        >
                          <option value="pending">Chờ xử lý</option>
                          <option value="confirmed">Đã xác nhận</option>
                          <option value="shipping">Đang giao hàng</option>
                          <option value="completed">Đã giao hàng (Hoàn thành)</option>
                          <option value="cancelled">Đã hủy</option>
                        </select>
                      </div>

                      <!-- Payment Status Dropdown -->
                      <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Trạng thái thanh toán</label>
                        <select
                          v-model="editForm.payment_status"
                          class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-sm focus:border-blue-600 focus:outline-none transition-all"
                        >
                          <option value="unpaid">Chưa thanh toán</option>
                          <option value="paid">Đã thanh toán</option>
                          <option value="refunded">Đã hoàn tiền</option>
                        </select>
                      </div>

                      <!-- Save button -->
                      <button
                        type="submit"
                        :disabled="submittingStatusUpdate"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-blue-100 hover:shadow flex items-center justify-center gap-2 active:scale-[0.98]"
                      >
                        <svg v-if="submittingStatusUpdate" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <span>Cập nhật trạng thái</span>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end shrink-0">
              <button 
                @click="closeDetailModal" 
                class="px-5 py-2 border border-slate-200 rounded-xl text-slate-650 hover:bg-slate-100 hover:text-slate-800 text-sm font-semibold transition-colors"
              >
                Đóng
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useOrderStore } from '@/stores/admin/orderStore'
import Pagination from '@/components/admin/Pagination.vue'

const orderStore = useOrderStore()

// State
const searchQuery = ref('')
const filterStatus = ref('')
const filterPaymentStatus = ref('')
const filterPaymentMethod = ref('')

const showDetailModal = ref(false)
const activeOrder = ref(null)
const loadingOrderDetails = ref(false)
const submittingStatusUpdate = ref(false)

const toastMessage = ref('')

const editForm = ref({
  status: '',
  payment_status: ''
})

let searchTimer = null

// Polling interval
let pollInterval = null

// Load data
onMounted(() => {
  orderStore.fetchOrders()
  
  // Thiết lập tự động đồng bộ mỗi 10 giây để hiển thị đơn hàng mới khi khách hàng đặt mua (COD hoặc VNPAY)
  pollInterval = setInterval(() => {
    if (!orderStore.loading && !showDetailModal.value) {
      fetchFilteredOrders()
    }
  }, 10000)
})

onUnmounted(() => {
  if (pollInterval) {
    clearInterval(pollInterval)
  }
})

// Formatting helpers
const formatPrice = (value) => {
  if (value === null || value === undefined) return '0 đ'
  return Number(value).toLocaleString('vi-VN') + ' đ'
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  try {
    return new Date(dateStr).toLocaleString('vi-VN', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch {
    return dateStr
  }
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=200&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  const baseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  return `${baseUrl}/storage/${path}`
}

// Badge styling helpers
const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-amber-50 border-amber-250 text-amber-700',
    confirmed: 'bg-blue-50 border-blue-250 text-blue-700',
    shipping: 'bg-indigo-50 border-indigo-250 text-indigo-700',
    completed: 'bg-emerald-50 border-emerald-250 text-emerald-700',
    cancelled: 'bg-rose-50 border-rose-250 text-rose-700',
  }
  return classes[status] || 'bg-slate-50 border-slate-250 text-slate-700'
}

const getStatusDotClass = (status) => {
  const classes = {
    pending: 'bg-amber-500',
    confirmed: 'bg-blue-500',
    shipping: 'bg-indigo-500',
    completed: 'bg-emerald-500',
    cancelled: 'bg-rose-500',
  }
  return classes[status] || 'bg-slate-400'
}

const getStatusText = (status) => {
  const textMap = {
    pending: 'Chờ xử lý',
    confirmed: 'Đã xác nhận',
    shipping: 'Đang giao',
    completed: 'Đã giao',
    cancelled: 'Đã hủy',
  }
  return textMap[status] || status
}

const getPaymentStatusClass = (status) => {
  const classes = {
    unpaid: 'bg-rose-50 border-rose-200 text-rose-700',
    paid: 'bg-emerald-50 border-emerald-200 text-emerald-700',
    refunded: 'bg-slate-55 bg-slate-50 border-slate-200 text-slate-600',
  }
  return classes[status] || 'bg-slate-50 border-slate-200 text-slate-600'
}

const getPaymentStatusText = (status) => {
  const textMap = {
    unpaid: 'Chưa trả',
    paid: 'Đã trả',
    refunded: 'Đã hoàn tiền',
  }
  return textMap[status] || status
}

// Search and filters actions
const onSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    fetchFilteredOrders({ page: 1 })
  }, 400)
}

const onFilterChange = () => {
  fetchFilteredOrders({ page: 1 })
}

const resetFilters = () => {
  searchQuery.value = ''
  filterStatus.value = ''
  filterPaymentStatus.value = ''
  filterPaymentMethod.value = ''
  orderStore.fetchOrders({ page: 1 })
}

const fetchFilteredOrders = (extraParams = {}) => {
  orderStore.fetchOrders({
    search: searchQuery.value,
    status: filterStatus.value,
    payment_status: filterPaymentStatus.value,
    payment_method: filterPaymentMethod.value,
    ...extraParams
  })
}

// Pagination actions
const goToPage = (page) => {
  if (page < 1 || page > orderStore.meta.last_page) return
  fetchFilteredOrders({ page })
}

const handlePerPageChange = (newPerPage) => {
  orderStore.meta.per_page = newPerPage
  fetchFilteredOrders({ page: 1 })
}

// Toast utility
const triggerToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => {
    toastMessage.value = ''
  }, 2500)
}

const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text).then(() => {
    triggerToast(`Đã copy mã đơn: ${text}`)
  })
}

// Detail modal logic
const openDetail = async (orderId) => {
  showDetailModal.value = true
  loadingOrderDetails.value = true
  try {
    const detail = await orderStore.fetchOrderById(orderId)
    activeOrder.value = detail
    editForm.value.status = detail.status
    editForm.value.payment_status = detail.payment_status
  } catch (err) {
    triggerToast('Lỗi khi tải thông tin đơn hàng.')
    showDetailModal.value = false
  } finally {
    loadingOrderDetails.value = false
  }
}

const closeDetailModal = () => {
  showDetailModal.value = false
  activeOrder.value = null
}

const handleUpdateStatus = async () => {
  if (!activeOrder.value) return
  submittingStatusUpdate.value = true
  try {
    const updated = await orderStore.updateOrder(activeOrder.value.id, {
      status: editForm.value.status,
      payment_status: editForm.value.payment_status
    })
    activeOrder.value = updated.data
    triggerToast('Cập nhật trạng thái đơn hàng thành công!')
  } catch (err) {
    triggerToast(err.response?.data?.message || 'Lỗi khi cập nhật trạng thái đơn hàng.')
  } finally {
    submittingStatusUpdate.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.25s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
