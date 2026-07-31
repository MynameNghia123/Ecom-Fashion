<template>
    <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
      :class="isOpenCreateModal === false ? 'hidden' : ''"
    >
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
          <button 
            @click="emit('closeCreateModal')"
            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
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
              <div class="relative" v-if="!selectedCustomer">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input 
                  type="text" 
                  v-model="searchCustomerKeyword"
                  placeholder="Tìm tên, SĐT khách hàng..." 
                  class="w-full pl-8 pr-10 py-2 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                  @focus="showCustomerDropdown = searchResults.length > 0 || isSearchingCustomer"
                >
                <!-- Loading spin -->
                <span v-if="isSearchingCustomer" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#0258cb]">
                  <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                </span>

                <!-- Smart search dropdown -->
                <div v-if="showCustomerDropdown" class="absolute top-full left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-20 overflow-hidden max-h-[250px] overflow-y-auto">
                  <div v-if="isSearchingCustomer" class="p-4 text-center text-sm text-slate-500">
                    Đang tìm kiếm...
                  </div>
                  <div v-else-if="searchResults.length === 0" class="p-4 text-center text-sm text-slate-500">
                    Không tìm thấy khách hàng nào.
                  </div>
                  <div v-else class="p-1.5 space-y-0.5">
                    <div 
                      v-for="customer in searchResults" 
                      :key="customer.id"
                      @click="selectCustomer(customer)"
                      class="flex items-start gap-3 px-3 py-2.5 hover:bg-slate-50 rounded-lg cursor-pointer"
                    >
                      <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ customer.first_name ? customer.first_name.charAt(0).toUpperCase() : 'C' }}
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ customer.last_name }} {{ customer.first_name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ customer.phone_number || customer.email }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Selected customer -->
              <div v-else class="mt-3 p-3 bg-blue-50/50 border border-blue-100 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ selectedCustomer.first_name ? selectedCustomer.first_name.charAt(0).toUpperCase() : 'C' }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ selectedCustomer.last_name }} {{ selectedCustomer.first_name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ selectedCustomer.phone_number || selectedCustomer.email }}</p>
                  </div>
                </div>
                <button @click="removeSelectedCustomer" class="text-xs font-semibold text-[#0258cb] hover:underline shrink-0 ml-2">Thay đổi</button>
              </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div>
              <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Thông tin giao hàng</p>
              <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2.5">
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Tên người nhận <span class="text-red-500">*</span></label>
                    <input type="text" v-model="orderData.customer_address.receiver_name" :class="{'border-red-500': fieldError('customer_address.receiver_name')}" placeholder="Nhập tên người nhận..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                    <p v-if="fieldError('customer_address.receiver_name')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.receiver_name') }}</p>
                  </div>
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" v-model="orderData.customer_address.receiver_phone" :class="{'border-red-500': fieldError('customer_address.receiver_phone')}" placeholder="Nhập số điện thoại..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                    <p v-if="fieldError('customer_address.receiver_phone')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.receiver_phone') }}</p>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Tỉnh/Thành phố <span class="text-red-500">*</span></label>
                    <input type="text" v-model="orderData.customer_address.province" :class="{'border-red-500': fieldError('customer_address.province')}" placeholder="Nhập tỉnh/thành phố..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                    <p v-if="fieldError('customer_address.province')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.province') }}</p>
                  </div>
                  <div>
                    <label class="block text-xs text-slate-500 mb-1">Quận/Huyện <span class="text-red-500">*</span></label>
                    <input type="text" v-model="orderData.customer_address.district" :class="{'border-red-500': fieldError('customer_address.district')}" placeholder="Nhập quận/huyện..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                    <p v-if="fieldError('customer_address.district')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.district') }}</p>
                  </div>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Phường/Xã <span class="text-red-500">*</span></label>
                  <input type="text" v-model="orderData.customer_address.ward" :class="{'border-red-500': fieldError('customer_address.ward')}" placeholder="Nhập phường/xã..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                  <p v-if="fieldError('customer_address.ward')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.ward') }}</p>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Địa chỉ chi tiết <span class="text-red-500">*</span></label>
                  <input type="text" v-model="orderData.customer_address.detail_address" :class="{'border-red-500': fieldError('customer_address.detail_address')}" placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường)..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb]">
                  <p v-if="fieldError('customer_address.detail_address')" class="text-red-500 text-xs mt-1">{{ fieldError('customer_address.detail_address') }}</p>
                </div>

                <div class="pt-2">
                  <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Phương thức thanh toán <span class="text-red-500">*</span></label>
                  <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                      <input type="radio" v-model="orderData.payment_method" value="COD" class="w-4 h-4 text-[#0258cb] focus:ring-[#0258cb]">
                      <span>Thanh toán khi nhận hàng (COD)</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                      <input type="radio" v-model="orderData.payment_method" value="BANK_TRANSFER" class="w-4 h-4 text-[#0258cb] focus:ring-[#0258cb]">
                      <span>Chuyển khoản ngân hàng</span>
                    </label>
                  </div>
                  <p v-if="fieldError('payment_method')" class="text-red-500 text-xs mt-1">{{ fieldError('payment_method') }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Products -->
          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
              <p class="text-sm font-bold text-slate-800">Danh sách sản phẩm</p>
              <!-- Search product -->
              <div class="relative w-52">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input 
                  type="text" 
                  v-model="searchProductKeyword"
                  @focus="showProductDropdown = searchProductResults.length > 0 || isSearchingProduct"
                  placeholder="Tìm sản phẩm, SKU..." 
                  class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all">
                <!-- Loading spin -->
                <span v-if="isSearchingProduct" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#0258cb]">
                  <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                </span>
                <!-- Smart search dropdown -->
                <div v-if="showProductDropdown" class="absolute top-full left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-20 overflow-hidden max-h-[300px] overflow-y-auto">
                  <div v-if="isSearchingProduct" class="p-4 text-center text-sm text-slate-500">
                    Đang tìm kiếm...
                  </div>
                  <div v-else-if="searchProductResults.length === 0" class="p-4 text-center text-sm text-slate-500">
                    Không tìm thấy sản phẩm nào.
                  </div>
                  <div v-else class="p-1.5 space-y-0.5">
                    <div 
                      v-for="variant in searchProductResults" 
                      :key="variant.id"
                      @click="selectProductVariant(variant)"
                      class="flex items-start gap-3 px-3 py-2.5 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors"
                    >
                      <img :src="variant.thumbnail || (variant.product && variant.product.thumbnail)" alt="" class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200 shrink-0">
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ variant.product?.name || variant.name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                          <p class="text-[11px] text-slate-500 font-mono">{{ variant.sku }}</p>
                          <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                          <p class="text-xs font-semibold text-[#0258cb]">{{ variant.price?.toLocaleString('vi-VN') }}đ</p>
                        </div>
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
                <tr v-if="selectedProducts.length === 0">
                  <td colspan="6" class="py-8 text-center text-sm text-slate-500">
                    Chưa có sản phẩm nào được chọn
                  </td>
                </tr>
                <tr v-else v-for="(item, index) in selectedProducts" :key="index">
                  <td class="py-3 pr-3 min-w-[200px]">
                    <div class="flex items-center gap-3">
                      <img :src="item.thumbnail || (item.product && item.product.thumbnail)" alt="" class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-200">
                      <div>
                        <p class="text-sm font-semibold text-slate-800 line-clamp-2">{{ item.product?.name || item.name }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">SKU: {{ item.sku }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-3 text-right">
                    <p class="text-sm font-semibold text-slate-800">{{ item.price?.toLocaleString('vi-VN') }}đ</p>
                  </td>
                  <td class="py-3 px-3 text-center">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold" :class="item.stock_quantity > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'">
                      {{ item.stock_quantity }}
                    </span>
                  </td>
                  <td class="py-3 px-3">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="item.quantity > 1 ? item.quantity-- : removeSelectedProduct(index)" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">-</button>
                      <input type="number" v-model.number="item.quantity" class="w-10 text-center text-sm font-semibold text-slate-800 border-none bg-transparent focus:ring-0 p-0" min="1" :max="item.stock_quantity">
                      <button @click="item.quantity < item.stock_quantity ? item.quantity++ : null" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" :disabled="item.quantity >= item.stock_quantity">+</button>
                    </div>
                  </td>
                  <td class="py-3 px-3 text-right">
                    <p class="text-sm font-bold text-[#0258cb]">{{ (item.price * item.quantity).toLocaleString('vi-VN') }}đ</p>
                  </td>
                  <td class="py-3 pl-3 text-right">
                    <button @click="removeSelectedProduct(index)" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
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
                    <input type="text" v-model="couponCode" placeholder="Nhập mã coupon..." :disabled="isApplyingCoupon || appliedCoupon" class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:outline-none transition-all disabled:opacity-70 disabled:bg-slate-100">
                  </div>
                  <button v-if="!appliedCoupon" @click="applyCoupon" :disabled="isApplyingCoupon" class="px-3.5 py-2 text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors whitespace-nowrap disabled:opacity-50">
                    <span v-if="isApplyingCoupon">Đang xử lý...</span>
                    <span v-else>Áp dụng</span>
                  </button>
                  <button v-else @click="removeCoupon" class="px-3.5 py-2 text-sm font-semibold bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-colors whitespace-nowrap">
                    Xóa mã
                  </button>
                </div>
                <p v-if="couponError" class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  {{ couponError }}
                </p>
                <p v-if="appliedCoupon" class="mt-1.5 text-xs text-emerald-600 font-semibold flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  Đã áp dụng mã: {{ appliedCoupon.code }}
                </p>
              </div>
              <!-- Summary -->
              <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-slate-600"><span>Tạm tính (Subtotal):</span><span>{{ subtotal.toLocaleString('vi-VN') }} đ</span></div>
                <div class="flex justify-between text-red-500 font-semibold"><span>Chiết khấu (Discount):</span><span>-{{ discount.toLocaleString('vi-VN') }} đ</span></div>
                <div class="flex justify-between text-slate-600"><span>Phí vận chuyển:</span><span>{{ shippingFee.toLocaleString('vi-VN') }} đ</span></div>
                <div class="flex justify-between font-bold text-slate-800 text-base pt-2 border-t border-slate-100"><span>Tổng cộng:</span><span class="text-[#0258cb]">{{ total.toLocaleString('vi-VN') }} đ</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
          <button 
            @click="emit('closeCreateModal')"
            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Hủy</button>
          <button 
            @click="handleSaveOrder"
            :disabled="isSubmitting"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98] shadow-md shadow-blue-200 disabled:opacity-70">
            <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            {{ isSubmitting ? 'Đang lưu...' : 'Xác nhận & Lưu đơn hàng' }}
          </button>
        </div>
      </div>
    </div>
</template>
<script setup>
import { defineProps, defineEmits, ref, reactive, watch, computed } from 'vue';
import { useCustomerStore } from '@/stores/admin/customerStore';
import { useProductStore } from '@/stores/admin/productStore';
import { useCouponStore } from '@/stores/admin/couponStore';
import { useOrderStore } from '@/stores/admin/orderStore';
import { useOrderValidation } from '@/composables/admin/validation/useOrderValidation';

const { formErrors, validate, clearErrors, fieldError, applyBackendErrors } = useOrderValidation();

const props = defineProps({
  isOpenCreateModal: {
    type: Boolean,
    required: true
  }
});
const emit = defineEmits(['openCreateModal', 'closeCreateModal', 'saveCreateModal']);

const customerStore = useCustomerStore();
const orderStore = useOrderStore();

// --- Live Search Customer ---
const searchCustomerKeyword = ref('');
const searchResults = ref([]);
const isSearchingCustomer = ref(false);
const showCustomerDropdown = ref(false);
const selectedCustomer = ref(null);
let searchTimeout = null;



// Reset dữ liệu khi mở modal
watch(() => props.isOpenCreateModal, (isOpen) => {
  if (isOpen) {
    selectedCustomer.value = null;
    searchCustomerKeyword.value = '';
    searchResults.value = [];
    showCustomerDropdown.value = false;

    clearErrors();
    orderData.payment_method = 'COD';
    orderData.customer_address = {
      receiver_name: '',
      receiver_phone: '',
      province: '',
      district: '',
      ward: '',
      detail_address: ''
    };

    selectedProducts.value = [];
    searchProductKeyword.value = '';
    searchProductResults.value = [];
    showProductDropdown.value = false;

    couponCode.value = '';
    couponError.value = '';
    appliedCoupon.value = null;
    isApplyingCoupon.value = false;
  }
});

watch(searchCustomerKeyword, (newVal) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  
  if (!newVal || newVal.trim() === '') {
    searchResults.value = [];
    showCustomerDropdown.value = false;
    return;
  }

  // Debounce 300ms
  searchTimeout = setTimeout(async () => {
    isSearchingCustomer.value = true;
    showCustomerDropdown.value = true;
    
    // Gọi action store mà ta vừa tạo
    const results = await customerStore.searchCustomers(newVal);
    searchResults.value = results;
    
    isSearchingCustomer.value = false;
  }, 300); 
});

