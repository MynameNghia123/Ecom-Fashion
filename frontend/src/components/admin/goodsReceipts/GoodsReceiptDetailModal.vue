<template>
    <Teleport to="body">
        <Transition name="modal-fade">
        <div
            class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
        >
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[90vh]">

            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                    <h2 class="text-base font-bold text-slate-800">Chi tiết phiếu nhập kho</h2>
                    <span class="font-mono text-sm text-slate-500">{{ viewTarget?.code }}</span>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                        :class="statusClass(viewTarget?.status)"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(viewTarget?.status)"></span>
                        {{ statusLabel(viewTarget?.status)?.toUpperCase() }}
                    </span>
                    </div>
                </div>
                </div>
                <button @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
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
                    <p class="text-sm font-bold text-slate-800 leading-snug">{{ viewTarget?.supplier_name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nhân viên tạo</p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.staff_name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày nhập</p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.import_date }}</p>
                </div>
                </div>

                <!-- Product table -->
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                <table class="w-full text-sm">
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
                    <tr
                        v-for="(item, idx) in viewTarget?.items"
                        :key="idx"
                        class="hover:bg-blue-50/30 transition-colors duration-100"
                    >
                        <td class="py-3.5 px-4 text-slate-500 font-mono text-xs">{{ idx + 1 }}</td>
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-500">{{ item.sku || '—' }}</td>
                        <td class="py-3.5 px-4">
                        <p class="font-semibold text-slate-800">{{ item.product_name }}</p>
                        <p v-if="item.variant" class="text-xs text-slate-400 mt-0.5">{{ item.variant }}</p>
                        </td>
                        <td class="py-3.5 px-4 text-right text-slate-700 font-semibold">{{ item.qty?.toLocaleString('vi-VN') }}</td>
                        <td class="py-3.5 px-4 text-right text-slate-700">{{ item.import_price?.toLocaleString('vi-VN') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-slate-800">{{ (item.qty * item.import_price)?.toLocaleString('vi-VN') }}</td>
                    </tr>
                    </tbody>
                </table>
                </div>

                <!-- Summary -->
                <div class="flex justify-end">
                <div class="w-72 space-y-2 text-sm">
                    <div v-if="viewTarget?.extra_cost" class="flex justify-between text-slate-600">
                    <span>Chi phí khác:</span>
                    <span class="font-semibold">{{ formatPrice(viewTarget.extra_cost) }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-700">Tổng cộng:</span>
                    <div class="text-right">
                        <span class="text-xl font-bold text-[#0258cb]">{{ formatPrice(viewTarget?.total || 0) }}</span>
                        <span class="text-xs text-slate-400 ml-1">VND</span>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Note -->
                <div v-if="viewTarget?.note" class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-sm text-amber-700">
                <span class="font-bold">Ghi chú:</span> {{ viewTarget.note }}
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
                <button @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Đóng</button>
                <button
                @click="printReceipt"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 active:scale-[0.98]"
                >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                In phiếu
                </button>
            </div>
            </div>
        </div>
        </Transition>
    </Teleport>
</template>
