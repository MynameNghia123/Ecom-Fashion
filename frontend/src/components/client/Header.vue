<template>
  <div :class="wrapperClass">
    <!-- Top Bar -->
    <div
      class="bg-black text-white flex justify-between items-center px-5 lg:px-20 text-[11px] font-text tracking-[0.5px] transition-all duration-500 ease-in-out overflow-hidden max-lg:flex-col max-lg:gap-2.5"
      :class="topBarClass"
    >
      <div class="flex gap-5 items-center">
        <a href="#" class="text-white no-underline font-semibold cursor-pointer">THEO DÕI ĐƠN HÀNG</a>
        <a href="#" @click.prevent="handleAccountClick" class="text-white no-underline font-semibold cursor-pointer">TÀI KHOẢN CỦA TÔI</a>
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
          <!-- Trang chủ -->
          <li class="relative">
            <router-link to="/" class="nav-link" :class="navLinkClass">TRANG CHỦ</router-link>
          </li>

          <!-- Sản phẩm -->
          <li class="relative">
            <router-link to="/category" class="nav-link" :class="navLinkClass">SẢN PHẨM</router-link>
          </li>

          <!-- Danh mục có dropdown (dynamic từ DB) -->
          <li
            v-for="cat in rootCategories"
            :key="cat.id"
            class="relative nav-item-with-dropdown"
            @mouseenter="openDropdown(cat.id)"
            @mouseleave="closeDropdown"
          >
            <!-- Nav link chính -->
            <button
              class="nav-link nav-link-btn"
              :class="navLinkClass"
              @click="goToCategory(cat)"
            >
              {{ cat.name.toUpperCase() }}
              <svg
                v-if="cat.children && cat.children.length"
                width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                class="ml-1.5 transition-transform duration-300 inline-block"
                :class="activeDropdown === cat.id ? 'rotate-180' : ''"
              >
                <polyline points="6 9 12 15 18 9"/>
              </svg>
            </button>

            <!-- Dropdown Panel -->
            <Transition name="dropdown">
              <div
                v-if="activeDropdown === cat.id && cat.children && cat.children.length"
                class="dropdown-panel"
                :class="isLight ? 'dropdown-light' : 'dropdown-dark'"
              >
                <div class="dropdown-inner">
                  <!-- Các sub-category (cột) -->
                  <div
                    v-for="sub in cat.children"
                    :key="sub.id"
                    class="dropdown-col"
                  >
                    <!-- Sub-category title -->
                    <button
                      class="dropdown-col-title"
                      @click="goToCategory(sub)"
                    >{{ sub.name }}</button>

                    <!-- Sub-sub-categories nếu có -->
                    <ul v-if="sub.children && sub.children.length" class="dropdown-links">
                      <li v-for="leaf in sub.children" :key="leaf.id">
                        <button class="dropdown-link" @click="goToCategory(leaf)">
                          {{ leaf.name }}
                        </button>
                      </li>
                    </ul>

                    <!-- Nếu không có cháu thì link thẳng đến sub -->
                    <ul v-else class="dropdown-links">
                      <li>
                        <button class="dropdown-link" @click="goToCategory(sub)">
                          Xem tất cả {{ sub.name }}
                        </button>
                      </li>
                    </ul>
                  </div>

                  <!-- CTA "Xem tất cả" -->
                  <div class="dropdown-cta">
                    <button class="dropdown-cta-btn" @click="goToCategory(cat)">
                      Xem tất cả {{ cat.name }}
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </Transition>
          </li>

          <!-- Tin tức -->
          <li class="relative">
            <router-link to="/blog" class="nav-link" :class="navLinkClass">TIN TỨC</router-link>
          </li>

          <!-- Liên hệ -->
          <li class="relative">
            <router-link to="/contact" class="nav-link" :class="navLinkClass">LIÊN HỆ</router-link>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-5">
        <button @click="isSearchOpen = true" class="icon-btn" :class="actionBtnClass" aria-label="Tìm kiếm">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
        <button
          @click="router.push('/profile/wishlist')"
          class="icon-btn relative"
          :class="actionBtnClass"
          aria-label="Yêu thích"
        >
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <span v-if="wishlistStore.count > 0" class="badge bg-rose-500">{{ wishlistStore.count }}</span>
        </button>
        <button @click="handleAccountClick" class="icon-btn" :class="actionBtnClass" aria-label="Tài khoản">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </button>
        <button @click="openMiniCart" class="icon-btn relative" :class="actionBtnClass" aria-label="Giỏ hàng">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
          <span class="badge bg-black">2</span>
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

    <!-- Search Overlay -->
    <SearchOverlay
      :is-open="isSearchOpen"
      @close="isSearchOpen = false"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useClientAuthStore } from '@/stores/client/authStore'
