<template>
<!-- Modal Backdrop & Container -->
    <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            </div>
            <div>
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Chi tiết phiếu nhập kho</h2>
                <span class="font-mono text-sm text-slate-500">{{ receipt?.receipt_code }}</span>
                <span v-if="receipt?.status === 'completed'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    ĐÃ HOÀN THÀNH
                </span>
                <span v-else-if="receipt?.status === 'approved'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    ĐÃ DUYỆT
                </span>
                <span v-else-if="receipt?.status === 'pending'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    CHỜ DUYỆT
                </span>
                <span v-else-if="receipt?.status === 'cancel'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    ĐÃ HUỶ
                </span>
            </div>
            </div>
        </div>
        <button
            @click="emit('close')"
            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        </div>

        <!-- Body -->
        <div class="px-7 py-5 overflow-y-auto space-y-5">

        <!-- Info grid -->
        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 grid grid-cols-2 sm:grid-cols-4 gap-5">
            <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nhà cung cấp</p>
            <p class="text-sm font-bold text-slate-800 leading-snug">{{ supplierName }}</p>
            </div>
            <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nhân viên tạo</p>
            <p class="text-sm font-semibold text-slate-700">{{ receipt?.staff_name ?? receipt?.staff_id ?? 'Admin' }}</p>
            </div>
            <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày nhập</p>
            <p class="text-sm font-semibold text-slate-700">{{ formattedDate }}</p>
            </div>
        </div>

        <!-- Product table -->
        <div class="rounded-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-10">STT</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Mã SP</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên sản phẩm</th>
                <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Số lượng</th>
                <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Đơn giá (VND)</th>
                <th class="py-3 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Thành tiền (VND)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <!-- Dynamic rows -->
                <tr v-for="(detail, index) in receipt?.good_receipt_details" :key="detail.id" class="hover:bg-neutral-100/30 transition-colors duration-100">
                <td class="py-3.5 px-4 text-slate-500 font-mono text-xs">{{ index + 1 }}</td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-500">{{ detail.product_variant?.sku || 'N/A' }}</td>
                <td class="py-3.5 px-4">
                    <p class="font-semibold text-slate-800">{{ detail.product_variant?.product?.name || 'Sản phẩm không xác định' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">ID: {{ detail.product_variant_id }}</p>
                </td>
                <td class="py-3.5 px-4 text-right text-slate-700 font-semibold">{{ detail.quantity }}</td>
                <td class="py-3.5 px-4 text-right text-slate-700">{{ formatCurrency(detail.import_price) }}</td>
                <td class="py-3.5 px-4 text-right font-bold text-slate-800">{{ formatCurrency(detail.quantity * detail.import_price) }}</td>
                </tr>
            </tbody>
            </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="flex justify-end">
            <div class="w-72 space-y-2 text-sm">
            <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                <span class="font-bold text-slate-700">Tổng cộng:</span>
                <div class="text-right">
                <span class="text-xl font-bold text-black">{{ formatCurrency(receipt?.total_amount_price) }}</span>
                </div>
            </div>
            </div>
        </div>

        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button 
        @click="$emit('close')"
        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
        </div>
    </div>
    </div>
</template>
<script setup>
  import { computed } from 'vue';
  import { useSupplierStore } from '@/stores/admin/supplierStore';
  const supplierStore = useSupplierStore();

  const props = defineProps({
    isShowView: {
      type: Boolean,
      required: true
    },
    receipt: {
        type: Object,
        default: null
    }
  });
  const emit = defineEmits(['close']);

  const supplierName = computed(() => {
    if (!props.receipt?.supplier_id) return 'N/A';
    const supplier = supplierStore.suppliers.find(s => s.id === props.receipt.supplier_id);
    return supplier ? supplier.name : 'Unknown Supplier';
  });

  const formattedDate = computed(() => {
    if (!props.receipt?.created_at) return '';
    const date = new Date(props.receipt.created_at);
    return date.toLocaleDateString('vi-VN');
  });

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