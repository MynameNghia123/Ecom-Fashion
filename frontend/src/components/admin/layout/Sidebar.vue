<template>
  <aside class="w-[260px] h-screen sticky top-0 flex flex-col shrink-0 select-none"
    style="background:#111111; border-right:1px solid rgba(255,255,255,0.07);">
    
    <!-- Logo Header -->
    <div class="h-16 px-6 flex items-center shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.07);">
      <span class="text-lg font-bold tracking-widest uppercase text-white">BFD Admin</span>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 admin-sidebar-nav">
      
      <!-- 1. Dashboard Link -->
      <router-link 
        to="/admin/dashboard" 
        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium rounded-lg transition-all no-underline"
        active-class="sidebar-link--active"
      >
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span>Dashboard</span>
      </router-link>



      <!-- 3. Quản lý sản phẩm Group -->
      <div v-if="authStore.hasPermission('categories', 'view') || authStore.hasPermission('products', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="4" width="16" height="16" rx="2"/>
            <path d="M4 10h16"/>
            <path d="M9 14h6"/>
          </svg>
          <span>Quản lý sản phẩm</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('categories', 'view')" to="/admin/categories"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Danh mục</router-link>
          <router-link v-if="authStore.hasPermission('products', 'view')" to="/admin/products"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Sản phẩm và biến thể</router-link>
          <router-link v-if="authStore.hasPermission('products', 'view')" to="/admin/product-attributes"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Thuộc tính sản phẩm</router-link>
        </div>
      </div>

      <!-- 3. Quản lý bán hàng Group -->
      <div v-if="authStore.hasPermission('orders', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/>
            <circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          <span>Quản lý bán hàng</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('orders', 'view')" to="/admin/orders"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Đơn hàng</router-link>
          <router-link v-if="authStore.hasPermission('orders', 'view')" to="/admin/return-requests"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Yêu cầu đổi trả</router-link>
        </div>
      </div>

      <!-- 4. Quản lý kho Group -->
      <div v-if="authStore.hasPermission('suppliers', 'view') || authStore.hasPermission('goods_receipts', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 20H4V4h16" />
            <path d="M4 9h16" />
            <path d="M4 15h16" />
          </svg>
          <span>Quản lý kho</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('suppliers', 'view')" to="/admin/suppliers"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Nhà cung cấp</router-link>
          <router-link v-if="authStore.hasPermission('goods_receipts', 'view')" to="/admin/warehouse-receipts"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Phiếu nhập kho</router-link>
        </div>
      </div>

      <!-- 5. Khách hàng & Đánh giá Group -->
      <div v-if="authStore.hasPermission('customers', 'view') || authStore.hasPermission('reviews', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>Khách hàng & Đánh giá</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('customers', 'view')" to="/admin/customers"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Danh sách khách hàng</router-link>
          <router-link v-if="authStore.hasPermission('reviews', 'view')" to="/admin/reviews"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Quản lý Đánh giá</router-link>
        </div>
      </div>

      <!-- 6. Tiếp thị Group -->
      <div v-if="authStore.hasPermission('coupons', 'view') || authStore.hasPermission('banners', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 5L6 9H2v6h4l5 4V5z"/>
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
          </svg>
          <span>Tiếp thị</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('coupons', 'view')" to="/admin/discounts"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Mã giảm giá</router-link>
          <router-link v-if="authStore.hasPermission('banners', 'view')" to="/admin/banners"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Banner quảng cáo</router-link>
        </div>
      </div>

      <!-- 7. Quản lý nội dung Group -->
      <div v-if="authStore.hasPermission('blogs', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <line x1="10" y1="9" x2="8" y2="9"/>
          </svg>
          <span>Quản lý nội dung</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('blogs', 'view')" to="/admin/blog"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Blog</router-link>
        </div>
      </div>
      <!-- 2. Thống kê Link -->
      <router-link
        to="/admin/statistics"
        class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium rounded-lg transition-all no-underline"
        active-class="sidebar-link--active"
      >
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/>
        </svg>
        <span>Thống kê</span>
      </router-link>
      <!-- 8. Phân quyền & Nhân sự Group -->
      <div v-if="authStore.hasPermission('staff', 'view') || authStore.hasPermission('roles', 'view')" class="space-y-1">
        <div class="sidebar-group-label flex items-center gap-3 px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <circle cx="12" cy="11" r="2"/>
            <path d="M12 13v3"/>
          </svg>
          <span>Phân quyền & Nhân sự</span>
        </div>
        <div class="space-y-0.5 pl-2">
          <router-link v-if="authStore.hasPermission('staff', 'view')" to="/admin/staff-accounts"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Tài khoản nhân viên</router-link>
          <router-link v-if="authStore.hasPermission('roles', 'view')" to="/admin/roles-permissions"
            class="sidebar-sublink block px-3 py-2 text-[13px] font-medium rounded-lg transition-all no-underline"
            active-class="sidebar-sublink--active"
          >Vai trò & Quyền hạn</router-link>
        </div>
      </div>

      <!-- 9. Quay lại website -->
      <router-link
        to="/"
        class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold no-underline rounded-lg transition-all mt-2"
        style="color:#f87171;"
        @mouseenter="e => e.currentTarget.style.background='rgba(248,113,113,0.1)'"
        @mouseleave="e => e.currentTarget.style.background='transparent'"
      >
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 8 8 12 12 16"/>
          <line x1="16" y1="12" x2="8" y2="12"/>
        </svg>
        <span>Quay lại website</span>
      </router-link>

    </nav>
  </aside>
</template>

<script setup>
import { useAuthStore } from '@/stores/admin/authStore'
const authStore = useAuthStore()
</script>

<style scoped>
/* ── Sidebar Scrollbar ── */
.admin-sidebar-nav::-webkit-scrollbar { width: 4px; }
.admin-sidebar-nav::-webkit-scrollbar-track { background: transparent; }
.admin-sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
.admin-sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

/* ── Group labels (section headers) ── */
.sidebar-group-label {
  color: rgba(255,255,255,0.35);
  letter-spacing: 0.1em;
}

/* ── Top-level nav link (Dashboard) ── */
.sidebar-link {
  color: rgba(255,255,255,0.6);
  border-left: 2px solid transparent;
}
.sidebar-link:hover {
  color: #ffffff;
  background: rgba(255,255,255,0.07);
}
.sidebar-link--active {
  color: #ffffff !important;
  background: rgba(255,255,255,0.1) !important;
  border-left-color: #ffffff !important;
  font-weight: 600 !important;
}

/* ── Sub-level links ── */
.sidebar-sublink {
  color: rgba(255,255,255,0.5);
  border-left: 2px solid transparent;
}
.sidebar-sublink:hover {
  color: rgba(255,255,255,0.85);
  background: rgba(255,255,255,0.06);
}
.sidebar-sublink--active {
  color: #ffffff !important;
  background: rgba(255,255,255,0.1) !important;
  border-left-color: rgba(255,255,255,0.6) !important;
  font-weight: 600 !important;
}
</style>
