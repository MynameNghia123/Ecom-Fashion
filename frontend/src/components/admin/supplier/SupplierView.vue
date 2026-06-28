<template>
  <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
  :class="formView?.isShowView ? '' : 'hidden'"
  >
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[680px] animate-modal-in flex flex-col max-h-[90vh]">

      <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
        <div>
          <h2 class="text-base font-bold text-slate-800">Chi tiết Nhà phân phối</h2>
          <p class="text-xs text-slate-400 mt-0.5">Thông tin chi tiết và lịch sử sản phẩm</p>
        </div>
        <button 
          @click="emit('close')"
          class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <div class="px-7 py-5 overflow-y-auto space-y-5">

        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-bold text-white shrink-0 bg-blue-500">
                NPP
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-800">{{ supplierData?.name }}</h3>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                  <span class="text-xs text-slate-400 font-mono">SUP-{{ supplierData?.id }}</span>
                  <span 
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border "
                    :class=" supplierData?.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-500 border-red-100'"
                    >
                    <span 
                      class="w-1.5 h-1.5 rounded-full "
                      :class=" supplierData?.is_active ? 'bg-emerald-500': 'bg-red-500'"
                      ></span>
                    {{ supplierData?.is_active ?  'Đang hợp tác' : 'Ngừng hợp tác' }} 
                  </span>
                </div>
              </div>
            </div>
            <button 
              @click="emit('edit')"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-semibold hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150 shrink-0">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Chỉnh sửa
            </button>
          </div>

          <div class="grid grid-cols-2 gap-x-8 gap-y-4 mt-5">

            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Email
              </p>
              <p class="text-sm font-semibold text-slate-700">{{ supplierData?.emal }}</p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Điện thoại
              </p>
              <p class="text-sm font-semibold text-slate-700">{{ supplierData?.phone }}</p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Địa chỉ
              </p>
              <p class="text-sm font-semibold text-slate-700">{{ supplierData?.address }}</p>
            </div>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-bold text-slate-700">Sản phẩm cung cấp</p>
            <span class="px-2.5 py-1 bg-blue-50 text-[#0258cb] text-xs font-bold rounded-lg border border-blue-100">
              2 sản phẩm
            </span>
          </div>
          <div class="rounded-xl border border-slate-100 overflow-hidden">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên sản phẩm</th>
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                  <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Danh mục</th>
                  <th class="py-2.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Giá nhập</th>
                  <th class="py-2.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Đã nhập</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-blue-50/30 transition-colors duration-100">
                  <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                      </div>
                      <span class="font-medium text-slate-800">Sản phẩm Demo</span>
                    </div>
                  </td>
                  <td class="py-3 px-4 font-mono text-xs text-slate-500">SKU-001</td>
                  <td class="py-3 px-4">
                    <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold px-2 py-0.5 rounded-md">Danh mục A</span>
                  </td>
                  <td class="py-3 px-4 text-right text-slate-700 font-medium">100.000 đ</td>
                  <td class="py-3 px-4 text-right font-bold text-emerald-500">50</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
        <button 
          @click="emit('close')"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">
          Đóng
        </button>
        <button 
          @click="emit('edit')"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
          Chỉnh sửa
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { defineProps, defineEmits } from 'vue';
const emit = defineEmits(['close', 'edit']);
const formView = defineProps({
  isShowView: {
    type: Boolean,
    default: false
  },
  supplierData: {
    type: Object,
    default: () => ({})
  }
});
</script>
<style>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}

.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>