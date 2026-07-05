<template>
  <div :class="wrapperClass">
    <!-- Top Bar -->
    <div
      class="bg-black text-white flex justify-between items-center px-5 lg:px-20 text-[11px] font-text tracking-[0.5px] transition-all duration-500 ease-in-out overflow-hidden max-lg:flex-col max-lg:gap-2.5"
      :class="topBarClass"
    >
      <div class="flex gap-5 items-center">
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">THEO DÕI ĐƠN HÀNG</a>
        <a href="#" @click.prevent="openLoginModal" class="text-white no-underline font-semibold cursor-pointer">TÀI KHOẢN CỦA TÔI</a>
        <router-link to="/about" class="text-white no-underline font-semibold cursor-pointer">VỀ CHÚNG TÔI</router-link>
      </div>
      <div class="text-center max-lg:order-first">
        <span class="text-white font-semibold">GIẢM GIÁ MÙA HÈ CHO TẤT CẢ ĐỒ BƠI VÀ MIỄN PHÍ GIAO HÀNG QUỐC TẾ TỐC ĐỘ CAO</span>
      </div>
      <div class="flex gap-5 items-center">
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">LIÊN HỆ</a>
        <div class="flex items-center gap-1.25">
          <span class="text-white font-semibold cursor-pointer">TIẾNG VIỆT <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-1.25 mb-[2px]"></i></span>
        </div>
        <div class="flex items-center gap-1.25">
          <span class="text-white font-semibold cursor-pointer">VND <i class="border-solid border-white border-r border-b inline-block p-[2.5px] rotate-45 ml-1.25 mb-[2px]"></i></span>
        </div>
      </div>
    </div>

    <!-- Main Header / Nav -->
    <header :class="headerClass">
      <div class="flex items-center gap-[25px]">
        <button class="bg-transparent border-none cursor-pointer flex flex-col gap-1.25 p-0">
          <span class="block w-6 h-[2px] transition-all duration-300" :class="menuLineClass"></span>
          <span class="block w-[18px] h-[2px] transition-all duration-300" :class="menuLineClass"></span>
          <span class="block w-6 h-[2px] transition-all duration-300" :class="menuLineClass"></span>
        </button>
        <router-link to="/" class="logo no-underline block">
          <h1 class="font-title text-[32px] m-0 tracking-[2px] font-bold" :class="logoTextClass">Luxury</h1>
        </router-link>
      </div>

      <nav class="max-lg:hidden">
        <ul class="flex list-none m-0 p-0 gap-[30px]">
          <li class="relative">
            <router-link to="/" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">
              TRANG CHỦ <i class="border-solid border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]" :class="arrowClass"></i>
            </router-link>
          </li>
          <li class="relative">
            <router-link to="/product/AB258041NTR26" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">
              SẢN PHẨM <i class="border-solid border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]" :class="arrowClass"></i>
            </router-link>
          </li>
          <li class="relative">
            <router-link to="/" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">
              DANH MỤC <i class="border-solid border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]" :class="arrowClass"></i>
            </router-link>
          </li>
          <li class="relative">
            <router-link to="/" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">
              NỮ <i class="border-solid border-r border-b inline-block p-[2.5px] rotate-45 ml-2 mb-[2px]" :class="arrowClass"></i>
            </router-link>
          </li>
          <li>
            <router-link to="/" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">NAM</router-link>
          </li>
          <li>
            <router-link to="/blog" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">TIN TỨC</router-link>
          </li>
          <li>
            <router-link to="/contact" class="no-underline font-text text-[13px] font-semibold flex items-center uppercase transition-all duration-300" :class="navLinkClass">LIÊN HỆ</router-link>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-5">
        <button class="bg-transparent border-none cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" :class="actionBtnClass" aria-label="Tìm kiếm">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
        <button class="bg-transparent border-none cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" :class="actionBtnClass" aria-label="Yêu thích">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <span class="absolute -top-0.5 -right-1.25 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
        </button>
        <button @click="openLoginModal" class="bg-transparent border-none cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" :class="actionBtnClass" aria-label="Tài khoản">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </button>
        <button class="bg-transparent border-none cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" :class="actionBtnClass" aria-label="So sánh">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 21l6-6M9 8l-5 5-4-4M9 21v-6H3"></path></svg>
        </button>
        <button @click="openMiniCart" class="bg-transparent border-none cursor-pointer relative flex items-center justify-center p-1.25 transition-transform duration-300 hover:scale-110" :class="actionBtnClass" aria-label="Giỏ hàng">
           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
          <span class="absolute -top-0.5 -right-1.25 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
        </button>
      </div>
    </header>

    <!-- Auth Modal -->
    <AuthModal 
      :is-open="isAuthModalOpen" 
      :initial-mode="authModalMode" 
      @close="isAuthModalOpen = false" 
    />

    <!-- Mini Cart Drawer -->
    <MiniCart
      :is-open="isMiniCartOpen"
      @close="isMiniCartOpen = false"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import AuthModal from '@/views/client/auth/AuthModal.vue'
