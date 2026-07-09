<template>
    <!-- ══════════════════════ PAGE HEADER ══════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Phiếu nhập kho</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage and track your goods receipts</p>
      </div>
      <button
        id="btn-open-add-receipt"
        @click="isShowAdd = true"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm phiếu nhập mới
      </button>
    </div>

    <!-- ══════════════════════ STATS CARDS ══════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Total receipts -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng phiếu nhập</p>
          <p class="text-3xl font-bold text-slate-800">{{ goodsReceiptStore.meta.total || 0 }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
          </svg>
        </div>
      </div>
      <!-- Total import value -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng giá trị nhập</p>
          <p class="text-3xl font-bold text-slate-800">{{ helperFormatCurrency(totalImportValue) }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </div>
      </div>
      <!-- Pending -->
      <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Phiếu chờ xử lý</p>
          <p class="text-3xl font-bold text-slate-800">{{ totalPendingReceipts }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- ══════════════════════ ERROR BANNER (Tùy chọn) ══════════════════════ -->
    <!-- <div class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
      <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      Đã xảy ra lỗi trong quá trình tải dữ liệu. Vui lòng thử lại sau.
    </div> -->

    <!-- ══════════════════════ TABLE CARD ══════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <!-- Search -->
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            id="search-receipt"
            type="text"
            v-model="searchQuery"
            @input="handleFilter"
            placeholder="Search receipts by code..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <div class="flex-1"></div>
        <!-- Filter Status -->
        <select
          v-model="statusFilter"
          @change="handleFilter"
          class="px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
        >
          <option value="">Tất cả trạng thái</option>
          <option value="pending">Chờ duyệt</option>
          <option value="approved">Đã duyệt</option>
          <option value="completed">Đã hoàn thành</option>
          <option value="cancelled">Đã huỷ</option>
        </select>
        <!-- Filter button -->
        <button 
          @click="handleFilter"
          class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
          </svg>
        </button>
        <!-- Export -->
        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mã phiếu</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nhà cung cấp</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nhân viên</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng tiền</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày nhập</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Trạng thái</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Actions</th>
            </tr>
          </thead>
      <template v-if="goodsReceiptStore.loading">
        <tbody>
          <tr v-for="i in 5" :key="'sk-'+i" class="animate-pulse">
            <td class="py-4 px-5"><div class="h-4 bg-slate-200 rounded w-8"></div></td>
            <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-36"></div></td>
            <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
            <td class="py-4 px-4"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
            <td class="py-4 px-4"><div class="h-6 bg-slate-200 rounded-full w-24"></div></td>
            <td class="py-4 px-4"><div class="flex justify-end gap-2"><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div><div class="h-8 w-8 bg-slate-200 rounded-lg"></div></div></td>
          </tr>
        </tbody>
      </template>
      <template v-else>
        <tbody class="divide-y divide-slate-50">

          <!-- Static Row 1: Đã hoàn thành -->
          <tr 
            v-for="(receipt, index) in goodsReceiptStore.goodsReceipts"
            :key="index"
            class="hover:bg-blue-50/40 transition-colors duration-100 group">
            <td class="py-4 px-5">
              <span class="font-mono text-sm font-semibold text-slate-700">{{ receipt.receipt_code }}</span>
            </td>
            <td class="py-4 px-4 text-slate-700 font-medium">{{ findSupplierNameById(receipt.supplier_id) }}</td>
            <td class="py-4 px-4 text-slate-500">{{ receipt.staff_id ?? 'ADMIN'}}</td>
            <td class="py-4 px-4 text-right font-semibold text-slate-800">{{ helperFormatCurrency(receipt.total_amount_price)}}</td>
            <td class="py-4 px-4 text-slate-500 text-xs">{{helperFomatDate(receipt.created_at) }}</td>
            <td class="py-4 px-4">
              <span v-if="receipt.status === 'completed'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Đã hoàn thành
              </span>
              <span v-else-if="receipt.status === 'approved'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Đã duyệt
              </span>
              <span v-else-if="receipt.status === 'pending'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-100">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Chờ duyệt
              </span>
              <span v-else-if="receipt.status === 'cancel'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                Đã huỷ
              </span>
              <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-100">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                {{ receipt.status }}
              </span>
            </td>
            <td class="py-4 px-4">
              <div class="flex items-center justify-end gap-1">
                <button 
                  @click="isShowView = true; selectedReceipt = receipt"
                  class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Xem chi tiết">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
                <button 
                  v-if="receipt.status !== 'cancel' && receipt.status !== 'completed'"
                  @click="isShowUpdate = true; selectedReceipt = receipt"
                  class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150" title="Chỉnh sửa">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <button
                  v-if="receipt.status !== 'cancel' && receipt.status !== 'completed'"
                  @click="isShowDelete = true; selectedReceipt = receipt"
                  class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150" title="Xóa">
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
        <!-- Pagination Footer -->

      </div>
      <div class="px-5 py-4 border-t border-slate-100">
          <Pagination
            :current-page="goodsReceiptStore.meta.current_page"
            :per-page="goodsReceiptStore.meta.per_page"
            :total="goodsReceiptStore.meta.total"
            :last-page="goodsReceiptStore.meta.last_page"
            :loading="goodsReceiptStore.loading"
            @update:currentPage="handleCurrentPageChange"
            @update:perPage="handlePerPageChange"
          />
      </div>
    </div>
    
    <ConfirmDeleteModal
      v-if="isShowDelete"
      :show="isShowDelete"
      @confirm="confirmDelete"
      @cancel="isShowDelete = false"
    />

    <GoodsReceiptAddModal
      v-if="isShowAdd"
      :isShowAdd="isShowAdd"
      :supplierList="suppliers"
      @close="isShowAdd = false"
      @onHandleSave="handleSave"
    />

    <GoodsReceiptUpdateModal
      v-if="isShowUpdate"
      :isShowUpdate="isShowUpdate"
      :receipt="selectedReceipt"
      :supplierList="suppliers"
      @close="isShowUpdate = false"
      @onHandleUpdate="handleUpdate"
    />

    <GoodsReceiptDetailModal
      v-if="isShowView"
      :isShowView="isShowView"
      :receipt="selectedReceipt"
      @close="isShowView = false"
    />

</template>
<script setup>
import GoodsReceiptAddModal from '@/components/admin/goodsReceipts/GoodsReceiptAddModal.vue';
import GoodsReceiptUpdateModal from '@/components/admin/goodsReceipts/GoodsReceiptUpdateModal.vue';
import GoodsReceiptDetailModal from '@/components/admin/goodsReceipts/GoodsReceiptDetailModal.vue';
import Pagination from '@/components/admin/Pagination.vue';
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue';
import { useGoodsReceiptStore } from '@/stores/admin/goodsReceiptStore.js';
import { useSupplierStore } from '@/stores/admin/supplierStore';
import { ref, onMounted, computed } from 'vue';
const supplierStore = useSupplierStore();
const suppliers = ref([]);
const goodsReceiptStore = useGoodsReceiptStore();

const searchQuery = ref('');
const statusFilter = ref('');

onMounted(async () => {
  await goodsReceiptStore.initialFetch();
  await supplierStore.initialFetch();
  suppliers.value = await supplierStore.getSupplierDropdown();
});

// tính tổng giá trị nhập của 1 phiếu nhập hàng 
const totalImportValue = computed(() => {
  return goodsReceiptStore.goodsReceipts.reduce((sum, receipt) => sum + Number(receipt.total_amount_price), 0);
});

// tính tổng phiếu nhập hàng chờ duyệt
const totalPendingReceipts = computed(() => {
  return goodsReceiptStore.goodsReceipts.filter(receipt => receipt.status === 'pending').length;
});


// xử lý filter phiếu nhập hàng
let filterTimeout = null;
const handleFilter = () => {
  if (filterTimeout) clearTimeout(filterTimeout);
  filterTimeout = setTimeout(async () => {
    await goodsReceiptStore.fetchGoodsReceipt({
      search: searchQuery.value,
      status: statusFilter.value,
      page: 1
    });
  }, 400);
};

// format tiền tệ
const helperFormatCurrency = (value) => {
  const numericValue = Number(value);
  if (isNaN(numericValue)) {
    return value; 
  }
  return new Intl.NumberFormat('vi-VN', { 
    style: 'currency', 
    currency: 'VND' 
  }).format(numericValue);
};

// format ngày tháng
const helperFomatDate = (dateString) => {
  if (!dateString) return '';
  return dateString.split(' ')[0];
};

// tìm tên nhà cung cấp theo id
const findSupplierNameById = (supplierId) => {
  const supplier = supplierStore.suppliers.find(s => s.id === supplierId);
  return supplier ? supplier.name : 'Unknown Supplier';
};

const selectedReceipt = ref(null);
const isShowView = ref(false);
const isShowAdd = ref(false);
const handleSave = async (receiptData, applyBackendErrors) => {
  try {
    await goodsReceiptStore.createGoodsReceipt(receiptData);
    isShowAdd.value = false;
    handleFilter(); // refresh list
  } catch (e) {
    if (applyBackendErrors) {
      applyBackendErrors(e);
    } else {
      console.error(e);
    }
  }
}

const isShowUpdate = ref(false);
const handleUpdate = async (id, receiptData, applyBackendErrors) => {
  try {
    await goodsReceiptStore.updateGoodsReceipt(receiptData, id);
    isShowUpdate.value = false;
    handleFilter(); // refresh list
  } catch (e) {
    if (applyBackendErrors) {
      applyBackendErrors(e);
    } else {
      console.error(e);
    }
  }
}

const isShowDelete = ref(false);
const confirmDelete = async () => {
  if (selectedReceipt.value) {
    await goodsReceiptStore.deleteGoodsReceipt(selectedReceipt.value.id);
  }
  isShowDelete.value = false;
  handleFilter();
}

const handleCurrentPageChange = async (page) => {
  await goodsReceiptStore.fetchGoodsReceipt({ page });
}

const handlePerPageChange = async (perPage) => {
  await goodsReceiptStore.fetchGoodsReceipt({ per_page: perPage, page: 1 });
}

</script>
