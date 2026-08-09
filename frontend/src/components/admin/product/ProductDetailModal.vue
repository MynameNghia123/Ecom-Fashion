<template>
  <!-- Modal Chi tiết sản phẩm -->
  <Teleport to="body">
    <Transition name="modal-fade">
      <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[1080px] max-h-[90vh] flex flex-col animate-modal-in">
          <div class="flex items-center justify-between px-7 pt-5 pb-4 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
              <h2 class="text-base font-bold text-slate-800">Chi tiết sản phẩm</h2>
              <span
                :class="product?.is_active
                  ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                  : 'bg-slate-100 text-slate-500 border-slate-200'"
                class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border"
              >
                <span :class="product?.is_active ? 'bg-emerald-500' : 'bg-slate-400'" class="w-1.5 h-1.5 rounded-full"></span>
                {{ product?.is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
              </span>
            </div>
            <button 
              @click="$emit('close')"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <div class="overflow-y-auto flex-1 px-7 py-6 space-y-5">

            <!-- Thông tin chung -->
            <div class="border border-slate-200 rounded-xl p-5">
              <h3 class="flex items-center gap-2 text-sm font-bold text-black mb-4">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Thông tin chung
              </h3>
              <div class="grid grid-cols-3 gap-5">
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID Sản phẩm</p>
                  <p class="text-sm font-mono text-slate-600">#{{ product?.id ?? '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tên sản phẩm</p>
                  <p class="text-sm font-semibold text-slate-800">{{ product?.name ?? '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Slug</p>
                  <p class="text-sm font-mono text-slate-600">{{ product?.slug ?? '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Danh mục</p>
                  <p class="text-sm font-semibold text-slate-700">
                    {{ categoryStore.categories.find(c => c.id === product?.category_id)?.name || 'Chưa phân loại' }}
                  </p>
                </div>
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Thương hiệu</p>
                  <p class="text-sm font-semibold text-slate-700">{{ product?.brand ?? '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Trạng thái</p>
                  <div class="flex items-center gap-2 mt-0.5">
                    <div
                      :class="product?.is_active ? 'bg-black justify-end' : 'bg-slate-300 justify-start'"
                      class="w-10 h-5 rounded-full flex items-center px-0.5 transition-colors"
                    >
                      <div class="w-4 h-4 bg-white rounded-full shadow-sm"></div>
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ product?.is_active ? 'Mở' : 'Tắt' }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Mô tả -->
            <div class="border border-slate-200 rounded-xl p-5">
              <h3 class="flex items-center gap-2 text-sm font-bold text-black mb-4">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Mô tả chi tiết
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                {{ product?.description || 'Chưa có mô tả.' }}
              </p>
            </div>

            <!-- Hình ảnh -->
            <div class="border border-slate-200 rounded-xl p-5">
              <h3 class="flex items-center gap-2 text-sm font-bold text-black mb-4">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Quản lý hình ảnh
              </h3>
              <div v-if="product?.images?.length" class="grid grid-cols-4 gap-3">
                <div
                  v-for="img in product.images" :key="img.id"
                  class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-50"
                >
                  <img :src="img.image_url" :alt="img.alt_text" class="w-full h-full object-cover" />
                  <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent px-2 py-2">
                    <span v-if="img.is_thumbnail" class="block text-[9px] font-bold text-white bg-black px-1.5 py-0.5 rounded uppercase mb-0.5 w-fit">Thumbnail</span>
                    <p v-if="img.alt_text" class="text-[10px] text-white font-medium truncate">{{ img.alt_text }}</p>
                  </div>
                </div>
              </div>
              <p v-else class="text-sm text-slate-400">Chưa có hình ảnh nào.</p>
            </div>

            <!-- Biến thể -->
            <div class="border border-slate-200 rounded-xl p-5">
              <h3 class="flex items-center gap-2 text-sm font-bold text-black mb-4">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Biến thể sản phẩm
              </h3>
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="bg-slate-50 border-y border-slate-200">
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Ảnh</th>
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Thuộc tính</th>
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá vốn</th>
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá bán</th>
                      <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá KM</th>
                      <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Tồn kho</th>
                      <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Kích hoạt</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr v-if="!product?.variants?.length">
                      <td colspan="8" class="py-8 text-center text-sm text-slate-400">Chưa có biến thể nào.</td>
                    </tr>
                    <tr v-for="v in product?.variants" :key="v.id" class="hover:bg-slate-50">
                      <td class="py-3 px-3">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden">
                          <img v-if="v.thumbnail" :src="v.thumbnail" class="w-full h-full object-cover" />
                          <svg v-else class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                      </td>
                      <td class="py-3 px-3 font-mono text-xs text-slate-700 font-semibold">{{ v.sku }}</td>
                      <td class="py-3 px-3 text-sm text-black font-medium">
                        {{ (v.attribute_values ?? []).map(av => av.value).join(' / ') || '—' }}
                      </td>
                      <td class="py-3 px-3 text-sm text-slate-600">{{ formatCurrency(v.cost_price) }}</td>
                      <td class="py-3 px-3 text-sm font-semibold text-slate-800">{{ formatCurrency(v.price) }}</td>
                      <td class="py-3 px-3">
                        <span v-if="v.sale_price" class="inline-block text-xs font-semibold text-pink-600 bg-pink-50 border border-pink-200 px-2 py-0.5 rounded-lg">
                          {{ formatCurrency(v.sale_price) }}
                        </span>
                        <span v-else class="text-xs text-slate-400">—</span>
                      </td>
                      <td class="py-3 px-3 text-center font-bold text-slate-800">{{ v.stock_quantity ?? 0 }}</td>
                      <td class="py-3 px-3 text-center">
                        <span
                          :class="v.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200'"
                          class="inline-block text-[10px] font-bold px-2 py-0.5 rounded border"
                        >{{ v.is_active ? 'BẬT' : 'TẮT' }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 px-7 py-4 border-t border-slate-100 shrink-0">
            <button 
              @click="$emit('close')"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Đóng</button>
            <button 
              @click="$emit('moveToUpdate')"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-black hover:bg-neutral-800 text-white font-semibold text-sm shadow-md shadow-neutral-200 transition-all active:scale-[0.98]">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Chỉnh sửa sản phẩm
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { useCategoryStore } from '@/stores/admin/categoryStore'

const categoryStore = useCategoryStore()

defineProps({
  product: {
    type: Object,
    default: null
  }
})

defineEmits(['close', 'moveToUpdate'])

// Helper: định dạng tiền tệ VND
const formatCurrency = (value) => {
  if (value === null || value === undefined) return '—'
  return Number(value).toLocaleString('vi-VN') + 'đ'
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