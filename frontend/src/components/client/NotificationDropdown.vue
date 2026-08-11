<template>
  <div class="fixed inset-0 z-[998]" v-if="isOpen" @click="close"></div>
  
  <Transition name="dropdown">
    <div 
      v-if="isOpen" 
      class="absolute top-[calc(100%+10px)] right-0 md:right-16 w-[360px] bg-white rounded-2xl shadow-xl border border-gray-100 z-[999] overflow-hidden"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-bold text-gray-900 m-0">Thông báo</h3>
        <button 
          v-if="store.unreadCount > 0"
          @click="store.markAllAsRead" 
          class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-transparent border-none cursor-pointer"
        >
          Đánh dấu đã đọc tất cả
        </button>
      </div>

      <!-- Content -->
      <div class="max-h-[400px] overflow-y-auto custom-scrollbar relative">
        <div v-if="store.loading && store.notifications.length === 0" class="flex justify-center items-center py-8">
          <div class="w-6 h-6 border-2 border-gray-200 border-t-black rounded-full animate-spin"></div>
        </div>

        <div v-else-if="store.notifications.length === 0" class="flex flex-col items-center justify-center py-12 px-4 text-center">
          <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-500 m-0">Bạn chưa có thông báo nào</p>
        </div>

        <template v-else>
          <div 
            v-for="notification in store.notifications" 
            :key="notification.id"
            @click="handleNotificationClick(notification)"
            class="p-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 cursor-pointer transition-colors relative"
            :class="{'bg-blue-50/30': !notification.is_read}"
          >
            <!-- Unread Dot -->
            <div v-if="!notification.is_read" class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-blue-500"></div>
            
            <div class="flex gap-3" :class="{'pl-2': !notification.is_read}">
              <!-- Icon based on type -->
              <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center" :class="getIconConfig(notification.type).bgClass">
                <svg class="w-5 h-5" :class="getIconConfig(notification.type).textClass" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="getIconConfig(notification.type).svg"></svg>
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 m-0 mb-1 leading-tight">{{ notification.title }}</p>
                <p class="text-[13px] text-gray-600 m-0 leading-snug line-clamp-2">{{ notification.content }}</p>
                <p class="text-[11px] text-gray-400 font-medium m-0 mt-1.5">{{ formatTime(notification.created_at) }}</p>
              </div>
            </div>
          </div>
          
          <!-- Load more -->
          <div v-if="store.meta.current_page < store.meta.last_page" class="p-3 text-center border-t border-gray-100">
            <button 
              @click.stop="loadMore" 
              class="text-xs font-bold text-gray-500 hover:text-black bg-transparent border-none cursor-pointer px-4 py-2"
              :disabled="store.loading"
            >
              <span v-if="store.loading">Đang tải...</span>
              <span v-else>Xem thêm</span>
            </button>
          </div>
        </template>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotificationStore } from '@/stores/client/notificationStore'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const store = useNotificationStore()
const router = useRouter()

onMounted(() => {
  store.fetchUnreadCount()
  if (props.isOpen && store.notifications.length === 0) {
    store.fetchNotifications()
  }
})

const close = () => {
  emit('close')
}

const loadMore = () => {
  if (store.meta.current_page < store.meta.last_page) {
    store.fetchNotifications(store.meta.current_page + 1)
  }
}

const handleNotificationClick = async (notification) => {
  if (!notification.is_read) {
    await store.markAsRead(notification.id)
  }
  
  // Navigation based on type
  if (notification.type.includes('order')) {
    router.push('/profile/orders')
  } else if (notification.type.includes('return')) {
    router.push('/profile/returns')
  }
  
  close()
}

const formatTime = (dateString) => {
  if (!dateString) return ''
  try {
    const date = new Date(dateString)
    const now = new Date()
    const diffInSeconds = Math.floor((now - date) / 1000)
    
    if (diffInSeconds < 60) {
      return 'vừa xong'
    }
    
    const rtf = new Intl.RelativeTimeFormat('vi', { numeric: 'auto' })
    
    const diffInMinutes = Math.floor(diffInSeconds / 60)
    if (diffInMinutes < 60) {
      return rtf.format(-diffInMinutes, 'minute')
    }
    
    const diffInHours = Math.floor(diffInMinutes / 60)
    if (diffInHours < 24) {
      return rtf.format(-diffInHours, 'hour')
    }
    
    const diffInDays = Math.floor(diffInHours / 24)
    if (diffInDays < 30) {
      return rtf.format(-diffInDays, 'day')
    }
    
    const diffInMonths = Math.floor(diffInDays / 30)
    if (diffInMonths < 12) {
      return rtf.format(-diffInMonths, 'month')
    }
    
    const diffInYears = Math.floor(diffInDays / 365)
    return rtf.format(-diffInYears, 'year')
  } catch (e) {
    return ''
  }
}

const getIconConfig = (type) => {
  switch (type) {
    case 'order_placed':
      return {
        bgClass: 'bg-emerald-100',
        textClass: 'text-emerald-600',
        svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'
      }
    case 'order_status_updated':
      return {
        bgClass: 'bg-blue-100',
        textClass: 'text-blue-600',
        svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
      }
    case 'return_request_updated':
      return {
        bgClass: 'bg-orange-100',
        textClass: 'text-orange-600',
        svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />'
      }
    default:
      return {
        bgClass: 'bg-gray-100',
        textClass: 'text-gray-600',
        svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'
      }
  }
}
</script>

<style scoped>
.dropdown-enter-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.dropdown-leave-active {
  transition: all 0.2s ease-in;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.98);
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e5e7eb;
  border-radius: 4px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
  background: #d1d5db;
}
</style>
