<template>
  <Transition name="fade">
    <div
      v-if="show && item"
      class="fixed inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center p-4 z-50"
      @click.self="$emit('close')"
    >
      <Transition name="pop" appear>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto overflow-x-hidden">

          <!-- Header -->
          <div class="flex items-start justify-between px-6 pt-6 pb-4">
            <div>
              <h2 class="text-lg font-bold text-slate-900">Chi tiết yêu cầu trả hàng</h2>
              <p class="text-xs text-slate-400 mt-0.5">Mã yêu cầu: #RR-{{ item.id }}</p>
            </div>
            <button
              @click="$emit('close')"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Order Reference Banner -->
          <div class="mx-6 mb-5 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                  <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Tham chiếu đơn hàng</p>
                <p class="text-sm font-bold text-[#0258cb]">#{{ item.order_code || item.order_id }}</p>
              </div>
            </div>
            <div class="text-right shrink-0">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border" :class="statusStyle(item.status)">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                {{ statusLabel(item.status) }}
              </span>
              <p class="text-[10px] text-slate-400 mt-1">Tạo lúc: {{ item.created_at || 'N/A' }}</p>
            </div>
          </div>

          <!-- Body -->
          <div class="px-6 pb-5 space-y-5">

            <!-- Two columns: Customer + Reason -->
            <div class="grid grid-cols-2 gap-5">

              <!-- Left column -->
              <div class="space-y-4">
                <!-- Customer Info -->
                <div>
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Thông tin khách hàng</p>
                  <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#0258cb] text-sm font-bold flex items-center justify-center shrink-0">
                      {{ initials(item.customer_name) }}
                    </div>
                    <div>
                      <p class="text-sm font-bold text-slate-800 leading-tight">{{ item.customer_name || 'Khách hàng' }}</p>
                      <p class="text-xs text-slate-400">{{ item.customer_email || '' }}</p>
                    </div>
                  </div>
                </div>

                <!-- Financial Details -->
                <div>
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Chi tiết tài chính</p>
                  <div class="bg-slate-50 border border-slate-100 rounded-xl overflow-hidden">
                    <div class="flex justify-between items-center px-3.5 py-2.5 border-b border-slate-100">
                      <span class="text-xs text-slate-500">Số tiền yêu cầu hoàn</span>
                      <span class="text-sm font-bold text-slate-800">{{ formatCurrency(item.refund_amount) }}</span>
                    </div>
                    <div class="flex justify-between items-center px-3.5 py-2.5">
                      <span class="text-xs text-slate-500">Nhân viên xử lý</span>
                      <span class="text-xs font-semibold text-slate-700">{{ item.processed_by_staff || '—' }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right column: Return Reason + Timestamps -->
              <div class="space-y-4">
                <!-- Return Reason -->
                <div>
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Lý do trả hàng</p>
                  <div class="bg-rose-50 border border-rose-100 rounded-xl px-3.5 py-3">
                    <p class="text-sm font-bold text-rose-700 leading-tight mb-1">{{ shortTitle(item.reason) }}</p>
                    <p class="text-xs text-rose-600/80 leading-relaxed">{{ item.reason }}</p>
                  </div>
                </div>

                <!-- Timestamps -->
                <div>
                  <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Thời gian</p>
                  <div class="space-y-1.5">
                    <div class="flex justify-between">
                      <span class="text-xs text-slate-400">Khởi tạo yêu cầu:</span>
                      <span class="text-xs font-medium text-slate-700">{{ item.created_at || '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-xs text-slate-400">Cập nhật lần cuối:</span>
                      <span class="text-xs font-medium text-slate-700">{{ item.updated_at || '—' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Evidence Images -->
            <div v-if="item.evidence_images && item.evidence_images.length">
              <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                  Hình ảnh bằng chứng ({{ item.evidence_images.length }})
                </p>
                <button type="button" class="inline-flex items-center gap-1 text-xs text-[#0258cb] hover:underline font-medium">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                  </svg>
                  tải tất cả
                </button>
              </div>
              <div class="grid grid-cols-3 gap-2">
                <div
                  v-for="(img, i) in item.evidence_images"
                  :key="i"
                  class="aspect-square bg-slate-100 border border-slate-200 rounded-xl overflow-hidden"
                >
                  <img
                    v-if="typeof img === 'string' && img.startsWith('http')"
                    :src="img"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                      <circle cx="8.5" cy="8.5" r="1.5"/>
                      <polyline points="21 15 16 10 5 21"/>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/70">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition-colors"
            >
              Đóng
            </button>
            <div v-if="item.status === 'pending'" class="flex gap-2.5">
              <button
                @click="$emit('reject', item)"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Từ chối
              </button>
              <button
                @click="$emit('approve', item)"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white transition-colors shadow-sm active:scale-[0.98]"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
                Chấp nhận hoàn tiền
              </button>
            </div>
          </div>

        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  show: { type: Boolean, default: false },
  item: { type: Object, default: null },
})

defineEmits(['close', 'approve', 'reject'])

function formatCurrency(v) {
  if (!v && v !== 0) return '0 đ'
  return new Intl.NumberFormat('vi-VN').format(v) + 'đ'
}

function statusLabel(status) {
  const map = { pending: 'Chờ xử lý', approved: 'Đã chấp nhận', rejected: 'Đã từ chối', completed: 'Hoàn thành' }
  return map[status] || status
}

function statusStyle(status) {
  const map = {
    pending:   'bg-amber-50 text-amber-700 border-amber-200',
    approved:  'bg-emerald-50 text-emerald-700 border-emerald-200',
    rejected:  'bg-rose-50 text-rose-600 border-rose-200',
    completed: 'bg-blue-50 text-blue-700 border-blue-200',
  }
  return map[status] || 'bg-slate-100 text-slate-600 border-slate-200'
}

function initials(name) {
  if (!name) return 'K'
  return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
}

// Extract first sentence/short phrase as bold title inside reason box
function shortTitle(reason) {
  if (!reason) return ''
  const firstSentence = reason.split(/[.\n]/)[0]
  return firstSentence.length > 40 ? firstSentence.slice(0, 40) + '…' : firstSentence
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
.pop-leave-active { transition: all 0.12s ease; }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: scale(0.95) translateY(10px); }
</style>