const orderData = reactive({
  payment_method: 'COD',
  customer_address: {
    receiver_name: '',
    receiver_phone: '',
    province: '',
    district: '',
    ward: '',
    detail_address: ''
  }
});

const selectCustomer = (customer) => {
  selectedCustomer.value = customer;
  searchCustomerKeyword.value = '';
  showCustomerDropdown.value = false;
  
  if (customer.default_address) {
    orderData.customer_address = {
      receiver_name: customer.default_address.receiver_name || '',
      receiver_phone: customer.default_address.receiver_phone || '',
      province: customer.default_address.province || '',
      district: customer.default_address.district || '',
      ward: customer.default_address.ward || '',
      detail_address: customer.default_address.detail_address || ''
    };
  } else {
    orderData.customer_address = {
      receiver_name: '',
      receiver_phone: '',
      province: '',
      district: '',
      ward: '',
      detail_address: ''
    };
  }
};

const removeSelectedCustomer = () => {
  selectedCustomer.value = null;
  orderData.customer_address = {
    receiver_name: '',
    receiver_phone: '',
    province: '',
    district: '',
    ward: '',
    detail_address: ''
  };
  setTimeout(() => showCustomerDropdown.value = false, 0);
};

// --- Live Search Product Variant ---
const productStore = useProductStore();
const searchProductKeyword = ref('');
const searchProductResults = ref([]);
const isSearchingProduct = ref(false);
const showProductDropdown = ref(false);
let searchProductTimeout = null;

