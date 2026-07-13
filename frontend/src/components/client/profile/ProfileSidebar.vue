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
        v-slot="{ navigate, isActive, isExactActive }"
      >
        <button
          @click="navigate"
          :class="[
            'w-full flex items-center justify-between px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 group text-left cursor-pointer',
            (item.path === '/profile' ? isExactActive : isActive)
              ? 'bg-gray-50 text-gray-900 border-l-4 border-gray-900 rounded-l-none pl-2.5 font-semibold'
              : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
          ]"
        >
          <div class="flex items-center">
            <span class="tracking-wide">{{ item.name }}</span>
          </div>

          <svg
            v-if="!(item.path === '/profile' ? isExactActive : isActive)"
            class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </router-link>

      <!-- Đăng xuất -->
      <button
        @click="handleLogout"
        class="w-full flex items-center justify-between px-3 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50/50 hover:text-red-700 transition-all duration-200 group text-left cursor-pointer border-none bg-transparent"
      >
        <div class="flex items-center">
          <span class="tracking-wide">Đăng xuất</span>
        </div>
      </button>
    </nav>
  </aside>
</template>

<script setup>
import { useClientAuthStore } from '@/stores/client/authStore'
import { useRouter } from 'vue-router'

const authStore = useClientAuthStore()
const router = useRouter()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
}

const menuItems = [
  { name: 'TRANG CHỦ', path: '/profile' },
  { name: 'Thông tin người dùng', path: '/profile/information' },
  { name: 'Địa chỉ', path: '/profile/address' },
  { name: 'Lịch sử đơn hàng', path: '/profile/order-history' },
  { name: 'Mã khuyến mãi', path: '/profile/vouchers' },
  { name: 'Danh sách yêu thích', path: '/profile/wishlist' },
  { name: 'Đánh giá', path: '/profile/reviews' },
  { name: 'Thông báo', path: '/profile/notification' },
  { name: 'Cài đặt', path: '/profile/settings' }
]
</script>

<style scoped>
span {
  font-family: inherit;
}
</style>