import { useWishlistStore } from '@/stores/client/wishlistStore'
import { productService } from '@/services/client/productService'
import AuthModal from '@/views/client/auth/AuthModal.vue'
import MiniCart from '@/components/client/cart/MiniCart.vue'
import SearchOverlay from '@/components/client/SearchOverlay.vue'

const router = useRouter()
const authStore = useClientAuthStore()
const wishlistStore = useWishlistStore()
const isAuthModalOpen = ref(false)
const authModalMode = ref('login')
const isMiniCartOpen = ref(false)
const isSearchOpen = ref(false)
const activeDropdown = ref(null)
const rootCategories = ref([])

let dropdownTimer = null

// ── Category Dropdown ─────────────────────────────────────────
const loadCategoryTree = async () => {
  try {
    const res = await productService.getCategoryTree()
    if (res.data?.success) {
      rootCategories.value = res.data.data
    }
  } catch (err) {
    console.error('Lỗi tải cây danh mục:', err)
  }
}

const openDropdown = (id) => {
  clearTimeout(dropdownTimer)
  activeDropdown.value = id
}

const closeDropdown = () => {
  dropdownTimer = setTimeout(() => {
    activeDropdown.value = null
  }, 120)
}

const goToCategory = (cat) => {
  activeDropdown.value = null
  router.push({ path: '/category', query: { category_id: cat.id } })
}

// ── Auth & Cart ───────────────────────────────────────────────
const openLoginModal = () => {
  authModalMode.value = 'login'
  isAuthModalOpen.value = true
}

const handleAccountClick = () => {
  if (authStore.isAuthenticated) {
    router.push('/profile')
  } else {
    openLoginModal()
  }
}

const openMiniCart = () => {
  isMiniCartOpen.value = true
}

// ── Props & Scroll ────────────────────────────────────────────
const props = defineProps({
  isTransparent: { type: Boolean, default: false }
})

const scrolled = ref(false)
const handleScroll = () => { scrolled.value = window.scrollY > 80 }

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  loadCategoryTree()
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

// ── Computed Classes ──────────────────────────────────────────
const wrapperClass = computed(() =>
  props.isTransparent
    ? 'fixed top-0 left-0 right-0 w-full z-[200]'
    : 'sticky top-0 left-0 right-0 w-full z-[200] bg-white'
)

const topBarClass = computed(() =>
  scrolled.value ? 'max-h-0 py-0 opacity-0 pointer-events-none' : 'max-h-[60px] py-2.5'
)

const isLight = computed(() => !props.isTransparent || scrolled.value)

const headerClass = computed(() => {
  const base = 'flex justify-between items-center py-[25px] px-5 lg:px-20 w-full transition-all duration-500'
  if (!props.isTransparent) return `${base} sticky top-0 bg-white text-black border-b border-gray-200 shadow-sm`
  if (scrolled.value) return `${base} bg-white/95 backdrop-blur-md text-black border-b border-gray-200 shadow-sm`
  return `${base} bg-transparent text-white`
})

const menuLineClass = computed(() => isLight.value ? 'bg-black' : 'bg-white')
const logoTextClass = computed(() => isLight.value ? 'text-black' : 'text-white')
const navLinkClass  = computed(() => isLight.value ? 'text-gray-800 hover:text-black' : 'text-white hover:opacity-75')
const actionBtnClass = computed(() => isLight.value ? 'text-black' : 'text-white')
</script>

<style scoped>
/* ── Nav link base ── */
.nav-link {
  text-decoration: none;
  font-family: var(--font-text, 'Montserrat', sans-serif);
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  text-transform: uppercase;
  transition: all 0.3s;
  white-space: nowrap;
  letter-spacing: 0.3px;
}
.nav-link-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
}

/* ── Icon buttons ── */
.icon-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 5px;
  transition: transform 0.3s;
}
.icon-btn:hover { transform: scale(1.12); }