const selectedProducts = ref([]);

watch(searchProductKeyword, (newVal) => {
  if (searchProductTimeout) clearTimeout(searchProductTimeout);
  
  if (!newVal || newVal.trim() === '') {
    searchProductResults.value = [];
    showProductDropdown.value = false;
    return;
  }

  // Debounce 300ms
  searchProductTimeout = setTimeout(async () => {
    isSearchingProduct.value = true;
    showProductDropdown.value = true;
    
    const results = await productStore.searchVariantBySku(newVal);
    searchProductResults.value = results;
    
    isSearchingProduct.value = false;
  }, 300); 
});

const selectProductVariant = (variant) => {
  const existing = selectedProducts.value.find(p => p.id === variant.id);
  if (existing) {
    if (existing.quantity < variant.stock_quantity) {
      existing.quantity += 1;
    }
  } else {
    selectedProducts.value.push({
      ...variant,
      quantity: 1
    });
  }
  
  searchProductKeyword.value = '';
  showProductDropdown.value = false;
};

const removeSelectedProduct = (index) => {
  selectedProducts.value.splice(index, 1);
};

// --- Tính toán giỏ hàng ---
const subtotal = computed(() => {
  return selectedProducts.value.reduce((sum, item) => sum + ((item.price || 0) * (item.quantity || 0)), 0);
});

