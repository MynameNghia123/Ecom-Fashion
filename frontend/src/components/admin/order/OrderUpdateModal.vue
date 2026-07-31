<template>
        <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4" :class="!isOpenUpdateModal ? 'hidden' : ''">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="emit('closeUpdateModal')"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[800px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100" v-if="selectedOrder">
          <div>
            <h2 class="text-base font-bold text-slate-800">Chỉnh sửa đơn hàng</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ selectedOrder.order_code }} · Ngày đặt: {{ selectedOrder.created_at }}</p>
          </div>
          <button @click="emit('closeUpdateModal')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body: 2 columns -->
        <div class="flex gap-0 overflow-y-auto flex-1" v-if="selectedOrder">

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
                    <select v-model="formData.status" class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:outline-none focus:border-[#0258cb] cursor-pointer pr-8">
                      <option value="pending">Chờ xác nhận</option>
                      <option value="processing">Đang xử lý</option>
                      <option value="shipping">Đang giao</option>
                      <option value="completed">Hoàn thành</option>
                      <option value="cancelled">Đã hủy</option>
                    </select>
                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Trạng thái thanh toán</label>
                  <div class="relative">
                    <select v-model="formData.payment_status" class="w-full appearance-none px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:outline-none focus:border-[#0258cb] cursor-pointer pr-8">
                      <option value="paid">Đã thanh toán</option>
                      <option value="unpaid">Chưa thanh toán</option>
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
                  <input type="text" v-model="formData.shipping_name" :disabled="!canEditShipping" class="w-full px-3 py-2 text-sm border rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed" :class="{'border-red-500': fieldError('shipping_name'), 'border-slate-200': !fieldError('shipping_name')}">
                  <p v-if="fieldError('shipping_name')" class="text-xs text-red-500 mt-1">{{ fieldError('shipping_name') }}</p>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Số điện thoại</label>
                  <input type="text" v-model="formData.shipping_phone" :disabled="!canEditShipping" class="w-full px-3 py-2 text-sm border rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed" :class="{'border-red-500': fieldError('shipping_phone'), 'border-slate-200': !fieldError('shipping_phone')}">
                  <p v-if="fieldError('shipping_phone')" class="text-xs text-red-500 mt-1">{{ fieldError('shipping_phone') }}</p>
                </div>
                <div>
                  <label class="block text-xs text-slate-500 mb-1">Địa chỉ</label>
                  <textarea v-model="formData.shipping_address" :disabled="!canEditShipping" rows="3" class="w-full px-3 py-2 text-sm border rounded-lg text-slate-700 bg-white focus:outline-none focus:border-[#0258cb] resize-none disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed" :class="{'border-red-500': fieldError('shipping_address'), 'border-slate-200': !fieldError('shipping_address')}"></textarea>
                  <p v-if="fieldError('shipping_address')" class="text-xs text-red-500 mt-1">{{ fieldError('shipping_address') }}</p>
                </div>
                <p v-if="!canEditShipping" class="text-[11px] text-amber-600 font-medium leading-snug mt-2">
                  * Chỉ được sửa thông tin giao hàng khi đơn ở trạng thái Chờ xác nhận và Chưa thanh toán.
                </p>
              </div>
            </div>

            <!-- Mã giảm giá (Chỉ xem) -->
            <div>
              <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-2 mt-4">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Mã giảm giá
              </p>
              <div class="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 flex items-center justify-between">
                <span v-if="selectedOrder.coupon_code" class="font-semibold text-emerald-600">{{ selectedOrder.coupon_code }}</span>
                <span v-else class="text-slate-400">Không có mã</span>
              </div>
            </div>
          </div>

          <!-- Right: Products -->
          <div class="flex-1 p-5 space-y-4">
            <div class="flex items-center justify-between">
              <p class="text-sm font-bold text-slate-800">Sản phẩm trong đơn</p>
              
              <!-- Search product (Chỉ hiện khi cho phép sửa) -->
              <div class="relative w-52" v-if="canEditShipping">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input 
                  type="text" 
                  v-model="searchProductKeyword"
                  @focus="showProductDropdown = searchProductResults.length > 0 || isSearchingProduct"
                  placeholder="Thêm sản phẩm..." 
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
              <p v-else class="text-xs text-amber-600 font-medium">Không thể sửa sản phẩm</p>
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
                <tr v-if="selectedProducts.length === 0">
                  <td colspan="5" class="py-8 text-center text-sm text-slate-500">
                    Chưa có sản phẩm nào được chọn
                  </td>
                </tr>
                <tr v-for="(item, index) in selectedProducts" :key="index">
                  <td class="py-3 pr-3">
                    <div class="flex items-center gap-2.5">
                      <img v-if="item.thumbnail || item.product_image" :src="item.thumbnail || item.product_image" class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 object-cover border border-slate-200">
                      <div v-else class="w-10 h-10 rounded-lg bg-slate-100 shrink-0 overflow-hidden flex items-center justify-center text-slate-400">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                      </div>
                      <div>
                        <p class="font-semibold text-slate-800 text-xs line-clamp-2">{{ item.product?.name || item.name || 'Sản phẩm #' + item.id }}</p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">SKU: {{ item.sku }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-2 text-right text-slate-700">{{ Number(item.price).toLocaleString('vi-VN') }}đ</td>
                  <td class="py-3 px-2 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="item.quantity > 1 ? item.quantity-- : null" :disabled="!canEditShipping" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">-</button>
                      <input type="number" v-model.number="item.quantity" :disabled="!canEditShipping" class="w-10 text-center text-sm font-semibold text-slate-800 border-none bg-transparent focus:ring-0 p-0 disabled:text-slate-500 disabled:cursor-not-allowed" min="1" :max="item.stock_quantity">
                      <button @click="item.quantity < item.stock_quantity ? item.quantity++ : null" :disabled="!canEditShipping || item.quantity >= item.stock_quantity" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">+</button>
                    </div>
                  </td>
                  <td class="py-3 px-2 text-right font-semibold text-slate-800">{{ Number(item.price * item.quantity).toLocaleString('vi-VN') }}đ</td>
                  <td class="py-3 pl-2 text-center">
                    <button @click="removeSelectedProduct(index)" :disabled="!canEditShipping" class="p-1 rounded text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Summary -->
            <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
              <div class="flex justify-between text-slate-600"><span>Tạm tính:</span><span>{{ Number(subtotal).toLocaleString('vi-VN') }}đ</span></div>
              <div class="flex justify-between text-slate-600"><span>Phí vận chuyển:</span><span>{{ Number(selectedOrder.shipping_fee || 0).toLocaleString('vi-VN') }}đ</span></div>
              <div class="flex justify-between text-emerald-600 font-semibold" v-if="selectedOrder.coupon_discount_amount > 0">
                <span>Khuyến mãi {{ selectedOrder.coupon_code ? `(${selectedOrder.coupon_code})` : '' }}:</span>
                <span>-{{ Number(selectedOrder.coupon_discount_amount).toLocaleString('vi-VN') }}đ</span>
              </div>
              <div class="flex justify-between font-bold text-slate-800 text-base pt-1 border-t border-slate-100">
                <span>Tổng cộng:</span>
                <span class="text-[#0258cb] text-lg">{{ Number(subtotal + (selectedOrder.shipping_fee || 0) - (selectedOrder.coupon_discount_amount || 0)).toLocaleString('vi-VN') }}đ</span>
              </div>
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
            <button @click="emit('closeUpdateModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
            <button @click="handleUpdate" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Cập nhật đơn hàng
            </button>
          </div>
        </div>
      </div>
    </div>
</template>
<script setup>
import { defineProps, defineEmits, ref, watch, computed } from 'vue';
import { useOrderStore } from '@/stores/admin/orderStore';
import { useProductStore } from '@/stores/admin/productStore';
import { useOrderValidation } from '@/composables/admin/validation/useOrderValidation';

const orderStore = useOrderStore();
const productStore = useProductStore();
const { formErrors, clearErrors, fieldError, applyBackendErrors } = useOrderValidation();

const props = defineProps({
  isOpenUpdateModal: {
    type: Boolean,
    default: false
  },
  selectedOrder: {
    type: Object,
    default: null
  }
});

watch(() => props.selectedOrder, (newVal) => {
  if (newVal) {
    console.log('Chi tiết đơn hàng:', newVal);
  }
});

const emit = defineEmits(['closeUpdateModal', 'updateOrder']);

const canEditShipping = computed(() => {
  if (!props.selectedOrder) return false;
  return props.selectedOrder.status === 'pending' && props.selectedOrder.payment_status === 'unpaid';
});

const formData = ref({
  customer_id: null,
  status: '',
  payment_status: '',
  payment_method: '',
  shipping_name: '',
  shipping_phone: '',
  shipping_address: ''
});

// Update formData khi selectedOrder thay đổi
watch(() => props.selectedOrder, (newVal) => {
  clearErrors();
  if (newVal) {
    formData.value = {
      customer_id: newVal.customer_id,
      status: newVal.status,
      payment_status: newVal.payment_status,
      payment_method: newVal.payment_method,
      shipping_name: newVal.shipping_name,
      shipping_phone: newVal.shipping_phone,
      shipping_address: newVal.shipping_address
    };
    
    // Khởi tạo danh sách sản phẩm từ order_details
    selectedProducts.value = (newVal.order_details || []).map(detail => ({
      id: detail.product_variant_id,
      product: { name: detail.product_name },
      name: detail.product_name,
      sku: detail.sku,
      thumbnail: detail.product_image,
      price: detail.unit_price,
      quantity: detail.quantity,
      stock_quantity: 9999 // Đặt số tồn kho lớn vì đang lấy từ db cũ
    }));
  }
}, { immediate: true });

// --- Live Search Product Variant ---
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

const isSubmitting = ref(false);

const handleUpdate = async () => {
  if (!props.selectedOrder) return;
  
  // Nếu chưa có sản phẩm nào
  if (selectedProducts.value.length === 0) {
    alert("Vui lòng chọn ít nhất 1 sản phẩm cho đơn hàng!");
    return;
  }
  
  const payload = {
    ...formData.value,
    order_details: selectedProducts.value.map(p => ({
      product_variant_id: p.id,
      quantity: p.quantity,
      unit_price: p.price
    }))
  };
  
  isSubmitting.value = true;
  try {
    // Gọi action updateOrder từ store
    await orderStore.updateOrder(props.selectedOrder.id, payload);
    
    // Đóng modal, component cha sẽ tự động reload dữ liệu nhờ emit 'updateOrder' (nếu cần thiết) hoặc store tự update
    emit('updateOrder'); 
    emit('closeUpdateModal');
  } catch (error) {
    if (error.response && error.response.status === 422) {
      applyBackendErrors(error);
    } else {
      console.error("Lỗi khi cập nhật đơn hàng:", error);
      alert('Có lỗi xảy ra khi cập nhật đơn hàng!');
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>
<style scoped>
</style>