/* ── Badge ── */
.badge {
  position: absolute;
  top: -2px;
  right: -5px;
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  border-radius: 999px;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}

/* ── Nav item hover underline ── */
.nav-item-with-dropdown { display: flex; flex-direction: column; }
.nav-item-with-dropdown > .nav-link,
.nav-item-with-dropdown > .nav-link-btn {
  position: relative;
}
.nav-item-with-dropdown > .nav-link::after,
.nav-item-with-dropdown > .nav-link-btn::after {
  content: '';
  position: absolute;
  bottom: -3px;
  left: 0;
  width: 0;
  height: 2px;
  background: currentColor;
  transition: width 0.25s ease;
}
.nav-item-with-dropdown:hover > .nav-link::after,
.nav-item-with-dropdown:hover > .nav-link-btn::after {
  width: 100%;
}

/* ── Dropdown Panel ── */
.dropdown-panel {
  position: absolute;
  top: calc(100% + 18px);
  left: 50%;
  transform: translateX(-50%);
  min-width: 340px;
  max-width: 560px;
  border-radius: 18px;
  overflow: hidden;
  z-index: 999;
  box-shadow: 0 24px 64px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.08);
}

.dropdown-light {
  background: #fff;
  border: 1px solid #f0f0f0;
}

.dropdown-dark {
  background: rgba(20, 20, 20, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(20px);
}

/* Arrow triangle indicator */
.dropdown-panel::before {
  content: '';
  position: absolute;
  top: -7px;
  left: 50%;
  transform: translateX(-50%);
  width: 14px;
  height: 14px;
  background: inherit;
  border-top: 1px solid rgba(0,0,0,0.06);
  border-left: 1px solid rgba(0,0,0,0.06);
  transform: translateX(-50%) rotate(45deg);
}

.dropdown-inner {
  padding: 20px 24px 16px;
  display: flex;
  gap: 28px;
  flex-wrap: wrap;
  position: relative;
}

/* ── Dropdown columns ── */
.dropdown-col { flex: 1; min-width: 120px; }

.dropdown-col-title {
  background: transparent;
  border: none;
  cursor: pointer;
  font-family: var(--font-text, 'Montserrat', sans-serif);
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  padding: 0 0 8px;
  margin: 0 0 8px;
  border-bottom: 1px solid;
  width: 100%;
  text-align: left;
  transition: opacity 0.2s;
}

.dropdown-light .dropdown-col-title {
  color: #111;
  border-color: #eee;
}
.dropdown-dark .dropdown-col-title {
  color: #fff;
  border-color: rgba(255,255,255,0.15);
}

.dropdown-col-title:hover { opacity: 0.7; }

/* ── Dropdown sub links ── */
.dropdown-links {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.dropdown-link {
  background: transparent;
  border: none;
  cursor: pointer;
  font-family: var(--font-text, 'Montserrat', sans-serif);
  font-size: 13px;
  font-weight: 400;
  padding: 5px 0;
  text-align: left;
  width: 100%;
  transition: all 0.18s;
  border-radius: 6px;
}
.dropdown-light .dropdown-link { color: #444; }
.dropdown-dark .dropdown-link { color: rgba(255,255,255,0.72); }

.dropdown-light .dropdown-link:hover { color: #000; padding-left: 6px; }
.dropdown-dark .dropdown-link:hover { color: #fff; padding-left: 6px; }

/* ── CTA View all ── */
.dropdown-cta {
  width: 100%;
  padding-top: 12px;
  border-top: 1px solid;
  margin-top: 4px;
}
.dropdown-light .dropdown-cta { border-color: #eee; }
.dropdown-dark .dropdown-cta { border-color: rgba(255,255,255,0.1); }

.dropdown-cta-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: var(--font-text, 'Montserrat', sans-serif);
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 0;
  transition: gap 0.2s;
}
.dropdown-light .dropdown-cta-btn { color: #111; }
.dropdown-dark .dropdown-cta-btn { color: #fff; }
.dropdown-cta-btn:hover { gap: 12px; }

/* ── Dropdown Transition ── */
.dropdown-enter-active {
  transition: opacity 0.2s ease, transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.dropdown-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from {
  opacity: 0;
  transform: translateX(-50%) translateY(-8px);
}
.dropdown-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(-6px);
}
</style>
