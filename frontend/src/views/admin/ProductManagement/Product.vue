<template>
  <div class="space-y-6">
    <!-- Button thêm sản phầm mới -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Sản phẩm</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý danh mục hàng hóa, giá bán và trạng thái tồn kho của hệ thống</p>
      </div>
      <button
        @click="useModal = 'add'"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm sản phẩm mới
      </button>
    </div>
    
    <!-- hiện thị tổng sản phẩm, đang hoạt động, hết hàng, mới trong tháng -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng sản phẩm</p>
        <p class="text-3xl font-bold text-slate-800">{{ productStore.stats.total }}</p>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hoạt động</p>
        <div class="flex items-end gap-2">
          <p class="text-3xl font-bold text-slate-800">{{ productStore.stats.active }}</p>
          <span class="mb-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ percentActiveProducts }}%</span>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Hết hàng</p>
        <p class="text-3xl font-bold text-red-500">{{ productStore.stats.out_of_stock }}</p>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Mới trong tháng</p>
        <p class="text-3xl font-bold text-[#0258cb]">+{{ productStore.stats.new_this_month }}</p>
      </div>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- FIlter -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo tên sản phẩm, thương hiệu..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
          />
        </div>
        <div class="relative ">
          <select v-model="filterCategory" class="appearance-none pl-4 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer">
            <option value="">Tất cả danh mục</option>
            <option v-for="cat in categoryStore.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>
        <div class="relative">
          <select v-model="filterStatus" class="appearance-none pl-4 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer">
            <option value="">Trạng thái</option>
            <option value="1">Đang hoạt động</option>
            <option value="0">Ngừng hoạt động</option>
          </select>
          <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>
        <button class="ml-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
          Lọc nâng cao
        </button>
      </div>


      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[80px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Thương hiệu</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Danh mục</th>
              <th class="py-3.5 px-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Tồn Kho tổng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày tạo</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Thao tác</th>
            </tr>
          </thead>
            <template v-if="productStore.loading">
              <tr v-for="i in categoryStore.meta.per_page" :key="i" class="animate-pulse">
                <td class="py-4 px-6"><div class="h-4 bg-slate-200 rounded w-12"></div></td>
                <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-40"></div></td>
                <td class="py-4 px-6">
                  <div class="flex justify-end gap-2">
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                    <div class="h-8 w-8 bg-slate-200 rounded-lg"></div>
                  </div>
                </td>
              </tr>
            </template>
            <template v-else-if="productStore.products.length === 0">
              <tr>
                <td colspan="8" class="py-6 text-center text-slate-500">Không có sản phẩm nào</td>
              </tr>
            </template>
            <template v-else>
              <tbody class="divide-y divide-slate-50">
              <tr v-for="product in productStore.products" :key="product.id"
                class="hover:bg-blue-50/40 transition-colors duration-100">
                <td class="py-4 px-5 font-mono text-xs text-slate-500">
                  #{{ product.id }}
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200">
                      <img v-if="product.thumbnail" :src="product.thumbnail" class="w-full h-full object-cover" />
                      <svg v-else class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                      <p class="font-semibold text-slate-800 leading-tight">{{ product.name }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-4 text-slate-700 font-medium text-sm">{{ product.brand }}</td>
                <td class="py-4 px-4">
                  <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                    {{ categoryStore.categories.find(cate => cate.id === product.category_id)?.name || 'Chưa phân loại' }}
                  </span>
                </td>
                <td class="py-4 px-4 text-center">
                  <span class="text-slate-800 font-semibold">
                    {{ getProductStock(product) }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span
                    :class="product.is_active
                      ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                      : 'bg-slate-100 text-slate-500 border-slate-200'"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border"
                  >
                    <span :class="product.is_active ? 'bg-emerald-500' : 'bg-slate-400'" class="w-1.5 h-1.5 rounded-full"></span>
                    {{ product.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="py-4 px-4 text-slate-500 text-sm">
                  {{ formatDateOnly(product.created_at) }}
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-end gap-1">
                    <button 
                      @click="openDetail(product)"
                      class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all" title="Xem chi tiết">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                    <button 
                      @click="openEdit(product)"  
                    class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all" title="Chỉnh sửa">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button 
                    @click="openDelete(product)"
                    class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all" title="Xóa">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              </tbody>
            </template>
        </table>
      </div>

      <div class="px-5 py-4 border-t border-slate-100">
        <p class="text-sm text-slate-500 text-center">
          <Pagination
            :current-page="productStore.meta.current_page"
            :per-page="productStore.meta.per_page"
            :total="productStore.meta.total"
            :last-page="productStore.meta.last_page"
            @update:current-page="handleCurrentPage"
            @update:per-page="handlePerPageChange"
          />
        </p>
      </div>
    </div>


    <!-- Modal Area -->
  <ProductDetailModal 
    v-if="useModal === 'detail'"
    :product="selectedProduct"
    @close="close"
    @moveToUpdate="moveToUpdate"
    />

  <ProductFormModal 
    :action="useModal"
    :product="selectedProduct"
    v-if="useModal === 'add' || useModal === 'edit'" 
    @closeForm="closeForm"
    @save="save"
    />

  <ConfirmDeleteModal 
    v-if="useModal === 'delete'" 
    :show="true"
    :title="'Xác nhận xóa sản phẩm'"
    :message="'Bạn có chắc chắn muốn xóa sản phẩm'"
    :itemName="selectedProduct?.name || ''"
    @cancel="cancel"
    @confirm="confirmDelete"
    />

    <div class="hidden">
       <p>Component Delete Modal Area</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useProductStore } from '@/stores/admin/productStore'
import { useCategoryStore } from '@/stores/admin/categoryStore'
import  Pagination from '@/components/admin/Pagination.vue'
import  ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import  ProductDetailModal from '@/components/admin/product/ProductDetailModal.vue';
import  ProductFormModal from '@/components/admin/product/ProductFormModal.vue';

const productStore = useProductStore();
const categoryStore = useCategoryStore();

const useModal = ref('');
const selectedProduct = ref(null);

const searchQuery = ref('');
const filterCategory = ref('');
const filterStatus = ref('');

watch([searchQuery, filterCategory, filterStatus], () => {
  handleCurrentPage(1, true);
});

const percentActiveProducts = computed(() => {
  if (productStore.stats.total === 0) return 0;
  return ((productStore.stats.active / productStore.stats.total) * 100).toFixed(1);
});
onMounted(async () => {
  // 2 fetch độc lập → chạy song song
  await Promise.all([
    productStore.initialFetch(),
    categoryStore.initialFetch(),
  ]);
});

const formatDateOnly = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('vi-VN'); 
};

// Tính tổng tồn kho của sản phẩm từ tất cả biến thể
const getProductStock = (product) => {
  if (!product.variants || product.variants.length === 0) return 0;
  return product.variants.reduce((sum, v) => sum + (v.stock_quantity ?? 0), 0);
};

// ─── Mở modal ───────────────────────────────────────────────────────────────
const openDetail = (product) => {
  selectedProduct.value = product;
  useModal.value = 'detail';
}
const openEdit = (product) => {
  selectedProduct.value = product;
  useModal.value = 'edit';
}
const openDelete = (product) => {
  selectedProduct.value = product;
  useModal.value = 'delete';
}

// ─── Phân trang ──────────────────────────────────────────────────────────────
const handleCurrentPage = async (page, forceRefresh = false) => {
  if (!forceRefresh && page === productStore.meta.current_page) return;
  productStore.meta.current_page = page;
  await productStore.fetchProducts({ 
    page, 
    per_page: productStore.meta.per_page,
    search: searchQuery.value,
    category_id: filterCategory.value,
    is_active: filterStatus.value
  });
}
const handlePerPageChange = async (perPage) => {
  productStore.meta.per_page = perPage;
  await handleCurrentPage(1, true);
}

// ─── Xóa ────────────────────────────────────────────────────────────────────
const confirmDelete = async () => {
  if (!selectedProduct.value) return;
  try {
    await productStore.deleteProduct(selectedProduct.value.id);
  } catch (e) {
    console.error('Lỗi xóa sản phẩm:', e);
  } finally {
    selectedProduct.value = null;
    useModal.value = '';
  }
};
const cancel = () => {
  selectedProduct.value = null;
  useModal.value = '';
}

// ─── Form (Thêm / Sửa) ──────────────────────────────────────────────────────
const closeForm = () => {
  selectedProduct.value = null;
  useModal.value = '';
}
const save = async () => {
  useModal.value = '';
  selectedProduct.value = null;
  await handleCurrentPage(1, true);
}

// ─── Chi tiết ────────────────────────────────────────────────────────────────
const close = () => {
  selectedProduct.value = null;
  useModal.value = '';
}
const moveToUpdate = () => {
  useModal.value = 'edit';
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(12px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>