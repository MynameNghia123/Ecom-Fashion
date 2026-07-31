<template>
    <div class="fixed inset-0 z-[9998] flex items-start justify-center p-4 pt-16" :class="!isOpenDetailModal ? 'hidden' : ''">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="emit('closeDetailModal')"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[85vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100" v-if="selectedOrder">
          <div class="flex items-center gap-3">
            <h2 class="text-base font-bold text-slate-800">Chi tiết đơn hàng</h2>
            <span class="text-sm text-slate-500 font-mono">{{ selectedOrder.order_code }}</span>
            <span 
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
              :class="{
                'bg-amber-100 text-amber-700': selectedOrder.status === 'pending',
                'bg-blue-100 text-blue-700': selectedOrder.status === 'processing' || selectedOrder.status === 'shipping',
                'bg-emerald-100 text-emerald-700': selectedOrder.status === 'completed',
                'bg-red-100 text-red-600': selectedOrder.status === 'cancelled'
              }"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="selectedOrder.status === 'cancelled' ? 'bg-red-500' : 'bg-current'"></span>
              {{ 
                selectedOrder.status === 'pending' ? 'Chờ xác nhận' : 
                selectedOrder.status === 'processing' ? 'Đang xử lý' : 
                selectedOrder.status === 'shipping' ? 'Đang giao' : 
                selectedOrder.status === 'completed' ? 'Hoàn thành' : 
                selectedOrder.status === 'cancelled' ? 'Đã hủy' : selectedOrder.status 
              }}
            </span>
          </div>
          <button @click="emit('closeDetailModal')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-7 py-5 overflow-y-auto space-y-5" v-if="selectedOrder">

          <!-- 2 info cards -->
           
          <div class="grid grid-cols-2 gap-4">
            <!-- Thông tin khách hàng -->
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-[#0258cb] flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Thông tin khách hàng (Tài khoản)
              </p>
              <p class="text-sm font-semibold text-slate-800">{{ selectedOrder.customer_name || 'Khách vãng lai' }}</p>
              <p class="text-sm text-slate-500 mt-0.5">{{ selectedOrder.customer_email || 'N/A' }}</p>
            </div>
            <!-- Thông tin giao hàng -->
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-[#0258cb] flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Thông tin nhận hàng
              </p>
              <p class="text-sm font-semibold text-slate-800">{{ selectedOrder.shipping_name }}</p>
              <p class="text-sm text-slate-500 mt-0.5">{{ selectedOrder.shipping_phone }}</p>
              <p class="text-sm text-slate-500 mt-0.5">{{ selectedOrder.shipping_address }}</p>
            </div>
          </div>

          <!-- Products -->
          <div v-if="selectedOrder.order_details && selectedOrder.order_details.length > 0">
            <p class="text-sm font-bold text-slate-800 mb-3">Sản phẩm đã mua ({{ selectedOrder.order_details.length }})</p>
            <table class="w-full text-sm border border-slate-100 rounded-xl overflow-hidden">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                  <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm</th>
                  <th class="py-3 px-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Số lượng</th>
                  <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đơn giá</th>
                  <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng cộng</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-for="detail in selectedOrder.order_details" :key="detail.id">
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <img v-if="detail.product_image" :src="detail.product_image" class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 object-cover border border-slate-200">
                      <div v-else class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden flex items-center justify-center text-slate-400">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800">{{ detail.product_name || 'Sản phẩm #' + detail.product_variant_id }}</p>
                        <p class="text-[11px] text-slate-400 font-mono">{{ detail.sku }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ detail.quantity }}</td>
                  <td class="py-3.5 px-4 text-right text-slate-700">{{ Number(detail.unit_price).toLocaleString('vi-VN') }}đ</td>
                  <td class="py-3.5 px-4 text-right font-semibold text-slate-800">{{ Number(detail.unit_price * detail.quantity).toLocaleString('vi-VN') }}đ</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-sm text-slate-500 text-center py-4 bg-slate-50 rounded-xl">
            Không có thông tin chi tiết sản phẩm.
          </div>

          <!-- Summary -->
          <div class="grid grid-cols-2 gap-4">
            <div class="border border-slate-100 rounded-xl p-4">
              <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Thanh toán</p>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-slate-100 rounded text-xs font-semibold text-slate-600 uppercase">{{ selectedOrder.payment_method }}</span>
                <span class="text-xs text-slate-500" v-if="selectedOrder.transaction_id">Mã GD: {{ selectedOrder.transaction_id }}</span>
              </div>
              <p class="text-sm text-slate-600 flex items-center gap-1.5" :class="selectedOrder.payment_status === 'paid' ? 'text-emerald-600 font-semibold' : 'text-amber-600 font-semibold'">
                <svg v-if="selectedOrder.payment_status === 'paid'" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ selectedOrder.payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
              </p>
              <p class="text-xs text-slate-600 mt-2 flex items-center gap-1">
                Mã giảm giá: 
                <span v-if="selectedOrder.coupon_code" class="font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ selectedOrder.coupon_code }}</span>
                <span v-else class="text-slate-400">Không áp dụng</span>
              </p>
              <p class="text-xs text-slate-400 mt-2">Ngày đặt: {{ selectedOrder.created_at }}</p>
            </div>
            <div class="border border-slate-100 rounded-xl p-4 space-y-2">
              <div class="flex justify-between text-sm text-slate-600"><span>Tạm tính:</span><span>{{ Number(selectedOrder.sub_total_amount || 0).toLocaleString('vi-VN') }}đ</span></div>
              <div class="flex justify-between text-sm text-red-500 font-semibold" v-if="selectedOrder.coupon_discount_amount > 0">
                <span>Giảm giá {{ selectedOrder.coupon_code ? `(${selectedOrder.coupon_code})` : '' }}:</span>
                <span>- {{ Number(selectedOrder.coupon_discount_amount).toLocaleString('vi-VN') }}đ</span>
              </div>
              <div class="flex justify-between text-sm text-slate-600"><span>Phí vận chuyển:</span><span>{{ Number(selectedOrder.shipping_fee || 0).toLocaleString('vi-VN') }}đ</span></div>
              <div class="border-t border-slate-100 pt-2 flex justify-between font-bold text-slate-800"><span class="text-base">Tổng thanh toán:</span><span class="text-xl text-[#0258cb]">{{ Number(selectedOrder.final_amount || 0).toLocaleString('vi-VN') }}đ</span></div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
          <button @click="emit('closeDetailModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
          <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            In hóa đơn
          </button>
        </div>
      </div>
    </div>
</template>
<script setup>
import { defineProps, defineEmits } from 'vue';
const props = defineProps({
  isOpenDetailModal: {
    type: Boolean,
    default: false
  },
  selectedOrder: {
    type: Object,
    default: null
  }
});
const emit = defineEmits(['closeDetailModal']);
</script>
<style scoped> 
</style>