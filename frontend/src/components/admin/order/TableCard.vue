<template>
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
            <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Mã đơn hàng</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider min-w-[200px]">Khách hàng</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Ngày tạo</th>
            <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Tổng tiền</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Trạng thái</th>
            <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Thanh toán</th>
            <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <template v-if="loading">
            <tr v-for="i in meta.per_page" :key="'sk-' + i">
              <td colspan="7" class="py-4 px-5">
                <div class="h-5 bg-slate-100 rounded-lg animate-pulse w-full"></div>
              </td>
            </tr>
          </template>
          <template v-else>
            <!-- Row dữ liệu thực tế -->
            <tr
              v-for="order in orders"
              :key="order.id"
              class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-5 whitespace-nowrap">
                <span class="font-bold text-[#0258cb] hover:underline cursor-pointer">{{ order.order_code }}</span>
              </td>
              <td class="py-4 px-4 min-w-[200px]">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ order.shipping_name ? order.shipping_name.charAt(0).toUpperCase() : 'KH' }}
                  </div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">{{ order.shipping_name }}</p>
                    <p class="text-xs text-slate-400">{{ order.shipping_phone }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-slate-500 whitespace-nowrap">{{ order.created_at }}</td>
              <td class="py-4 px-4 text-right font-semibold text-slate-800 whitespace-nowrap">
                {{ Number(order.final_amount).toLocaleString('vi-VN') }}đ
              </td>
              <td class="py-4 px-4">
                <span 
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="{
                    'bg-amber-100 text-amber-700': order.status === 'pending',
                    'bg-blue-100 text-blue-700': order.status === 'processing' || order.status === 'shipping',
                    'bg-emerald-100 text-emerald-700': order.status === 'completed',
                    'bg-red-100 text-red-600': order.status === 'cancelled'
                  }"
                >
                  <span class="w-1.5 h-1.5 rounded-full" 
                        :class="order.status === 'cancelled' ? 'bg-red-500' : 'bg-current'"></span>
                  {{ 
                    order.status === 'pending' ? 'Chờ xác nhận' : 
                    order.status === 'processing' ? 'Đang xử lý' : 
                    order.status === 'shipping' ? 'Đang giao' : 
                    order.status === 'completed' ? 'Hoàn thành' : 
                    order.status === 'cancelled' ? 'Đã hủy' : order.status 
                  }}
                </span>
              </td>
              <td class="py-4 px-4">
                <span 
                  class="inline-flex items-center gap-1.5 text-xs font-semibold"
                  :class="order.payment_status === 'paid' ? 'text-emerald-600' : 'text-slate-500'"
                >
                  <svg v-if="order.payment_status === 'paid'" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <svg v-else class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  {{ order.payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button
                    @click="showOrder(order)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button 
                    @click="editOrder(order)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all duration-150" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button
                    @click="printOrder(order)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all duration-150" title="In hóa đơn">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </template>

        </tbody>
      </table>
    </div>
    <!-- Pagination Footer -->
    <div class="px-5 py-3 border-t border-slate-100 bg-white">
      <Pagination
        :current-page="meta.current_page"
        :per-page="meta.per_page"
        :total="meta.total"
        :last-page="meta.last_page"
        :loading="loading"
        @update:current-page="handlePageChange"
        @update:per-page="handlePerPageChange"
      />

    </div>

  </div>
</template>
<script setup>
import Pagination from '@/components/admin/Pagination.vue';
import { defineEmits, defineProps } from 'vue';

const props = defineProps({
  orders: {
    type: Array,
    required: true
  },
  meta: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['change-page','set-action', 'change-per-page', 'set-selected-order']);

const showOrder = function(order){
  emit('set-selected-order', order);
  emit('set-action', 'view')
}

const editOrder = function(order){
  emit('set-selected-order', order);
    emit('set-action', 'edit')

}

const printOrder = function(order){
  emit('set-selected-order', order);
    emit('set-action', 'print')
}

const handlePageChange = (page) => {
  emit('change-page', page);
};

const handlePerPageChange = (per_page) => {
  emit('change-per-page', per_page);
};
</script>