import MiniCart from '@/components/client/cart/MiniCart.vue'

const isAuthModalOpen = ref(false)
const authModalMode = ref('login')
const isMiniCartOpen = ref(false)

const openLoginModal = () => {
  authModalMode.value = 'login'
  isAuthModalOpen.value = true
}

const openRegisterModal = () => {
  authModalMode.value = 'register'
  isAuthModalOpen.value = true
}

const openMiniCart = () => {
  isMiniCartOpen.value = true
}

const props = defineProps({
  isTransparent: {
    type: Boolean,
    default: false
  }
})

const scrolled = ref(false)

const handleScroll = () => {
  scrolled.value = window.scrollY > 80
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

/**
 * Hai chiến lược layout:
 *
 * A) Trang chủ (isTransparent = true):
 *    - Wrapper luôn FIXED top-0 → overlay lên toàn bộ hero, không chen vào layout
 *    - Topbar: có màu bg-black (scrolls với wrapper vì fixed)
 *    - Khi scroll > 80px: topbar collapse, nav chuyển trắng/frosted
 *
 * B) Các trang khác (isTransparent = false):
 *    - Wrapper: normal flow (relative)
 *    - Topbar: cuộn bình thường
 *    - Nav header: sticky top-0 → bám khi user lướt qua topbar
 */

// Wrapper
const wrapperClass = computed(() =>
  props.isTransparent
    ? 'fixed top-0 left-0 right-0 w-full z-[200]'    // homepage: luôn fixed overlay hero
    : 'sticky top-0 left-0 right-0 w-full z-[200] bg-white' // trang thường: sticky ở top
)

// Topbar: collapse mượt khi đã scroll
const topBarClass = computed(() => {
  if (scrolled.value) {
    // Topbar slides up và biến mất
    return 'max-h-0 py-0 opacity-0 pointer-events-none'
  }
  return 'max-h-[60px] py-2.5'
})

// Nav trắng khi: trang thường | hoặc homepage đã scroll
const isLight = computed(() => !props.isTransparent || scrolled.value)

// Nav header class
const headerClass = computed(() => {
  const base = 'flex justify-between items-center py-[25px] px-5 lg:px-20 w-full transition-all duration-500'
  if (!props.isTransparent) {
    // Trang thường: luôn sticky, trắng
    return `${base} sticky top-0 bg-white text-black border-b border-gray-200 shadow-sm`
  }
  if (scrolled.value) {
    // Homepage đã scroll: trắng frosted (wrapper vẫn fixed)
    return `${base} bg-white/95 backdrop-blur-md text-black border-b border-gray-200 shadow-sm`
  }
  // Homepage chưa scroll: trong suốt
  return `${base} bg-transparent text-white`
})

const menuLineClass = computed(() => isLight.value ? 'bg-black' : 'bg-white')
const logoTextClass = computed(() => isLight.value ? 'text-black' : 'text-white')
const navLinkClass = computed(() => isLight.value ? 'text-gray-800 hover:text-black' : 'text-white hover:opacity-75')
const arrowClass = computed(() => isLight.value ? 'border-black' : 'border-white')
const actionBtnClass = computed(() => isLight.value ? 'text-black' : 'text-white')
</script>
