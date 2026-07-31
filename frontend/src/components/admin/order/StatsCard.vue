<template>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
    <div
      v-for="s in stats"
      :key="s.label"
      class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow duration-200"
    >
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ s.label }}</p>
        <p class="text-3xl font-bold text-slate-800">{{ s.value }}</p>
      </div>
      <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" :class="s.bgBg">
        <component :is="s.icon" class="w-6 h-6" :class="s.iconColor" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'
import { storeToRefs } from 'pinia'
import { useOrderStore } from '@/stores/admin/orderStore'

const orderStore = useOrderStore()
const { orders, meta } = storeToRefs(orderStore)

// Professional Feather SVG icons for Stats Cards
const IconTotal = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('path', { d: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z' }),
  h('polyline', { points: '14 2 14 8 20 8' }),
  h('line', { x1: '16', y1: '13', x2: '8', y2: '13' }),
  h('line', { x1: '16', y1: '17', x2: '8', y2: '17' }),
  h('polyline', { points: '10 9 9 9 8 9' })
])
const IconPending = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('circle', { cx: '12', cy: '12', r: '10' }),
  h('polyline', { points: '12 6 12 12 16 14' })
])
const IconProcessing = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('path', { d: 'M21 12a9 9 0 1 1-6.219-8.56' })
])
const IconCompleted = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('path', { d: 'M22 11.08V12a10 10 0 1 1-5.93-9.14' }),
  h('polyline', { points: '22 4 12 14.01 9 11.01' })
])
const IconCancelled = h('svg', { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
  h('circle', { cx: '12', cy: '12', r: '10' }),
  h('line', { x1: '15', y1: '9', x2: '9', y2: '15' }),
  h('line', { x1: '9', y1: '9', x2: '15', y2: '15' })
])

// Computed Stats
const stats = computed(() => {
  const total = meta.value?.total || orders.value?.length || 0
  const pending = orders.value?.filter(o => o.status === 'pending').length || 0
  const processing = orders.value?.filter(o => o.status === 'processing').length || 0
  const completed = orders.value?.filter(o => o.status === 'completed').length || 0
  const cancelled = orders.value?.filter(o => o.status === 'cancelled').length || 0

  return [
    { label: 'Tổng đơn hàng', value: total, icon: IconTotal, iconColor: 'text-blue-500', bgBg: 'bg-blue-50' },
    { label: 'Chờ xác nhận', value: pending, icon: IconPending, iconColor: 'text-amber-500', bgBg: 'bg-amber-50' },
    { label: 'Đang xử lý', value: processing, icon: IconProcessing, iconColor: 'text-blue-500', bgBg: 'bg-blue-50' },
    { label: 'Hoàn thành', value: completed, icon: IconCompleted, iconColor: 'text-emerald-500', bgBg: 'bg-emerald-50' },
    { label: 'Đã hủy', value: cancelled, icon: IconCancelled, iconColor: 'text-red-400', bgBg: 'bg-red-50' },
  ]
})
</script>