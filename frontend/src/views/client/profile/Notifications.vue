<template>
  <div class="space-y-8 animate-fade-in">

    <!-- Header -->
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title">Thông báo</h1>
        <p class="text-sm text-neutral-400 mt-2 font-text">Cập nhật trạng thái đơn hàng và các thông tin mới nhất.</p>
      </div>
      <button v-if="store.notifications.length > 0" @click="handleMarkAllRead"
        class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors bg-transparent border-none cursor-pointer flex-shrink-0 mt-2">
        Đánh dấu tất cả đã đọc
      </button>
    </div>

    <!-- Loading -->
    <div v-if="store.loading" class="py-16 text-center">
      <svg class="animate-spin w-6 h-6 mx-auto text-neutral-400" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
      </svg>
    </div>

    <!-- Empty -->
    <div v-else-if="store.notifications.length === 0" class="py-16 text-center border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 01-3.46 0"/>
      </svg>
      <p class="text-sm text-neutral-400">Không có thông báo nào.</p>
    </div>

    <!-- Notification list -->
    <div v-else class="divide-y divide-neutral-100 border border-neutral-100">
      <div
        v-for="notif in store.notifications"
        :key="notif.id"
        @click="handleClick(notif)"
        :class="['flex items-start gap-4 px-5 py-4 transition-colors cursor-pointer', !notif.is_read ? 'bg-neutral-50 hover:bg-neutral-100' : 'bg-white hover:bg-neutral-50']"
      >
        <!-- Icon -->
        <div :class="['w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5', iconBg(notif.type)]">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="iconColor(notif.type)">
            <path v-if="notif.type === 'order_placed'" d="M20 12V22H4V12"/>
            <path v-else-if="notif.type === 'order_status_updated'" d="M20 6L9 17l-5-5"/>
            <path v-else-if="notif.type === 'return_request_updated'" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <circle v-else cx="12" cy="12" r="4"/>
          </svg>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <p class="text-sm font-semibold text-neutral-900 leading-snug">{{ notif.title }}</p>
            <span v-if="!notif.is_read" class="w-2 h-2 rounded-full bg-neutral-900 flex-shrink-0 mt-1.5"></span>
          </div>
          <p class="text-xs text-neutral-500 mt-0.5 leading-relaxed">{{ notif.content }}</p>
          <p class="text-[11px] text-neutral-400 mt-1.5">{{ formatTime(notif.created_at) }}</p>
        </div>

        <!-- Arrow -->
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-neutral-300 flex-shrink-0 mt-2">
          <path d="M9 18l6-6-6-6"/>
        </svg>
      </div>
    </div>

    <!-- Load more -->
    <div v-if="store.meta.current_page < store.meta.last_page" class="text-center">
      <button
        @click="loadMore"
        :disabled="store.loading"
        class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:text-neutral-900 transition-colors bg-transparent border-none cursor-pointer"
      >
        Xem thêm
      </button>
    </div>

  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotificationStore } from '@/stores/client/notificationStore'

const router = useRouter()
const store = useNotificationStore()

onMounted(() => {
  store.fetchNotifications(1)
})

const handleClick = async (notif) => {
  // Đánh dấu đã đọc nếu chưa
  if (!notif.is_read) {
    await store.markAsRead(notif.id)
  }

  // Điều hướng theo type của thông báo
  const type = notif.type || ''

  if (type === 'order_placed' || type === 'order_status_updated') {
    // Tìm order_code từ nội dung (ví dụ: "ORD-XXXXXXXX")
    const match = notif.content?.match(/ORD-[A-Z0-9]+/) || notif.title?.match(/ORD-[A-Z0-9]+/)
    if (match) {
      router.push({ name: 'CheckoutSuccess', query: { code: match[0] } })
    } else {
      router.push('/profile/order-history')
    }
  } else if (type === 'return_request_updated') {
    router.push('/profile/returns')
  } else {
    // Fallback: trang lịch sử đơn hàng
    router.push('/profile/order-history')
  }
}

const handleMarkAllRead = () => {
  store.markAllAsRead()
}

const loadMore = () => {
  if (store.meta.current_page < store.meta.last_page) {
    store.fetchNotifications(store.meta.current_page + 1)
  }
}

const iconBg = (notif) => {
  const type = notif.type || ''
  const title = (notif.title || '').toLowerCase()
  if (type === 'order_placed') return 'bg-blue-50'
  if (type === 'order_status_updated') return 'bg-emerald-50'
  if (type === 'return_request_updated') {
    if (title.includes('từ chối') || title.includes('hủy')) return 'bg-rose-50'
    if (title.includes('duyệt') || title.includes('hoàn tiền')) return 'bg-emerald-50'
    return 'bg-amber-50'
  }
  return 'bg-neutral-100'
}

const iconColor = (notif) => {
  const type = notif.type || ''
  const title = (notif.title || '').toLowerCase()
  if (type === 'order_placed') return 'text-blue-600'
  if (type === 'order_status_updated') return 'text-emerald-600'
  if (type === 'return_request_updated') {
    if (title.includes('từ chối') || title.includes('hủy')) return 'text-rose-600'
    if (title.includes('duyệt') || title.includes('hoàn tiền')) return 'text-emerald-600'
    return 'text-amber-600'
  }
  return 'text-neutral-600'
}

const formatTime = (dateStr) => {
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