<template>
  <div class="space-y-6 font-text text-slate-800">
    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Đổi / Trả hàng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Theo dõi, tiếp nhận và xử lý các yêu cầu đổi trả sản phẩm từ khách hàng</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="refreshData" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
          Làm mới
        </button>
      </div>
    </div>

    <!-- Stat Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <!-- Card: Total Requests -->
      <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-4 flex flex-col justify-between shadow-md">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-300">Tổng yêu cầu</p>
        <div class="mt-3 flex items-baseline justify-between">
          <span class="text-2xl font-bold">{{ stats.total }}</span>
          <span class="text-xs font-medium text-slate-400">Tất cả</span>
        </div>
      </div>

      <!-- Card: Pending -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Chờ xử lý</p>
          <p class="text-2xl font-bold text-amber-600 mt-1">{{ stats.pending }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
      </div>

      <!-- Card: Approved -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Đã chấp nhận</p>
          <p class="text-2xl font-bold text-blue-600 mt-1">{{ stats.approved }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
      </div>

      <!-- Card: Item Received -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Đã nhận hàng</p>
          <p class="text-2xl font-bold text-purple-600 mt-1">{{ stats.received }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
        </div>
      </div>

      <!-- Card: Refunded -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Đã hoàn tiền</p>
          <p class="text-2xl font-bold text-emerald-600 mt-1">{{ stats.refunded }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
      </div>

      <!-- Card: Rejected -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Từ chối</p>
          <p class="text-2xl font-bold text-rose-600 mt-1">{{ stats.rejected }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
      </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <!-- Toolbar Filter & Search -->
      <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
          <!-- Search input -->
          <div class="relative flex items-center w-full max-w-xs">
            <span class="absolute left-3 text-slate-400">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Tìm theo mã Yêu cầu, Mã đơn, Khách hàng..."
              class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-400 transition-colors"
            />
          </div>

          <!-- Status Filter -->
          <select
            v-model="selectedStatus"
            class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-slate-400 transition-colors cursor-pointer"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="pending">Chờ xử lý</option>
            <option value="approved">Đã chấp nhận</option>
            <option value="received">Đã nhận hàng</option>
            <option value="refunded">Đã hoàn tiền</option>
            <option value="rejected">Từ chối</option>
          </select>

          <!-- Reason Filter -->
          <select
            v-model="selectedReason"
            class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-slate-400 transition-colors cursor-pointer"
          >
            <option value="">Tất cả lý do</option>
            <option value="defective">Sản phẩm lỗi / Rách</option>
            <option value="wrong_size">Không vừa size</option>
            <option value="wrong_item">Giao sai màu / Mẫu</option>
            <option value="change_mind">Khách đổi ý</option>
          </select>
        </div>

        <div class="text-xs text-slate-500 font-medium">
          Hiển thị <span class="font-bold text-slate-800">{{ filteredRequests.length }}</span> yêu cầu
        </div>
      </div>

      <!-- Data Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
              <th class="py-3.5 px-5 whitespace-nowrap">Mã Yêu Cầu</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Mã Đơn Hàng</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Khách Hàng</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Sản Phẩm Trả</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Lý Do Trả Hàng</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Tiền Hoàn Dự Kiến</th>
              <th class="py-3.5 px-5 whitespace-nowrap">Trạng Thái</th>
              <th class="py-3.5 px-5 text-right whitespace-nowrap">Thao Tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <tr v-for="item in filteredRequests" :key="item.id" class="hover:bg-slate-50/80 transition-colors">
              <!-- Return Ticket Code -->
              <td class="py-4 px-5 font-bold text-slate-800 font-mono">
                {{ item.ticket_code }}
                <span class="block text-[10px] font-normal text-slate-400 mt-0.5">{{ item.created_at }}</span>
              </td>

              <!-- Order Code -->
              <td class="py-4 px-5 font-semibold text-blue-600 font-mono">
                {{ item.order_code }}
              </td>

              <!-- Customer Info -->
              <td class="py-4 px-5">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs shrink-0">
                    {{ item.customer_name.charAt(0) }}
                  </div>
                  <div>
                    <p class="font-bold text-slate-800">{{ item.customer_name }}</p>
                    <p class="text-[11px] text-slate-400 font-mono">{{ item.customer_phone }}</p>
                  </div>
                </div>
              </td>

              <!-- Product Info -->
              <td class="py-4 px-5 max-w-[220px]">
                <div class="flex items-center gap-2.5">
                  <img :src="item.product_image" :alt="item.product_name" class="w-10 h-12 object-cover rounded-lg border border-slate-200 shrink-0" />
                  <div class="truncate">
                    <p class="font-semibold text-slate-800 truncate" :title="item.product_name">{{ item.product_name }}</p>
                    <p class="text-[11px] text-slate-400">Size: {{ item.variant_size }} | Màu: {{ item.variant_color }} (x{{ item.quantity }})</p>
                  </div>
                </div>
              </td>

              <!-- Reason -->
              <td class="py-4 px-5">
                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 font-semibold rounded-md text-[11px]">
                  {{ getReasonLabel(item.reason) }}
                </span>
                <p v-if="item.customer_note" class="text-[11px] text-slate-500 mt-1 line-clamp-1 italic max-w-[180px]">"{{ item.customer_note }}"</p>
              </td>

              <!-- Refund Value -->
              <td class="py-4 px-5 font-bold text-emerald-700 font-mono text-sm">
                {{ formatPrice(item.refund_amount) }} đ
              </td>

              <!-- Status Badge -->
              <td class="py-4 px-5">
                <span :class="getStatusBadgeClass(item.status)" class="px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide inline-flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(item.status)"></span>
                  {{ getStatusLabel(item.status) }}
                </span>
              </td>

              <!-- Action buttons -->
              <td class="py-4 px-5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="openModal(item)"
                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition-colors"
                  >
                    Chi tiết
                  </button>
                </div>
              </td>
            </tr>

            <tr v-if="filteredRequests.length === 0">
              <td colspan="8" class="text-center py-12 text-slate-400 font-medium">
                Không tìm thấy yêu cầu đổi trả nào phù hợp.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Return Request Detail Modal -->
    <div v-if="selectedRequest" class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
      <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
          <div>
            <span class="text-xs font-bold font-mono text-blue-600 uppercase tracking-wider">{{ selectedRequest.ticket_code }}</span>
            <h2 class="text-lg font-bold text-slate-800">Chi tiết Yêu cầu Đổi / Trả hàng</h2>
          </div>
          <button @click="selectedRequest = null" class="w-8 h-8 rounded-full hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 text-xs">
          <!-- Status Banner -->
          <div class="p-4 rounded-xl flex items-center justify-between border" :class="getStatusBannerClass(selectedRequest.status)">
            <div>
              <p class="font-bold text-sm">Trạng thái: {{ getStatusLabel(selectedRequest.status) }}</p>
              <p class="text-[11px] opacity-80 mt-0.5">Ngày gửi yêu cầu: {{ selectedRequest.created_at }}</p>
            </div>
            <span class="text-xs font-mono font-bold">Mã Đơn: {{ selectedRequest.order_code }}</span>
          </div>

          <!-- Customer & Order Section -->
          <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <div>
              <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Khách Hàng</p>
              <p class="font-bold text-slate-800 text-sm">{{ selectedRequest.customer_name }}</p>
              <p class="text-slate-500 font-mono mt-0.5">{{ selectedRequest.customer_phone }}</p>
              <p class="text-slate-500 font-mono">{{ selectedRequest.customer_email }}</p>
            </div>
            <div>
              <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Địa chỉ lấy hàng trả</p>
              <p class="text-slate-700 leading-relaxed font-medium">{{ selectedRequest.pickup_address }}</p>
            </div>
          </div>

          <!-- Product Item Details -->
          <div>
            <p class="font-bold text-slate-700 text-sm mb-3">Sản phẩm yêu cầu đổi / trả</p>
            <div class="flex items-center gap-4 p-3 bg-white border border-slate-200 rounded-xl">
              <img :src="selectedRequest.product_image" :alt="selectedRequest.product_name" class="w-16 h-20 object-cover rounded-lg border border-slate-100 shrink-0" />
              <div class="grow space-y-1">
                <p class="font-bold text-slate-800 text-sm">{{ selectedRequest.product_name }}</p>
                <p class="text-slate-500">Kích cỡ: <span class="font-semibold text-slate-700">{{ selectedRequest.variant_size }}</span> | Màu sắc: <span class="font-semibold text-slate-700">{{ selectedRequest.variant_color }}</span></p>
                <p class="text-slate-500">Số lượng trả: <span class="font-bold text-slate-800">x{{ selectedRequest.quantity }}</span></p>
              </div>
              <div class="text-right">
                <p class="text-xs text-slate-400">Đơn giá</p>
                <p class="font-bold text-slate-800 text-sm font-mono">{{ formatPrice(selectedRequest.unit_price) }} đ</p>
              </div>
            </div>
          </div>

          <!-- Reason & Customer Notes -->
          <div class="space-y-3">
            <div>
              <p class="font-bold text-slate-700 text-xs mb-1">Lý do đổi trả:</p>
              <span class="inline-block px-3 py-1 bg-amber-50 text-amber-700 font-bold rounded-lg border border-amber-200">
                {{ getReasonLabel(selectedRequest.reason) }}
              </span>
            </div>

            <div>
              <p class="font-bold text-slate-700 text-xs mb-1">Ghi chú từ khách hàng:</p>
              <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 leading-relaxed italic">
                "{{ selectedRequest.customer_note }}"
              </div>
            </div>

            <!-- Proof Images -->
            <div>
              <p class="font-bold text-slate-700 text-xs mb-2">Hình ảnh bằng chứng đính kèm:</p>
              <div class="flex items-center gap-3">
                <img
                  v-for="(img, idx) in selectedRequest.proof_images"
                  :key="idx"
                  :src="img"
                  alt="Proof"
                  class="w-20 h-20 object-cover rounded-xl border border-slate-200 hover:scale-105 transition-transform cursor-pointer shadow-sm"
                />
              </div>
            </div>
          </div>

          <!-- Refund Summary -->
          <div class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl space-y-2 font-mono">
            <div class="flex justify-between text-slate-600">
              <span>Tổng giá trị sản phẩm:</span>
              <span>{{ formatPrice(selectedRequest.unit_price * selectedRequest.quantity) }} đ</span>
            </div>
            <div class="flex justify-between text-slate-600">
              <span>Phí vận chuyển đổi trả:</span>
              <span>- 0 đ (Miễn phí)</span>
            </div>
            <div class="border-t border-emerald-200 pt-2 flex justify-between font-bold text-emerald-800 text-sm">
              <span>TỔNG TIỀN HOÀN:</span>
              <span>{{ formatPrice(selectedRequest.refund_amount) }} đ</span>
            </div>
          </div>
        </div>

        <!-- Modal Actions Footer -->
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
          <button @click="selectedRequest = null" class="px-4 py-2 bg-white border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
            Đóng
          </button>
          
          <div class="flex items-center gap-2" v-if="selectedRequest.status === 'pending'">
            <button @click="updateStatus('rejected')" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition-colors border border-rose-200">
              Từ chối
            </button>
            <button @click="updateStatus('approved')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-md shadow-blue-200">
              Chấp nhận yêu cầu
            </button>
          </div>

          <div class="flex items-center gap-2" v-else-if="selectedRequest.status === 'approved'">
            <button @click="updateStatus('received')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-colors shadow-md shadow-purple-200">
              Xác nhận đã nhận hàng
            </button>
          </div>

          <div class="flex items-center gap-2" v-else-if="selectedRequest.status === 'received'">
            <button @click="updateStatus('refunded')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-colors shadow-md shadow-emerald-200">
              Xác nhận hoàn tiền
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedStatus = ref('')
const selectedReason = ref('')
const selectedRequest = ref(null)

// Mock Return Requests dataset
const requests = ref([
  {
    id: 1,
    ticket_code: '#RET-1001',
    order_code: '#ORD-8829',
    customer_name: 'Nguyễn Văn An',
    customer_phone: '0912 345 678',
    customer_email: 'nguyenvanan@gmail.com',
    pickup_address: '123 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
    product_name: 'Áo Sơ Mi Nữ Tay Dài Form Rộng Oxford',
    product_image: 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?q=80&w=400&auto=format&fit=crop',
    variant_size: 'M',
    variant_color: 'Trắng',
    quantity: 1,
    unit_price: 450000,
    refund_amount: 450000,
    reason: 'wrong_size',
    customer_note: 'Mặc bị chật vai một chút, muốn đổi sang size L hoặc trả hàng hoàn tiền.',
    proof_images: [
      'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?q=80&w=400&auto=format&fit=crop'
    ],
    status: 'pending',
    created_at: '2026-07-21 14:30'
  },
  {
    id: 2,
    ticket_code: '#RET-1002',
    order_code: '#ORD-8815',
    customer_name: 'Trần Thị Mai',
    customer_phone: '0988 765 432',
    customer_email: 'thimai.tran@gmail.com',
    pickup_address: '45 Nguyễn Huệ, Phường 2, Thành phố Vũng Tàu',
    product_name: 'Quần Jean Nam Slimfit Cao Cấp',
    product_image: 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=400&auto=format&fit=crop',
    variant_size: '31',
    variant_color: 'Xanh Đậm',
    quantity: 1,
    unit_price: 680000,
    refund_amount: 680000,
    reason: 'defective',
    customer_note: 'Khi bóc hàng thấy dải chỉ gấu quần bị sứt chỉ một đường dài.',
    proof_images: [
      'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=400&auto=format&fit=crop'
    ],
    status: 'approved',
    created_at: '2026-07-20 09:15'
  },
  {
    id: 3,
    ticket_code: '#RET-1003',
    order_code: '#ORD-8790',
    customer_name: 'Lê Hoàng Nam',
    customer_phone: '0933 112 233',
    customer_email: 'hoangnam.le@gmail.com',
    pickup_address: '88 Cầu Giấy, Quận Cầu Giấy, Hà Nội',
    product_name: 'Váy Đầm Dáng Xòe Họa Tiết Hoa Mùa Hè',
    product_image: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?q=80&w=400&auto=format&fit=crop',
    variant_size: 'S',
    variant_color: 'Hoa Vàng',
    quantity: 1,
    unit_price: 790000,
    refund_amount: 790000,
    reason: 'wrong_item',
    customer_note: 'Đơn đặt màu hoa vàng nhưng shop gửi nhầm sang hoa xanh.',
    proof_images: [
      'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?q=80&w=400&auto=format&fit=crop'
    ],
    status: 'received',
    created_at: '2026-07-19 16:45'
  },
  {
    id: 4,
    ticket_code: '#RET-1004',
    order_code: '#ORD-8752',
    customer_name: 'Phạm Minh Tuấn',
    customer_phone: '0905 999 888',
    customer_email: 'minhtuan.pham@gmail.com',
    pickup_address: '12 Nguyễn Văn Linh, Đà Nẵng',
    product_name: 'Áo Khoác Blazer Nam Hai Hàng Cúc',
    product_image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=400&auto=format&fit=crop',
    variant_size: 'XL',
    variant_color: 'Đen Classic',
    quantity: 1,
    unit_price: 1250000,
    refund_amount: 1250000,
    reason: 'change_mind',
    customer_note: 'Cảm thấy không hợp với phong cách cá nhân.',
    proof_images: [
      'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=400&auto=format&fit=crop'
    ],
    status: 'refunded',
    created_at: '2026-07-18 11:20'
  }
])

// Filtered Computed
const filteredRequests = computed(() => {
  return requests.value.filter(req => {
    const matchSearch =
      !searchQuery.value ||
      req.ticket_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      req.order_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      req.customer_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      req.customer_phone.includes(searchQuery.value)

    const matchStatus = !selectedStatus.value || req.status === selectedStatus.value
    const matchReason = !selectedReason.value || req.reason === selectedReason.value

    return matchSearch && matchStatus && matchReason
  })
})

// Stats Summary Computed
const stats = computed(() => {
  return {
    total: requests.value.length,
    pending: requests.value.filter(r => r.status === 'pending').length,
    approved: requests.value.filter(r => r.status === 'approved').length,
    received: requests.value.filter(r => r.status === 'received').length,
    refunded: requests.value.filter(r => r.status === 'refunded').length,
    rejected: requests.value.filter(r => r.status === 'rejected').length
  }
})

// Helpers
const formatPrice = (val) => {
  if (!val) return '0'
  return new Intl.NumberFormat('vi-VN').format(val)
}

const getReasonLabel = (reason) => {
  const map = {
    defective: 'Sản phẩm lỗi / Rách',
    wrong_size: 'Không vừa size',
    wrong_item: 'Giao sai màu / mẫu',
    change_mind: 'Đổi ý không muốn mua'
  }
  return map[reason] || 'Lý do khác'
}

const getStatusLabel = (status) => {
  const map = {
    pending: 'Chờ xử lý',
    approved: 'Đã chấp nhận',
    received: 'Đã nhận hàng',
    refunded: 'Đã hoàn tiền',
    rejected: 'Từ chối'
  }
  return map[status] || status
}

const getStatusBadgeClass = (status) => {
  const map = {
    pending: 'bg-amber-50 text-amber-700 border border-amber-200',
    approved: 'bg-blue-50 text-blue-700 border border-blue-200',
    received: 'bg-purple-50 text-purple-700 border border-purple-200',
    refunded: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    rejected: 'bg-rose-50 text-rose-700 border border-rose-200'
  }
  return map[status] || 'bg-slate-100 text-slate-700'
}

const getStatusDotClass = (status) => {
  const map = {
    pending: 'bg-amber-500',
    approved: 'bg-blue-500',
    received: 'bg-purple-500',
    refunded: 'bg-emerald-500',
    rejected: 'bg-rose-500'
  }
  return map[status] || 'bg-slate-400'
}

const getStatusBannerClass = (status) => {
  const map = {
    pending: 'bg-amber-50 border-amber-200 text-amber-900',
    approved: 'bg-blue-50 border-blue-200 text-blue-900',
    received: 'bg-purple-50 border-purple-200 text-purple-900',
    refunded: 'bg-emerald-50 border-emerald-200 text-emerald-900',
    rejected: 'bg-rose-50 border-rose-200 text-rose-900'
  }
  return map[status] || 'bg-slate-50 border-slate-200 text-slate-900'
}

const openModal = (item) => {
  selectedRequest.value = item
}

const updateStatus = (newStatus) => {
  if (selectedRequest.value) {
    selectedRequest.value.status = newStatus
    const found = requests.value.find(r => r.id === selectedRequest.value.id)
    if (found) found.status = newStatus
  }
}

const refreshData = () => {
  searchQuery.value = ''
  selectedStatus.value = ''
  selectedReason.value = ''
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.98); }
  to   { opacity: 1; transform: scale(1); }
}
</style>
