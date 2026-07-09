<template>
  <aside class="w-[260px] h-screen sticky top-0 bg-white border-r border-[#eef2f7] flex flex-col shrink-0 select-none">
    
    <!-- Logo Header -->
    <div class="h-16 px-6 flex items-center border-b border-[#eef2f7] shrink-0">
      <span class="text-lg font-bold text-[#0258cb] tracking-wide">BFD Admin</span>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="flex-1 overflow-y-auto px-4 py-3 space-y-5 admin-sidebar-nav">
      
      <!-- 1. Dashboard Link -->
      <router-link 
        to="/admin/dashboard" 
        class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent rounded-r-lg transition-all no-underline"
        active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb] [&_svg]:!text-[#0258cb]"
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
          <router-link to="/admin/categories"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent rounded-r-2 transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Danh mục
          </router-link>
          <router-link to="/admin/products"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent rounded-r-2 transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Sản phẩm và biến thể
          </router-link>
          <router-link to="/admin/product-attributes"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent rounded-r-2 transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/orders"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Đơn hàng
          </router-link>
          <router-link to="/admin/return-requests"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/suppliers"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Nhà cung cấp
          </router-link>
          <router-link to="/admin/warehouse-receipts"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/customers"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Danh sách khách hàng
          </router-link>
          <router-link to="/admin/reviews"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/discounts"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Mã giảm giá
          </router-link>
          <router-link to="/admin/banners"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/blog"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
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
          <router-link to="/admin/staff-accounts"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Tài khoản nhân viên
          </router-link>
          <router-link to="/admin/roles-permissions"
            class="block pl-[44px] pr-4 py-2 text-[13px] font-medium text-slate-500 border-l-[3px] border-transparent transition-all duration-150 no-underline hover:text-slate-800 hover:bg-slate-50 rounded-r-lg"
            active-class="!bg-[#eff6ff] !text-[#0258cb] !font-semibold !border-l-[#0258cb]"
          >
            Vai trò & Quyền hạn
          </router-link>
        </div>
      </div>

      <!-- 9. Quay lại website -->
      <router-link
        to="/"
        class="flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold text-rose-500 hover:text-rose-600 hover:bg-rose-50 border-l-[3px] border-transparent hover:border-l-rose-400 rounded-r-lg transition-all no-underline"
      >
        <!-- Arrow Left Circle Icon (red) -->
        <svg class="w-5 h-5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 8 8 12 12 16"/>
          <line x1="16" y1="12" x2="8" y2="12"/>
        </svg>
        <span>Quay lại website</span>
      </router-link>

    </nav>
  </aside>
</template>

<style scoped>
/* Scrollbar pseudo-selectors — cannot be expressed in Tailwind utilities */
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
</style>
