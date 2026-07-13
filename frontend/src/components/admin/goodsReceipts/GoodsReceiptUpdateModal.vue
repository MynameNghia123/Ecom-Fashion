<template>
  <!-- Modal Backdrop & Container -->
  <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
       :class=" isShowUpdate ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0'">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[700px] animate-modal-in flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
        <div>
          <h2 class="text-base font-bold text-slate-800">Chỉnh sửa phiếu nhập kho #{{ updatedReceipt.receipt_code }}</h2>
        </div>
        <button
          @click="closeModal"
          class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-7 py-6 overflow-y-auto space-y-5">

        <!-- NCC + Status -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Nhà cung cấp <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                </svg>
              </span>
              <select 
                v-model="updatedReceipt.supplier_id"
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200">
                <option value="" disabled>Chọn nhà cung cấp</option>
                <option v-for="supplier in supplierList" :key="supplier.id" :value="supplier.id">
                  {{ supplier.name }}
                </option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Trạng thái phiếu</label>
            <select 
              v-model="updatedReceipt.status"
              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200">
              <option value="pending">Đang chờ duyệt</option>
              <option value="approved">Đã duyệt</option>
              <option value="completed">Đã hoàn thành</option>
              <option value="cancel">Đã huỷ</option>
            </select>
          </div>
        </div>

        <!-- Product list (edit) -->
        <div>
          <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-bold text-slate-700">Danh sách sản phẩm</p>
            <button 
              @click="addGoodsReceiptDetails"
              class="inline-flex items-center gap-1 text-sm font-semibold text-[#0258cb] hover:text-[#004bb3] transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
              Thêm dòng
            </button>
          </div>

          <div class="rounded-xl border border-slate-200 overflow-visible">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-8">STT</th>
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm / Biến thể</th>
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Số lượng</th>
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Đơn giá nhập</th>
                  <th class="w-10"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-for="(goodReceiptDetail, index) in goodsReceiptDetails" 
                    :key="goodReceiptDetail.id">
                  <td class="py-3 px-4 text-slate-500 font-mono text-xs">{{ index + 1 }}</td>
                  <td class="py-2 px-4">
                    <div class="relative">
                      <input
                        type="text"
                        v-model="goodReceiptDetail.searchQuery"
                        @input="handleSearch(goodReceiptDetail)"
                        @focus="activeRowIndex = index"
                        @blur="hideDropdown"
                        placeholder="Tìm sản phẩm / biến thể..."
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                      />
                      <!-- Dropdown list -->
                      <div 
                        v-if="activeRowIndex === index && (productVariantStore.searchResults.length > 0 || productVariantStore.loading)"
                        class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                      >
                        <div v-if="productVariantStore.loading" class="p-3 text-sm text-center text-slate-500">
                          Đang tìm kiếm...
                        </div>
                        <ul v-else>
                          <li 
                            v-for="variant in productVariantStore.searchResults" 
                            :key="variant.id"
                            @mousedown.prevent="selectVariant(goodReceiptDetail, variant)"
                            class="px-3 py-2 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0"
                          >
                            <div class="text-sm font-semibold text-slate-700">{{ variant.product?.name || 'Sản phẩm' }}</div>
                            <div class="text-xs text-slate-500">SKU: {{ variant.sku }}</div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="py-2 px-4">
                    <input
                      type="number"
                      min="1"
                      v-model="goodReceiptDetail.quantity"
                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all text-center"
                    />
                  </td>
                  <td class="py-2 px-4">
                    <div class="flex items-center gap-1">
                      <input
                        type="number"
                        min="0"
                        v-model="goodReceiptDetail.import_price"
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                      />
                      <span class="text-slate-400 text-xs font-medium shrink-0">đ</span>
                    </div>
                  </td>
                  <td class="py-2 px-2 text-center">
                    <button 
                      @click="deleteGoodsReceiptDetails(index)"
                      class="p-1.5 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all duration-150">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Totals -->
          <div class="mt-4 flex justify-end">
            <div class="w-64 space-y-2">
              <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                <span class="text-sm font-bold text-slate-700">Tổng cộng:</span>
                <span class="text-lg font-bold text-[#0258cb]">{{ formatCurrency(updatedReceipt.total_amount_price) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button 
          @click="closeModal"
          :disabled="isSubmitting"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150 disabled:opacity-50">Hủy</button>
        <button 
          @click="handleUpdate"
          :disabled="isSubmitting"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
          <svg v-if="!isSubmitting" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          {{ isSubmitting ? 'Đang lưu...' : 'Cập nhật phiếu nhập' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, watch, onMounted } from 'vue';
  import { useProductVariantStore } from '@/stores/admin/productVariantStore'

  const productVariantStore = useProductVariantStore();

  const props = defineProps({
    isShowUpdate: {
      type: Boolean,
      required: true
    },
    receipt: {
      type: Object,
      default: null
    },
    supplierList: {
      type: Array,
      default: () => []
    }
  });

  const emit = defineEmits(['close', 'onHandleUpdate']);
  const isSubmitting = ref(false);

  const updatedReceipt = ref({
    receipt_code: '',
    supplier_id: '',
    staff_id: null,
    total_amount_price: 0,
    status: 'pending',
    good_receipt_details: [],
  });

  const goodsReceiptDetails = ref([]);

  onMounted(() => {
    if (props.receipt) {
      updatedReceipt.value = { ...props.receipt };
      if (props.receipt.good_receipt_details) {
        goodsReceiptDetails.value = props.receipt.good_receipt_details.map(detail => ({
          id: detail.id || Date.now() + Math.random(),
          product_variant_id: detail.product_variant_id,
          product_variant_name: detail.product_variant?.product?.name ? `${detail.product_variant.product.name} (SKU: ${detail.product_variant.sku})` : (detail.product_variant?.sku || ''),
          quantity: detail.quantity,
          import_price: detail.import_price,
          searchQuery: detail.product_variant?.product?.name ? `${detail.product_variant.product.name} (SKU: ${detail.product_variant.sku})` : (detail.product_variant?.sku || ''),
        }));
      }
    }
  });

  const addGoodsReceiptDetails = function(){
    goodsReceiptDetails.value.push({
      id: Date.now(),
      product_variant_id: '',
      product_variant_name: '',
      quantity: 1,
      import_price: 0,
      searchQuery: '',
    });
  }

  const activeRowIndex = ref(null);
  let searchTimeout = null;

  const handleSearch = (detail) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
      if (detail.searchQuery.trim().length > 0) {
        await productVariantStore.searchVariants(detail.searchQuery);
      } else {
        productVariantStore.clearSearch();
      }
    }, 400);
  };

  const selectVariant = (detail, variant) => {
    detail.product_variant_id = variant.id;
    const variantName = variant.product?.name ? `${variant.product.name} (SKU: ${variant.sku})` : variant.sku;
    detail.product_variant_name = variantName;
    detail.searchQuery = variantName;
    detail.import_price = variant.cost_price || variant.price || 0;
    activeRowIndex.value = null;
    productVariantStore.clearSearch();
  };

  const hideDropdown = () => {
    setTimeout(() => {
      activeRowIndex.value = null;
    }, 150);
  };

  watch(goodsReceiptDetails, (newDetails) => {
    updatedReceipt.value.total_amount_price = newDetails.reduce((total, detail) => {
      return total + detail.quantity * detail.import_price;
    }, 0);
  }, { deep: true });

  const deleteGoodsReceiptDetails = function(index){
    goodsReceiptDetails.value.splice(index, 1);
  }

  const closeModal = () => {
    emit('close');
  };

  const handleUpdate = async () => {
    if (!updatedReceipt.value.receipt_code) {
      alert("Vui lòng nhập mã phiếu");
      return;
    }
    if (!updatedReceipt.value.supplier_id) {
      alert("Vui lòng chọn nhà cung cấp");
      return;
    }
    const validDetails = goodsReceiptDetails.value.filter(d => d.product_variant_id);
    if (validDetails.length === 0) {
      alert("Vui lòng thêm ít nhất 1 sản phẩm");
      return;
    }

    isSubmitting.value = true;
    try {
      updatedReceipt.value.good_receipt_details = validDetails.map(d => ({
        product_variant_id: d.product_variant_id,
        quantity: d.quantity,
        import_price: d.import_price
      }));
      
      await new Promise(resolve => setTimeout(resolve, 500));
      emit('onHandleUpdate', props.receipt.id, JSON.parse(JSON.stringify(updatedReceipt.value)));
    } finally {
      isSubmitting.value = false;
    }
  };

  const formatCurrency = (value) => {
    const numericValue = Number(value);
    if (isNaN(numericValue)) {
      return value; 
    }
    return new Intl.NumberFormat('vi-VN', { 
      style: 'currency', 
      currency: 'VND' 
    }).format(numericValue);
  };
</script>