const shippingFee = computed(() => {
  // Tạm tính phí vận chuyển cố định nếu có sản phẩm, có thể tuỳ chỉnh sau
  return selectedProducts.value.length > 0 ? 30000 : 0;
});

// --- Logic Coupon ---
const couponStore = useCouponStore();
const couponCode = ref('');
const couponError = ref('');
const isApplyingCoupon = ref(false);
const appliedCoupon = ref(null);

const applyCoupon = async () => {
  couponError.value = '';
  
  if (!couponCode.value.trim()) {
    couponError.value = 'Vui lòng nhập mã giảm giá';
    return;
  }
  
  if (selectedProducts.value.length === 0) {
    couponError.value = 'Vui lòng chọn sản phẩm trước khi áp dụng mã';
    return;
  }

  isApplyingCoupon.value = true;
  try {
    const res = await couponStore.checkCoupon({ 
      code: couponCode.value.trim(), 
      order_total: subtotal.value 
    });
    
    if (res.success) {
      appliedCoupon.value = res.data;
    } else {
      couponError.value = res.message || 'Mã giảm giá không hợp lệ';
    }
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      couponError.value = error.response.data.message;
    } else {
      couponError.value = 'Có lỗi xảy ra khi kiểm tra mã giảm giá';
    }
  } finally {
    isApplyingCoupon.value = false;
  }
};

