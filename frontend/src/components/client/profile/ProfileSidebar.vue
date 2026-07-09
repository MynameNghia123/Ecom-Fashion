<template>
  <aside class="w-64 border-r border-gray-100 min-h-[calc(100vh-80px)] pr-6 bg-white">
    <div class="mb-8 pl-2">
      <h2 class="text-lg font-bold uppercase tracking-wider text-gray-900">Tài khoản</h2>
      <p class="text-xs text-gray-400 mt-1">Manage your profile and orders</p>
    </div>

    <nav class="space-y-1">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        custom
        v-slot="{ navigate, isActive }"
      >
        <button
          @click="navigate"
          :class="[
            'w-full flex items-center justify-between px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 group text-left',
            isActive
              ? 'bg-gray-50 text-gray-900 border-l-4 border-gray-900 rounded-l-none pl-2.5 font-semibold'
              : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
          ]"
        >
          <div class="flex items-center space-x-3">
            <component
              :is="item.icon"
              :class="[
                'w-5 h-5 transition-colors',
                isActive ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-900'
              ]"
            />
            <span class="tracking-wide">{{ item.name }}</span>
          </div>

          <svg
            v-if="!isActive"
            class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { markRaw } from 'vue' // Thay shallowRef bằng markRaw

// Các định nghĩa Icon giữ nguyên nguyên bản của bạn...
const HomeIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`
}
const UserIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`
}
const MapIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
}
const HistoryIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
}
const TicketIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>`
}
const HeartIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`
}
const StarIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`
}
const BellIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`
}
const CogIcon = {
  template: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
}

const menuItems = [
  { name: 'TRANG CHỦ', path: '/profile', icon: markRaw(HomeIcon) },
  { name: 'Thông tin người dùng', path: '/profile/information', icon: markRaw(UserIcon) },
  { name: 'Địa chỉ', path: '/profile/address', icon: markRaw(MapIcon) },
  { name: 'Lịch sử đơn hàng', path: '/profile/order-history', icon: markRaw(HistoryIcon) },
  { name: 'Mã khuyến mãi', path: '/profile/vouchers', icon: markRaw(TicketIcon) },
  { name: 'Danh sách yêu thích', path: '/profile/wishlist', icon: markRaw(HeartIcon) },
  { name: 'Đánh giá', path: '/profile/reviews', icon: markRaw(StarIcon) },
  { name: 'Thông báo', path: '/profile/notification', icon: markRaw(BellIcon) },
  { name: 'Cài đặt', path: '/profile/settings', icon: markRaw(CogIcon) }
]
</script>

<style scoped>
span {
  font-family: inherit;
}
</style>