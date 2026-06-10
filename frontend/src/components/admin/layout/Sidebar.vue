<template>
  <aside class="w-[260px] min-h-screen bg-white border-r border-[#eef2f7] flex flex-col shrink-0 select-none">
    
    <!-- Logo Header -->
    <div class="px-6 py-5 flex items-center gap-3 border-b border-[#f8fafc] mb-2">
      <div class="w-8 h-8 rounded-lg bg-[#0258cb] flex items-center justify-center text-white font-bold text-base shadow-sm">
        EF
      </div>
      <div>
        <h2 class="text-sm font-extrabold text-slate-800 tracking-wide uppercase leading-tight">EcomFashion</h2>
        <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest leading-none">Admin Area</span>
      </div>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="flex-1 overflow-y-auto px-4 py-3 space-y-5 admin-sidebar-nav">
      
      <!-- 1. Dashboard Link -->
      <router-link 
        to="/admin/dashboard" 
        class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent rounded-r-lg transition-all"
        active-class="active-menu-link"
      >
        <!-- Dashboard Grid Icon -->
        <svg class="w-5 h-5 text-slate-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span>Dashboard</span>
      </router-link>

      <!-- 2. Quản lý sản phẩm Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Box Archive Drawer Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="4" width="16" height="16" rx="2"/>
            <path d="M4 10h16"/>
            <path d="M9 14h6"/>
          </svg>
          <span>Quản lý sản phẩm</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/categories" class="nav-sub-link" active-class="active-sub-link">
            Danh mục
          </router-link>
          <router-link to="/admin/products" class="nav-sub-link" active-class="active-sub-link">
            Sản phẩm và biến thể
          </router-link>
          <router-link to="/admin/product-attributes" class="nav-sub-link" active-class="active-sub-link">
            Thuộc tính sản phẩm
          </router-link>
        </div>
      </div>

      <!-- 3. Quản lý bán hàng Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Shopping Cart Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/>
            <circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          <span>Quản lý bán hàng</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/orders" class="nav-sub-link" active-class="active-sub-link">
            Đơn hàng
          </router-link>
          <router-link to="/admin/return-requests" class="nav-sub-link" active-class="active-sub-link">
            Yêu cầu đổi trả
          </router-link>
        </div>
      </div>

      <!-- 4. Quản lý kho Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Warehouse Shelves Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 20H4V4h16" />
            <path d="M4 9h16" />
            <path d="M4 15h16" />
          </svg>
          <span>Quản lý kho</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/suppliers" class="nav-sub-link" active-class="active-sub-link">
            Nhà cung cấp
          </router-link>
          <router-link to="/admin/warehouse-receipts" class="nav-sub-link" active-class="active-sub-link">
            Phiếu nhập kho
          </router-link>
        </div>
      </div>

      <!-- 5. Khách hàng & Đánh giá Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Users Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>Khách hàng & Đánh giá</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/customers" class="nav-sub-link" active-class="active-sub-link">
            Danh sách khách hàng
          </router-link>
          <router-link to="/admin/reviews" class="nav-sub-link" active-class="active-sub-link">
            Quản lý Đánh giá
          </router-link>
        </div>
      </div>

      <!-- 6. Tiếp thị Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Megaphone Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 5L6 9H2v6h4l5 4V5z"/>
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
          </svg>
          <span>Tiếp thị</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/discounts" class="nav-sub-link" active-class="active-sub-link">
            Mã giảm giá
          </router-link>
          <router-link to="/admin/banners" class="nav-sub-link" active-class="active-sub-link">
            Banner quảng cáo
          </router-link>
        </div>
      </div>

      <!-- 7. Quản lý nội dung Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Document Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <line x1="10" y1="9" x2="8" y2="9"/>
          </svg>
          <span>Quản lý nội dung</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/blog" class="nav-sub-link" active-class="active-sub-link">
            Blog
          </router-link>
        </div>
      </div>

      <!-- 8. Phân quyền & Nhân sự Group -->
      <div class="space-y-1">
        <div class="flex items-center gap-3 px-3 py-2 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
          <!-- Shield Lock Icon -->
          <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <circle cx="12" cy="11" r="2"/>
            <path d="M12 13v3"/>
          </svg>
          <span>Phân quyền & Nhân sự</span>
        </div>
        <div class="space-y-0.5">
          <router-link to="/admin/staff-accounts" class="nav-sub-link" active-class="active-sub-link">
            Tài khoản nhân viên
          </router-link>
          <router-link to="/admin/roles-permissions" class="nav-sub-link" active-class="active-sub-link">
            Vai trò & Quyền hạn
          </router-link>
        </div>
      </div>

      <!-- 9. Cấu hình hệ thống Link -->
      <router-link 
        to="/admin/settings" 
        class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent rounded-r-lg transition-all"
        active-class="active-menu-link"
      >
        <!-- Gear Settings Icon -->
        <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
        <span>Cấu hình hệ thống</span>
      </router-link>

    </nav>
  </aside>
</template>

<style scoped>
/* Scrollbar styling for navigation list */
.admin-sidebar-nav::-webkit-scrollbar {
  width: 4px;
}
.admin-sidebar-nav::-webkit-scrollbar-track {
  background: transparent;
}
.admin-sidebar-nav::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.admin-sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Custom class for normal child sub-links */
.nav-sub-link {
  display: block;
  padding-left: 44px; /* Align perfectly under header text (pl-3 = 12px + icon w-5 = 20px + gap-3 = 12px => 44px) */
  padding-right: 16px;
  padding-top: 8px;
  padding-bottom: 8px;
  font-size: 13px;
  font-weight: 500;
  color: #64748b; /* text-slate-500 */
  border-left: 3px solid transparent;
  border-radius: 0 8px 8px 0; /* Right rounded corners to match active design */
  transition: all 0.15s ease-in-out;
  text-decoration: none;
}
.nav-sub-link:hover {
  color: #1e293b; /* text-slate-800 */
  background-color: #f8fafc; /* soft background hover state */
}

/* Custom class for active child sub-links */
.active-sub-link {
  background-color: #eff6ff !important; /* soft blue */
  color: #0258cb !important; /* vibrant blue */
  font-weight: 600 !important;
  border-left: 3px solid #0258cb !important;
}

/* Custom class for active main menu links (Dashboard / Settings) */
.active-menu-link {
  background-color: #eff6ff !important;
  color: #0258cb !important;
  font-weight: 600 !important;
  border-left: 3px solid #0258cb !important;
}
.active-menu-link svg {
  color: #0258cb !important;
}
</style>