const removeCoupon = () => {
  appliedCoupon.value = null;
  couponCode.value = '';
  couponError.value = '';
};

// Sửa lại discount để map từ appliedCoupon
const discount = computed(() => {
  if (!appliedCoupon.value) return 0;
  
  if (appliedCoupon.value.type === 'fixed') {
    return Number(appliedCoupon.value.discount_value);
  } else if (appliedCoupon.value.type === 'percent') {
    return subtotal.value * (Number(appliedCoupon.value.discount_value) / 100);
  }
  
  return 0;
});

const total = computed(() => {
  return Math.max(0, subtotal.value + shippingFee.value - discount.value);
});

// --- API Save Order ---
const isSubmitting = ref(false);
const handleSaveOrder = async () => {
  if (selectedProducts.value.length === 0) {
    alert('Vui lòng chọn ít nhất 1 sản phẩm');
    return;
  }
  
  if (!validate(orderData)) {
    return;
  }

  // Dựng payload gửi lên backend
  const payload = {
    customer_id: selectedCustomer.value ? selectedCustomer.value.id : null,
    coupon_id: appliedCoupon.value ? appliedCoupon.value.id : null,
    payment_method: orderData.payment_method,
    shipping_fee: shippingFee.value,
    customer_address: { ...orderData.customer_address },
    order_details: selectedProducts.value.map(p => ({
      product_variant_id: p.id,
      quantity: p.quantity,
      unit_price: p.price
    }))
  };

  isSubmitting.value = true;
  try {
    await orderStore.createOrder(payload);
    emit('saveCreateModal'); // Đóng modal và báo thành công
  } catch (error) {
    if (error.response?.status === 422) {
      applyBackendErrors(error.response.data);
    } else {
      console.error(error);
      alert('Có lỗi xảy ra khi tạo đơn hàng!');
    }
  } finally {
    isSubmitting.value = false;
  }
};

</script>
<style scoped>
</style>
