<template>
  <div class="space-y-6">

    <!-- ===== Page Header ===== -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý Sản phẩm</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý danh mục hàng hóa, giá bán và trạng thái tồn kho của hệ thống</p>
      </div>
      <button
        @click="openAddModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm sản phẩm mới
      </button>
    </div>

    <!-- ===== Stats Cards ===== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tổng sản phẩm</p>
        <p class="text-3xl font-bold text-slate-800">1,284</p>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Đang hoạt động</p>
        <div class="flex items-end gap-2">
          <p class="text-3xl font-bold text-slate-800">1,150</p>
          <span class="mb-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">89.5%</span>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Hết hàng</p>
        <p class="text-3xl font-bold text-red-500">12</p>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Mới trong tháng</p>
        <p class="text-3xl font-bold text-[#0258cb]">+45</p>
      </div>
    </div>

    <!-- ===== Table Card ===== -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <!-- Search -->
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo tên sản phẩm, thương hiệu..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
          />
        </div>
        <!-- Category filter -->
        <div class="relative">
          <select v-model="filterCategory" class="appearance-none pl-4 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer">
            <option value="">Tất cả danh mục</option>
            <option v-for="c in categoryOptions" :key="c" :value="c">{{ c }}</option>
          </select>
          <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>
        <!-- Status filter -->
        <div class="relative">
          <select v-model="filterStatus" class="appearance-none pl-4 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer">
            <option value="">Trạng thái</option>
            <option value="active">Đang hoạt động</option>
            <option value="inactive">Ngừng hoạt động</option>
          </select>
          <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </span>
        </div>
        <!-- Advanced Filter btn -->
        <button class="ml-auto inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all duration-150">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
          Lọc nâng cao
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
              <th class="py-3.5 px-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-[80px]">ID</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Thương hiệu</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Danh mục</th>
              <th class="py-3.5 px-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Tồn Kho tổng</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
              <th class="py-3.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày tạo</th>
              <th class="py-3.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-[110px]">Thao tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="product in paginatedProducts" :key="product.id" class="hover:bg-blue-50/40 transition-colors duration-100">
              <td class="py-4 px-5 font-mono text-xs text-slate-500">#{{ product.id }}</td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200">
                    <img v-if="product.thumbnail" :src="product.thumbnail" :alt="product.name" class="w-full h-full object-cover" />
                    <svg v-else class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-800 leading-tight">{{ product.name }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 font-mono">SKU: {{ product.sku }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 text-slate-700 font-medium text-sm">{{ product.brand }}</td>
              <td class="py-4 px-4">
                <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-lg">{{ product.category }}</span>
              </td>
              <td class="py-4 px-4 text-center">
                <span :class="product.stock === 0 ? 'text-red-500 font-bold' : 'text-slate-800 font-semibold'">{{ product.stock }}</span>
              </td>
              <td class="py-4 px-4">
                <span :class="product.status === 'active'
                  ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                  : 'bg-slate-100 text-slate-500 border-slate-200'"
                  class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="product.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ product.status === 'active' ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-4 px-4 text-slate-500 text-sm">{{ product.createdAt }}</td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end gap-1">
                  <button @click="openViewModal(product)" class="p-2 rounded-lg text-slate-400 hover:text-[#0258cb] hover:bg-blue-50 transition-all" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button @click="openEditModal(product)" class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button @click="triggerDelete(product)" class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all" title="Xóa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="paginatedProducts.length === 0">
              <td colspan="8" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <svg class="w-12 h-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                  <p class="text-sm font-medium">Không tìm thấy sản phẩm nào</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-5 py-4 border-t border-slate-100">
        <Pagination
          v-model:currentPage="currentPage"
          v-model:perPage="pageSize"
          :total="filteredProducts.length"
          :lastPage="totalPages"
        />
      </div>
    </div>

    <!-- ============================= VIEW DETAIL MODAL ============================= -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showViewModal" class="fixed inset-0 z-[9998] flex items-center justify-center p-4" @click.self="showViewModal = false">
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[1080px] max-h-[90vh] flex flex-col animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-5 pb-4 border-b border-slate-100 shrink-0">
              <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Chi tiết sản phẩm</h2>
                <span :class="viewTarget?.status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200'"
                  class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border">
                  <span class="w-1.5 h-1.5 rounded-full" :class="viewTarget?.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ viewTarget?.status === 'active' ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                </span>
              </div>
              <button @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Body -->
            <div class="overflow-y-auto flex-1 px-7 py-6 space-y-5">

              <!-- Section: Thông tin chung -->
              <div class="border border-slate-200 rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-bold text-[#0258cb] mb-4">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  Thông tin chung
                </h3>
                <div class="grid grid-cols-3 gap-5">
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tên sản phẩm</p>
                    <p class="text-sm font-semibold text-slate-800">{{ viewTarget?.name }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Slug</p>
                    <p class="text-sm font-mono text-slate-600">{{ viewTarget?.slug }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Danh mục</p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.category }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Thương hiệu</p>
                    <p class="text-sm font-semibold text-slate-700">{{ viewTarget?.brand }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Trạng thái hoạt động</p>
                    <div class="flex items-center gap-2 mt-0.5">
                      <div class="w-10 h-5 rounded-full flex items-center px-0.5 transition-colors" :class="viewTarget?.status === 'active' ? 'bg-[#0258cb] justify-end' : 'bg-slate-200 justify-start'">
                        <div class="w-4 h-4 bg-white rounded-full shadow-sm"></div>
                      </div>
                      <span class="text-sm font-medium text-slate-700">{{ viewTarget?.status === 'active' ? 'Mở' : 'Tắt' }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Section: Mô tả chi tiết -->
              <div class="border border-slate-200 rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-bold text-[#0258cb] mb-4">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                  Mô tả chi tiết
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ viewTarget?.description || 'Chưa có mô tả.' }}</p>
              </div>

              <!-- Section: Hình ảnh -->
              <div class="border border-slate-200 rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-bold text-[#0258cb] mb-4">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                  Quản lý hình ảnh
                </h3>
                <div v-if="viewTarget?.images?.length" class="grid grid-cols-4 gap-3">
                  <div v-for="(img, idx) in viewTarget.images" :key="idx" class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-50">
                    <img :src="img.url" :alt="img.alt" class="w-full h-full object-cover" />
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent px-2 py-2">
                      <span v-if="img.isThumbnail" class="block text-[9px] font-bold text-white bg-[#0258cb] px-1.5 py-0.5 rounded uppercase mb-0.5 w-fit">Thumbnail</span>
                      <span v-else class="block text-[9px] font-bold text-white/70 bg-white/20 px-1.5 py-0.5 rounded uppercase mb-0.5 w-fit">Image</span>
                      <p class="text-[10px] text-white font-medium">#{{ idx + 1 }} {{ img.alt }}</p>
                    </div>
                  </div>
                </div>
                <p v-else class="text-sm text-slate-400">Chưa có hình ảnh nào.</p>
              </div>

              <!-- Section: Biến thể -->
              <div class="border border-slate-200 rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-bold text-[#0258cb] mb-4">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                  Biến thể sản phẩm
                </h3>
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-slate-50 border-y border-slate-200">
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Ảnh</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Tên biến thể</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá vốn</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá bán</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá KM</th>
                        <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Tồn kho</th>
                        <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Kích hoạt</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                      <tr v-for="v in viewTarget?.variants" :key="v.sku" class="hover:bg-slate-50">
                        <td class="py-3 px-3">
                          <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden">
                            <img v-if="v.image" :src="v.image" :alt="v.name" class="w-full h-full object-cover" />
                            <svg v-else class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                          </div>
                        </td>
                        <td class="py-3 px-3 font-mono text-xs text-slate-700 font-semibold">{{ v.sku }}</td>
                        <td class="py-3 px-3 text-sm text-[#0258cb] font-medium">{{ v.name }}</td>
                        <td class="py-3 px-3 text-sm text-slate-600">{{ formatPrice(v.costPrice) }}</td>
                        <td class="py-3 px-3 text-sm font-semibold text-slate-800">{{ formatPrice(v.salePrice) }}</td>
                        <td class="py-3 px-3">
                          <span v-if="v.promotionPrice" class="inline-block text-xs font-semibold text-pink-600 bg-pink-50 border border-pink-200 px-2 py-0.5 rounded-lg">
                            {{ formatPrice(v.promotionPrice) }}
                          </span>
                          <span v-else class="text-slate-400 text-sm">—</span>
                        </td>
                        <td class="py-3 px-3 text-center font-bold" :class="v.stock < 20 ? 'text-red-500' : 'text-slate-800'">{{ v.stock }}</td>
                        <td class="py-3 px-3 text-center">
                          <span :class="v.active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200'"
                            class="inline-block text-[10px] font-bold px-2 py-0.5 rounded border">
                            {{ v.active ? 'BẬT' : 'TẮT' }}
                          </span>
                        </td>
                      </tr>
                      <tr v-if="!viewTarget?.variants?.length">
                        <td colspan="7" class="py-6 text-center text-sm text-slate-400">Chưa có biến thể nào.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-4 border-t border-slate-100 shrink-0">
              <button @click="showViewModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Đóng</button>
              <button @click="openEditModal(viewTarget); showViewModal = false" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all active:scale-[0.98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Chỉnh sửa sản phẩm
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ============================= ADD / EDIT FORM MODAL ============================= -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showFormModal" class="fixed inset-0 z-[9998] flex items-center justify-center p-4" @click.self="closeFormModal">
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[1080px] max-h-[92vh] flex flex-col animate-modal-in">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-7 pt-5 pb-4 border-b border-slate-100 shrink-0">
              <h2 class="text-base font-bold text-slate-800">{{ modalMode === 'add' ? 'Thêm sản phẩm mới' : 'Chỉnh sửa sản phẩm' }}</h2>
              <button @click="closeFormModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto flex-1 px-7 py-6 space-y-6">

              <!-- Section 1: Thông tin chung -->
              <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-sm font-bold text-slate-700">Thông tin chung</h3>
                  <!-- Status toggle -->
                  <div class="flex items-center gap-2.5">
                    <span class="text-sm font-semibold text-slate-600">Trạng thái hoạt động</span>
                    <button type="button" @click="form.status = form.status === 'active' ? 'inactive' : 'active'"
                      class="w-11 h-6 rounded-full flex items-center px-0.5 transition-colors duration-200 focus:outline-none"
                      :class="form.status === 'active' ? 'bg-[#0258cb] justify-end' : 'bg-slate-200 justify-start'">
                      <div class="w-5 h-5 bg-white rounded-full shadow-md transition-all"></div>
                    </button>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input v-model="form.name" @input="autoSlug" type="text" placeholder="Nhập tên sản phẩm..."
                      class="w-full px-3.5 py-2.5 text-sm border rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                      :class="formErrors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-slate-200'" />
                    <p v-if="formErrors.name" class="text-xs text-red-500 mt-1">{{ formErrors.name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                    <input v-model="form.slug" type="text" placeholder="ten-san-pham-slug"
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all font-mono" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Danh mục <span class="text-red-500">*</span></label>
                    <div class="relative">
                      <select v-model="form.category" class="w-full appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer"
                        :class="formErrors.category ? 'border-red-400' : 'border-slate-200'">
                        <option value="">Chọn danh mục</option>
                        <option v-for="c in categoryOptions" :key="c" :value="c">{{ c }}</option>
                      </select>
                      <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <p v-if="formErrors.category" class="text-xs text-red-500 mt-1">{{ formErrors.category }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Thương hiệu</label>
                    <input v-model="form.brand" type="text" placeholder="Nhập thương hiệu"
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all" />
                  </div>
                </div>

                <!-- Description -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả chi tiết</label>
                  <!-- Mini toolbar -->
                  <div class="border border-slate-200 rounded-t-xl bg-slate-50 px-3 py-2 flex items-center gap-1 border-b-0">
                    <button type="button" class="px-2 py-1 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded transition-colors">B</button>
                    <button type="button" class="px-2 py-1 text-xs italic text-slate-600 hover:bg-slate-200 rounded transition-colors">I</button>
                    <button type="button" class="px-2 py-1 text-xs underline text-slate-600 hover:bg-slate-200 rounded transition-colors">U</button>
                    <div class="w-px h-4 bg-slate-300 mx-1"></div>
                    <button type="button" class="px-2 py-1 text-xs text-slate-600 hover:bg-slate-200 rounded transition-colors">≡</button>
                    <button type="button" class="px-2 py-1 text-xs text-slate-600 hover:bg-slate-200 rounded transition-colors">≣</button>
                  </div>
                  <textarea v-model="form.description" rows="5" placeholder="Nhập mô tả sản phẩm..."
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-b-xl text-slate-700 placeholder-slate-400 bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all resize-none leading-relaxed rounded-t-none"></textarea>
                </div>
              </div>

              <!-- Section 2: Hình ảnh -->
              <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-700">Quản lý hình ảnh sản phẩm</h3>
                <!-- Upload zone -->
                <div class="border-2 border-dashed border-slate-300 hover:border-[#0258cb] rounded-xl p-8 flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors group bg-blue-50/30 hover:bg-blue-50/60">
                  <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                      <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                    </svg>
                  </div>
                  <p class="text-sm font-semibold text-[#0258cb]">Kéo thả ảnh vào đây hoặc nhấp để tải lên</p>
                  <p class="text-xs text-slate-400">Hỗ trợ JPG, PNG. Kích thước tối đa 5MB. Tối đa 5 ảnh.</p>
                </div>
                <!-- Image list -->
                <div class="space-y-2">
                  <div v-for="(img, idx) in form.images" :key="idx" class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50">
                    <div class="w-12 h-12 rounded-lg bg-slate-200 border border-slate-300 flex items-center justify-center overflow-hidden shrink-0">
                      <img v-if="img.preview" :src="img.preview" class="w-full h-full object-cover" />
                      <svg v-else class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div class="flex-1 grid grid-cols-3 gap-3 items-center">
                      <div class="col-span-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Văn bản thay thế (Alt Text)</p>
                        <input v-model="img.alt" type="text" :placeholder="img.preview ? 'Mô tả ảnh...' : 'Chưa có ảnh tải lên...'" :disabled="!img.preview"
                          class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none disabled:text-slate-400 disabled:cursor-not-allowed transition-all" />
                      </div>
                      <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Thứ tự</p>
                        <input v-model.number="img.order" type="number" min="1" :disabled="!img.preview"
                          class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none disabled:opacity-50 transition-all text-center" />
                      </div>
                      <div class="flex items-center gap-2">
                        <div>
                          <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Ảnh đại diện</p>
                          <button type="button" :disabled="!img.preview" @click="setThumbnail(idx)"
                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all disabled:opacity-40"
                            :class="img.isThumbnail ? 'border-[#0258cb] bg-[#0258cb]' : 'border-slate-300 hover:border-[#0258cb]'">
                            <div v-if="img.isThumbnail" class="w-2.5 h-2.5 rounded-full bg-white"></div>
                          </button>
                        </div>
                        <button type="button" :disabled="!img.preview" @click="removeImage(idx)"
                          class="ml-auto p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 disabled:opacity-40 transition-colors">
                          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Manual upload button -->
                <div class="flex items-center gap-3">
                  <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Tải file lên
                  </button>
                  <span class="text-xs text-slate-400">Chấp nhận PDF, DOC, DOCX (Tối đa 10MB)</span>
                </div>
              </div>

              <!-- Section 3: Biến thể -->
              <div class="border border-slate-200 rounded-xl p-5 space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-sm font-bold text-slate-700">Phân loại hàng (Biến thể)</h3>
                  <button type="button" @click="addAttributeGroup"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl border border-slate-200 transition-all">
                    + Thêm nhóm phân loại
                  </button>
                </div>

                <!-- Attribute groups -->
                <div v-for="(group, gIdx) in form.attributeGroups" :key="gIdx" class="border border-slate-200 rounded-xl p-4 space-y-3">
                  <div class="flex items-center gap-2">
                    <input v-model="group.name" type="text" placeholder="Tên nhóm (VD: Màu sắc, Size...)"
                      class="px-3 py-1.5 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-2 focus:ring-[#0258cb]/10 focus:outline-none transition-all font-semibold text-slate-700 w-40" />
                    <div class="flex flex-wrap items-center gap-1.5 flex-1">
                      <span v-for="(val, vIdx) in group.values" :key="vIdx"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#0258cb] border border-blue-200">
                        {{ val }}
                        <button type="button" @click="removeAttributeValue(gIdx, vIdx)" class="hover:text-red-500 transition-colors">×</button>
                      </span>
                      <button type="button" @click="addAttributeValue(gIdx)"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold text-slate-500 border border-dashed border-slate-300 hover:border-[#0258cb] hover:text-[#0258cb] transition-all">
                        + Thêm giá trị
                      </button>
                    </div>
                    <button type="button" @click="removeAttributeGroup(gIdx)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </div>
                </div>

                <!-- Variants table -->
                <div v-if="form.variants.length > 0" class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-slate-50 border-y border-slate-200">
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase w-[80px]">Tên biến thể</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Hình ảnh</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá vốn (đ)</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá bán (đ)</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá KM (đ)</th>
                        <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Tồn kho</th>
                        <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Hoạt động</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                      <tr v-for="(v, vIdx) in form.variants" :key="vIdx" class="hover:bg-slate-50">
                        <td class="py-2.5 px-3 text-sm font-semibold text-slate-700">{{ v.name }}</td>
                        <td class="py-2.5 px-3">
                          <div class="w-9 h-9 rounded-lg border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center cursor-pointer hover:border-[#0258cb] transition-colors overflow-hidden">
                            <img v-if="v.image" :src="v.image" class="w-full h-full object-cover" />
                            <svg v-else class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                          </div>
                        </td>
                        <td class="py-2.5 px-3">
                          <input v-model="v.sku" type="text" :placeholder="`VD: ${form.name.substring(0,3).toUpperCase() || 'SKU'}-${v.name.toUpperCase()}-01`"
                            class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all font-mono" />
                        </td>
                        <td class="py-2.5 px-3">
                          <input v-model.number="v.costPrice" type="number" min="0" placeholder="0"
                            class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all text-right" />
                        </td>
                        <td class="py-2.5 px-3">
                          <input v-model.number="v.salePrice" type="number" min="0" placeholder="0"
                            class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all text-right" />
                        </td>
                        <td class="py-2.5 px-3">
                          <input v-model.number="v.promotionPrice" type="number" min="0" placeholder="0"
                            class="w-full px-2.5 py-1.5 text-xs border border-pink-200 rounded-lg bg-pink-50 focus:border-pink-400 focus:outline-none transition-all text-right placeholder-pink-300" />
                        </td>
                        <td class="py-2.5 px-3">
                          <input v-model.number="v.stock" type="number" min="0" placeholder="0"
                            class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all text-center" />
                        </td>
                        <td class="py-2.5 px-3 text-center">
                          <button type="button" @click="v.active = !v.active"
                            class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors"
                            :class="v.active ? 'bg-[#0258cb] border-[#0258cb]' : 'border-slate-300 bg-white hover:border-[#0258cb]'">
                            <svg v-if="v.active" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <p v-else class="text-xs text-slate-400 text-center py-4">Thêm nhóm phân loại và giá trị để tự động tạo biến thể.</p>
              </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-4 border-t border-slate-100 shrink-0">
              <button @click="closeFormModal" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy</button>
              <button @click="submitForm" class="px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all active:scale-[0.98]">
                {{ modalMode === 'add' ? 'Lưu sản phẩm' : 'Lưu thay đổi' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ============================= CONFIRM DELETE ============================= -->
    <ConfirmDeleteModal
      :show="showDeleteModal"
      title="Xóa sản phẩm"
      message="Bạn có chắc chắn muốn xóa sản phẩm"
      :itemName="deleteTarget?.name"
      messageSuffix="không? Tất cả biến thể và dữ liệu liên quan sẽ bị xóa vĩnh viễn."
      confirmLabel="Xóa sản phẩm"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue'
import ConfirmDeleteModal from '@/components/admin/ConfirmDeleteModal.vue'
import Pagination from '@/components/admin/Pagination.vue'
import { useProductStore } from '@/stores/admin/productStore'
import { useCategoryStore } from '@/stores/admin/categoryStore'

// ======== Stores ========
const productStore = useProductStore()
const categoryStore = useCategoryStore()

// ======== Helpers ========
const formatPrice = (n) => n ? Number(n).toLocaleString('vi-VN') + 'đ' : '0đ'
const generateSlug = (t) => t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-')

/**
 * Map API product → UI format
 * API: { id, name, slug, brand, is_active, category, product_images, product_variants }
 * UI: { id, name, slug, brand, status, category, stock, thumbnail, images, variants }
 */
const mapProduct = (p) => ({
  id:          p.id,
  name:        p.name,
  slug:        p.slug,
  brand:       p.brand ?? '',
  description: p.description ?? '',
  thumbnail:   p.thumbnail ?? '',
  status:      p.is_active ? 'active' : 'inactive',
  // category có thể là object {id, name} hoặc string tùy API response
  category:    p.category?.name ?? p.category ?? '',
  category_id: p.category_id ?? p.category?.id ?? null,
  createdAt:   p.created_at ? new Date(p.created_at).toLocaleDateString('vi-VN') : '',
  // Tổng tồn kho từ các biến thể
  stock: (p.product_variants ?? []).reduce((s, v) => s + (v.stock_quantity ?? 0), 0),
  images: (p.product_images ?? []).map(img => ({
    id:          img.id,
    url:         img.image_url,
    alt:         img.alt_text ?? '',
    isThumbnail: !!img.is_thumbnail,
    order:       img.display_order ?? 1,
  })),
  variants: (p.product_variants ?? []).map(v => ({
    id:             v.id,
    sku:            v.sku,
    name:           (v.attribute_values ?? []).map(a => a.value).join(' / '),
    costPrice:      v.cost_price ?? 0,
    salePrice:      v.price ?? 0,
    promotionPrice: v.sale_price ?? 0,
    stock:          v.stock_quantity ?? 0,
    active:         !!v.is_active,
    image:          v.thumbnail ?? '',
    attribute_values: v.attribute_values ?? [],
  })),
})

// ======== Computed từ store ========
const products = computed(() => (productStore.products ?? []).map(mapProduct))
const meta     = computed(() => productStore.meta)
const loading  = computed(() => productStore.loading)

// ======== Category options (dùng cho filter & form) ========
const categoryOptions = computed(() => categoryStore.categories ?? [])

// ======== Filter & Search (client-side nhanh, server-side cho pagination) ========
const searchQuery    = ref('')
const filterCategory = ref('')
const filterStatus   = ref('')

// Debounce search để tránh gọi API liên tục
let searchTimer = null
watch(searchQuery, (val) => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    currentPage.value = 1
    loadProducts()
  }, 400)
})

const filteredProducts = computed(() => products.value.filter(p => {
  const q = searchQuery.value.toLowerCase()
  const matchSearch  = !q || p.name.toLowerCase().includes(q) || (p.brand ?? '').toLowerCase().includes(q)
  const matchCat     = !filterCategory.value || p.category_id == filterCategory.value
  const matchStatus  = !filterStatus.value || p.status === filterStatus.value
  return matchSearch && matchCat && matchStatus
}))

// ======== Pagination (server-side) ========
const currentPage = ref(1)
const pageSize    = ref(10)

const totalPages       = computed(() => meta.value.last_page ?? 1)
const paginatedProducts = computed(() => filteredProducts.value)

watch(currentPage, () => loadProducts())
watch(pageSize,    () => { currentPage.value = 1; loadProducts() })

// ======== Load dữ liệu ========
async function loadProducts() {
  await productStore.fetchProducts({
    page:     currentPage.value,
    per_page: pageSize.value,
    search:   searchQuery.value || undefined,
  })
}

onMounted(async () => {
  await Promise.all([
    productStore.initialFetch(),                         // chỉ fetch nếu chưa có data
    categoryStore.initialFetch({ per_page: 100 }),       // lấy tất cả để hiển thị filter
  ])
})

// ======== View Modal ========
const showViewModal = ref(false)
const viewTarget    = ref(null)
const openViewModal = (p) => { viewTarget.value = p; showViewModal.value = true }

// ======== Form Modal ========
const showFormModal = ref(false)
const modalMode     = ref('add')
const submitting    = ref(false)
const serverErrors  = ref({})

const defaultForm = () => ({
  id: null, name: '', slug: '', category: '', category_id: null,
  brand: '', description: '', status: 'active', thumbnail: '',
  images: [{ id: null, preview: '', alt: '', order: 1, isThumbnail: true }],
  attributeGroups: [],
  variants: []
})

const form       = reactive(defaultForm())
const formErrors = reactive({ name: '', category: '' })

const resetForm = () => {
  Object.assign(form, defaultForm())
  formErrors.name = ''; formErrors.category = ''
  serverErrors.value = {}
}

const openAddModal = () => {
  resetForm()
  modalMode.value = 'add'
  showFormModal.value = true
}

const openEditModal = (p) => {
  resetForm()
  form.id          = p.id
  form.name        = p.name
  form.slug        = p.slug
  form.category_id = p.category_id
  form.category    = p.category
  form.brand       = p.brand
  form.description = p.description
  form.status      = p.status
  form.thumbnail   = p.thumbnail
  form.images      = p.images?.length
    ? p.images.map(img => ({ id: img.id, preview: img.url, alt: img.alt, order: img.order, isThumbnail: img.isThumbnail }))
    : [{ id: null, preview: '', alt: '', order: 1, isThumbnail: true }]
  form.variants = (p.variants ?? []).map(v => ({ ...v }))
  form.attributeGroups = []
  modalMode.value = 'edit'
  showFormModal.value = true
}

const closeFormModal = () => { showFormModal.value = false }
const autoSlug       = () => { if (modalMode.value === 'add') form.slug = generateSlug(form.name) }

// Image helpers
const setThumbnail = (idx) => form.images.forEach((img, i) => img.isThumbnail = i === idx)
const removeImage  = (idx) => form.images.splice(idx, 1)

// Attribute group helpers
const addAttributeGroup    = () => form.attributeGroups.push({ name: '', values: [] })
const removeAttributeGroup = (gIdx) => { form.attributeGroups.splice(gIdx, 1); regenerateVariants() }
const addAttributeValue    = (gIdx) => {
  const val = prompt('Nhập giá trị (VD: Đỏ, Size 40...)')
  if (val?.trim()) { form.attributeGroups[gIdx].values.push(val.trim()); regenerateVariants() }
}
const removeAttributeValue = (gIdx, vIdx) => { form.attributeGroups[gIdx].values.splice(vIdx, 1); regenerateVariants() }

// Auto-generate variants from attribute groups
const cartesian = (arrays) => arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...x, y])), [[]])

const regenerateVariants = () => {
  const groups = form.attributeGroups.filter(g => g.values.length > 0)
  if (groups.length === 0) { form.variants = []; return }
  const combinations = cartesian(groups.map(g => g.values))
  const existingMap  = Object.fromEntries(form.variants.map(v => [v.name, v]))
  form.variants = combinations.map(combo => {
    const name = combo.join(' / ')
    return existingMap[name] ?? { id: null, name, sku: '', costPrice: 0, salePrice: 0, promotionPrice: 0, stock: 0, active: true, image: '' }
  })
}

watch(() => form.attributeGroups.map(g => g.values.join(',')).join('|'), regenerateVariants)

// ======== Build payload gửi lên API ========
const buildPayload = () => ({
  category_id: form.category_id,
  name:        form.name,
  slug:        form.slug || generateSlug(form.name),
  description: form.description || null,
  brand:       form.brand || null,
  thumbnail:   form.thumbnail || null,
  is_active:   form.status === 'active',
  images: form.images
    .filter(img => img.preview)
    .map((img, idx) => ({
      ...(img.id ? { id: img.id } : {}),
      image_url:     img.preview,
      alt_text:      img.alt || null,
      display_order: img.order ?? idx + 1,
      is_thumbnail:  img.isThumbnail,
    })),
  variants: form.variants.map(v => ({
    ...(v.id ? { id: v.id } : {}),
    sku:            v.sku,
    price:          v.salePrice,
    sale_price:     v.promotionPrice || null,
    cost_price:     v.costPrice || null,
    stock_quantity: v.stock ?? 0,
    is_active:      v.active,
    thumbnail:      v.image || null,
    attribute_values: (v.attribute_values ?? []).map(a => ({
      ...(a.id ? { id: a.id } : {}),
      attribute_id: a.attribute_id,
      value:        a.value,
    })),
  })),
})

// Validate
const validateForm = () => {
  formErrors.name     = form.name.trim()   ? '' : 'Tên sản phẩm không được để trống.'
  formErrors.category = form.category_id   ? '' : 'Vui lòng chọn danh mục.'
  return !formErrors.name && !formErrors.category
}

// Submit
const submitForm = async () => {
  if (!validateForm() || submitting.value) return
  submitting.value   = true
  serverErrors.value = {}

  try {
    const payload = buildPayload()

    if (modalMode.value === 'add') {
      await productStore.createProduct(payload)
    } else {
      await productStore.updateProduct(form.id, payload)
    }
    closeFormModal()
  } catch (err) {
    // Hiển thị lỗi validation từ server (422)
    if (err.response?.status === 422) {
      serverErrors.value = err.response.data.errors ?? {}
    } else {
      alert(err.response?.data?.message ?? 'Có lỗi xảy ra, vui lòng thử lại.')
    }
  } finally {
    submitting.value = false
  }
}

// ======== Delete ========
const showDeleteModal = ref(false)
const deleteTarget    = ref(null)
const triggerDelete   = (p) => { deleteTarget.value = p; showDeleteModal.value = true }
const executeDelete   = async () => {
  try {
    await productStore.deleteProduct(deleteTarget.value.id)
  } catch {
    alert('Xóa sản phẩm thất bại.')
  } finally {
    showDeleteModal.value = false
    deleteTarget.value    = null
  }
}
</script>




<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(12px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
