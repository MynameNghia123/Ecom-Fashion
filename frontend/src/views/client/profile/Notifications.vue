<template>
  <div class="space-y-8 animate-fade-in">

    <!-- Header -->
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title">Thông báo</h1>
        <p class="text-sm text-neutral-400 mt-2 font-text">Cập nhật trạng thái đơn hàng và các thông tin mới nhất.</p>
      </div>
      <button v-if="notifications.length > 0" @click="markAllRead"
        class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors bg-transparent border-none cursor-pointer flex-shrink-0 mt-2">
        Đánh dấu tất cả đã đọc
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="py-16 text-center">
      <svg class="animate-spin w-6 h-6 mx-auto text-neutral-400" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
      </svg>
    </div>

    <!-- Empty -->
    <div v-else-if="notifications.length === 0" class="py-16 text-center border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 01-3.46 0"/>
      </svg>
      <p class="text-sm text-neutral-400">Không có thông báo nào.</p>
    </div>

    <!-- Notification list -->
    <div v-else class="divide-y divide-neutral-100 border border-neutral-100">
      <div
        v-for="notif in notifications"
        :key="notif.id"
        @click="markRead(notif)"
        :class="['flex items-start gap-4 px-5 py-4 transition-colors cursor-pointer', !notif.read ? 'bg-neutral-50 hover:bg-neutral-100' : 'bg-white hover:bg-neutral-50']"
      >
        <!-- Icon -->
        <div :class="['w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5', iconBg(notif.status)]">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="iconColor(notif.status)">
            <path v-if="notif.status === 'completed'" d="M20 6L9 17l-5-5"/>
            <path v-else-if="notif.status === 'cancelled'" d="M18 6L6 18M6 6l12 12"/>
            <path v-else-if="notif.status === 'shipping'" d="M5 12h14M12 5l7 7-7 7"/>
            <circle v-else cx="12" cy="12" r="4"/>
          </svg>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <p class="text-sm font-semibold text-neutral-900 leading-snug">{{ notif.title }}</p>
            <span v-if="!notif.read" class="w-2 h-2 rounded-full bg-neutral-900 flex-shrink-0 mt-1.5"></span>
          </div>
          <p class="text-xs text-neutral-500 mt-0.5 leading-relaxed">{{ notif.body }}</p>
          <p class="text-[11px] text-neutral-400 mt-1.5">{{ notif.timeAgo }}</p>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { orderService } from '@/services/client/orderService'

const orders  = ref([])
const loading = ref(false)
const readIds = ref(JSON.parse(localStorage.getItem('notif_read') || '[]'))

onMounted(async () => {
  loading.value = true
  try {
    const res = await orderService.getOrders()
    if (res.data?.success) orders.value = res.data.data
  } finally {
    loading.value = false
  }
})

const STATUS_NOTIF = {
  pending:   { title: 'Đơn hàng đã được tiếp nhận',   body: '(code) - Đang chờ xử lý.' },
  confirmed: { title: 'Đơn hàng đã xác nhận',         body: '(code) - Đang chuẩn bị hàng.' },
  shipping:  { title: 'Đơn hàng đang được vận chuyển', body: '(code) - Hàng đang trên đường.' },
  completed: { title: 'Giao hàng thành công!',           body: '(code) - Bạn đã nhận được hàng.' },
  cancelled: { title: 'Đơn hàng đã bị hủy',            body: '(code) - Liên hệ hỗ trợ nếu cần.' },
}

const notifications = computed(() =>
  orders.value.map(o => {
    const tpl = STATUS_NOTIF[o.status] || STATUS_NOTIF.pending
    return {
      id:      o.id,
      status:  o.status,
      title:   tpl.title,
      body:    tpl.body.replace('(code)', o.order_code),
      timeAgo: timeAgo(o.updated_at || o.created_at),
      read:    readIds.value.includes(o.id),
    }
  })
)

const markRead = (notif) => {
  if (!readIds.value.includes(notif.id)) {
    readIds.value.push(notif.id)
    localStorage.setItem('notif_read', JSON.stringify(readIds.value))
  }
}

const markAllRead = () => {
  readIds.value = orders.value.map(o => o.id)
  localStorage.setItem('notif_read', JSON.stringify(readIds.value))
}

const iconBg = (s) => ({
  completed: 'bg-emerald-50',
  cancelled: 'bg-rose-50',
  shipping:  'bg-blue-50',
  confirmed: 'bg-blue-50',
  pending:   'bg-amber-50',
}[s] || 'bg-neutral-100')

const iconColor = (s) => ({
  completed: 'text-emerald-600',
  cancelled: 'text-rose-600',
  shipping:  'text-blue-600',
  confirmed: 'text-blue-600',
  pending:   'text-amber-600',
}[s] || 'text-neutral-600')

const timeAgo = (dateStr) => {
  if (!dateStr) return ''
  const diff = Date.now() - new Date(dateStr).getTime()
  const mins  = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days  = Math.floor(diff / 86400000)
  if (mins < 1)   return 'Vừa xong'
  if (mins < 60)  return `${mins} phút trước`
  if (hours < 24) return `${hours} giờ trước`
  return `${days} ngày trước`
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
</style>