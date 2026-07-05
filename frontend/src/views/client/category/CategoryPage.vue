<template>
  <div class="max-w-[1280px] mx-auto px-5 py-12">

    <!-- Page Title -->
    <h1 class="font-title text-[42px] md:text-[52px] font-normal text-black text-center mb-12 tracking-[0.5px]">Cửa hàng</h1>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-start">

      <!-- ─── LEFT SIDEBAR: Filters ─────────────────────────────── -->
      <aside class="w-full lg:w-[210px] shrink-0 space-y-8">

        <!-- Danh mục sản phẩm -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Danh mục sản phẩm</h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li class="flex items-center gap-2 cursor-pointer hover:text-black transition-colors">
              <input type="checkbox" class="accent-black" /> Đầy đủ
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2"><input type="checkbox" class="accent-black" /> Áo khoác</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2"><input type="checkbox" class="accent-black" /> Đàn ông</span>
              <span class="text-neutral-400 text-xs">+</span>
            </li>
            <li class="flex items-center gap-2 cursor-pointer hover:text-black transition-colors">
              <input type="checkbox" class="accent-black" /> Áo phông
            </li>
            <li class="flex items-center gap-2 cursor-pointer hover:text-black transition-colors">
              <input type="checkbox" class="accent-black" /> Áo
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2"><input type="checkbox" class="accent-black" /> Phụ nữ</span>
              <span class="text-neutral-400 text-xs">+</span>
            </li>
          </ul>
        </div>

        <!-- Lọc theo giá -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Lọc theo giá</h3>
          <input type="range" min="0" max="15000000" value="10000000" class="w-full accent-black cursor-pointer" />
          <div class="flex items-center gap-2">
            <input type="text" value="0" class="w-14 border border-neutral-200 rounded px-2 py-1 text-xs font-text outline-none text-center" />
            <span class="text-neutral-400 text-xs">-</span>
            <input type="text" value="10.000.000" class="w-24 border border-neutral-200 rounded px-2 py-1 text-xs font-text outline-none text-center" />
          </div>
          <p class="text-xs text-neutral-500 font-text">Giá: 0đ — 10.000.000đ</p>
          <button class="border border-neutral-300 hover:border-black hover:text-black text-neutral-600 font-text text-[11px] font-bold tracking-wider py-1.5 px-4 uppercase transition-all cursor-pointer bg-transparent">LHC</button>
        </div>

        <!-- Lọc theo màu sắc -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Lọc theo màu sắc</h3>
          <ul class="space-y-2.5 font-text text-[13px] text-neutral-600">
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-black border border-neutral-200"></span> Đen</span>
              <span class="text-neutral-400 text-[11px]">(12)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-sky-400 border border-neutral-200"></span> Màu xanh da trời</span>
              <span class="text-neutral-400 text-[11px]">(7)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-rose-500 border border-neutral-200"></span> Màu nâu</span>
              <span class="text-neutral-400 text-[11px]">(5)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-neutral-400 border border-neutral-200"></span> Xám</span>
              <span class="text-neutral-400 text-[11px]">(8)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-green-600 border border-neutral-200"></span> Màu xanh lá</span>
              <span class="text-neutral-400 text-[11px]">(8)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-red-500 border border-neutral-200"></span> Màu đỏ</span>
              <span class="text-neutral-400 text-[11px]">(10)</span>
            </li>
            <li class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2.5"><span class="w-3.5 h-3.5 rounded-full bg-yellow-400 border border-neutral-200"></span> Màu vàng</span>
              <span class="text-neutral-400 text-[11px]">(9)</span>
            </li>
          </ul>
        </div>

        <!-- Lọc theo kích thước -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Lọc theo kích thước</h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li v-for="(count, size) in { 'XS': 14, 'S': 13, 'M': 12, 'L': 13, 'XL': 14 }" :key="size"
              class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2"><input type="checkbox" class="accent-black" /> {{ size }}</span>
              <span class="text-neutral-400 text-[11px]">({{ count }})</span>
            </li>
          </ul>
        </div>

        <!-- Thương hiệu -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Thương hiệu</h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li v-for="(count, brand) in { 'Calvin Klein': 12, 'Lacoste': 9, 'Louis Vuitton': 10, 'éPortempt': 11, 'Tommy Hilfiger': 8, 'UCLA': 10 }" :key="brand"
              class="flex items-center justify-between cursor-pointer hover:text-black transition-colors">
              <span class="flex items-center gap-2"><input type="checkbox" class="accent-black" /> {{ brand }}</span>
              <span class="text-neutral-400 text-[11px]">({{ count }})</span>
            </li>
          </ul>
        </div>

        <!-- Đã xem gần đây -->
        <div class="space-y-3 pt-2 border-t border-neutral-100">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black pt-2">Đã xem gần đây</h3>
          <div class="flex items-center gap-3 cursor-pointer group">
            <div class="w-12 h-16 bg-neutral-100 overflow-hidden shrink-0 rounded">
              <img
                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=200&auto=format&fit=crop"
                alt="Quần jogger cargo"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform"
              />
            </div>
            <div class="space-y-0.5">
              <p class="font-text text-[12px] font-semibold text-black leading-tight">Quần jogger cargo</p>
              <p class="font-text text-[12px] text-neutral-600">1.390.000đ</p>
              <p class="font-text text-[11px] text-neutral-400 line-through">1.150.000đ</p>
            </div>
          </div>
        </div>

      </aside>

      <!-- ─── RIGHT: Products Grid ──────────────────────────────── -->
      <div class="flex-1 min-w-0">

        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
          <p class="font-text text-[13px] text-neutral-500">Hiển thị 1–12 trong số 58 kết quả</p>
          <div class="flex items-center gap-3">
            <div class="flex gap-1">
              <button class="p-1.5 border border-black text-black cursor-pointer bg-transparent" aria-label="Grid view">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
              </button>
              <button class="p-1.5 border border-neutral-200 hover:border-black text-neutral-400 hover:text-black cursor-pointer bg-transparent transition-colors" aria-label="List view">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="4" width="18" height="2.5"/><rect x="3" y="10.75" width="18" height="2.5"/><rect x="3" y="17.5" width="18" height="2.5"/></svg>
              </button>
            </div>
            <select class="border border-neutral-200 hover:border-black font-text text-[12px] text-neutral-600 py-2 px-3 outline-none cursor-pointer transition-colors bg-white rounded-none">
              <option>Sắp xếp mặc định</option>
              <option>Giá tăng dần</option>
              <option>Giá giảm dần</option>
              <option>Mới nhất</option>
              <option>Phổ biến nhất</option>
            </select>
            <select class="border border-neutral-200 hover:border-black font-text text-[12px] text-neutral-600 py-2 px-3 outline-none cursor-pointer transition-colors bg-white rounded-none">
              <option>12 mặt hàng</option>
              <option>24 mặt hàng</option>
              <option>48 mặt hàng</option>
            </select>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10 mb-12">
          <ProductCard
            v-for="n in 6"
            :key="n"
            image="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=600&auto=format&fit=crop"
            name="Quần jogger cargo"
            currentPrice="1.390.000đ"
            originalPrice="1.150.000đ"
            discount="-20%"
          />
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center gap-2 font-text text-[13px]">
          <span class="w-8 h-8 bg-black text-white flex items-center justify-center text-sm font-semibold cursor-pointer select-none">1</span>
          <span class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center text-sm cursor-pointer select-none transition-colors">2</span>
          <span class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center text-sm cursor-pointer select-none transition-colors">3</span>
          <span class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center text-sm cursor-pointer select-none transition-colors">4</span>
          <span class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center text-sm cursor-pointer select-none transition-colors">5</span>
          <span class="w-8 h-8 border border-neutral-200 hover:border-black text-neutral-600 hover:text-black flex items-center justify-center text-sm cursor-pointer select-none transition-colors">›</span>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import ProductCard from '@/components/client/ui/ProductCard.vue'

// Route param: /category/:slug
// Khi kết nối API, dùng useRoute().params.slug để fetch đúng category
// Ví dụ: /category/ao-khoac, /category/quan-jean, /category/phu-nu
</script>
