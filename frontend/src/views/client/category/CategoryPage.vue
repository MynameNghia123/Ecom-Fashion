<template>
  <div class="w-full">
    <div 
      class="relative bg-cover bg-center bg-no-repeat py-24 flex items-center justify-center transition-all duration-500"
      :style="{ backgroundImage: `url('${categoryBannerImage}')` }"
    >
      <div class="absolute inset-0 bg-black/40"></div>
      <h1 class="relative font-title text-[42px] md:text-[52px] font-bold text-white uppercase tracking-[1px] m-0 z-10 text-center drop-shadow-md">
        {{ categoryTitle }}
      </h1>
    </div>

    <div class="max-w-[1280px] mx-auto px-5 py-12">
      <p v-if="route.query.search" class="text-center font-text text-sm text-neutral-500 mb-10">
      Kết quả tìm kiếm cho: <strong class="text-black font-semibold">"{{ route.query.search }}"</strong>
      <button @click="router.push('/category')" class="ml-2 text-xs text-neutral-400 hover:text-black underline border-none bg-transparent cursor-pointer">Xóa tìm kiếm</button>
    </p>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-start">

      <!-- Mobile Filter Button -->
      <div class="lg:hidden w-full flex justify-end mb-4">
        <button @click="isMobileFilterOpen = true" class="flex items-center gap-2 border border-black px-4 py-2 bg-white text-black font-bold text-xs uppercase tracking-wider cursor-pointer">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
          Lọc sản phẩm
        </button>
      </div>

      <!-- Mobile Filter Overlay -->
      <div v-if="isMobileFilterOpen" @click="isMobileFilterOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

      <!-- LEFT SIDEBAR: Filters -->
      <aside :class="['w-[85%] max-w-[320px] lg:w-[250px] shrink-0 space-y-8 divide-y divide-neutral-200 fixed lg:relative top-0 left-0 h-full lg:h-auto bg-white lg:bg-transparent z-50 lg:z-auto p-6 lg:p-0 overflow-y-auto lg:overflow-visible transition-transform duration-300', isMobileFilterOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">
        
        <div class="flex justify-between items-center lg:hidden mb-6 pb-4 border-b border-neutral-100">
          <span class="font-bold uppercase tracking-wider text-sm">Bộ lọc</span>
          <button @click="isMobileFilterOpen = false" class="bg-transparent border-none cursor-pointer">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        
        <!-- 1. Danh mục sản phẩm -->
        <div class="space-y-3 pt-0">
          <h3 class="font-text text-[12px] font-bold uppercase tracking-wider text-black flex items-center justify-between">
            <span>Danh mục</span>
            <span v-if="selectedCategory" @click="selectedCategory = null" class="text-[10px] text-neutral-400 hover:text-black cursor-pointer font-normal">Xóa</span>
          </h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li 
              @click="selectCategory(null)" 
              :class="['cursor-pointer hover:text-black transition-colors', !selectedCategory ? 'font-bold text-black' : '']"
            >
              Tất cả danh mục
            </li>
            <li 
              v-for="cat in categories" 
              :key="cat.id"
              @click="selectCategory(cat)"
              :class="['cursor-pointer hover:text-black transition-colors flex items-center justify-between', selectedCategory === cat.id ? 'font-bold text-black' : '']"
            >
              <span>{{ cat.name }}</span>
            </li>
          </ul>
        </div>

        <!-- 2. Khoảng giá -->
        <div class="space-y-4 pt-6">
          <h3 class="font-text text-[12px] font-bold uppercase tracking-wider text-black">Khoảng giá</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <input 
                type="number" 
                v-model.number="minPrice" 
                placeholder="Từ (đ)"
                class="w-full border border-neutral-200 px-3 py-1.5 text-xs font-text rounded-xs outline-none focus:border-black"
              />
              <span class="text-neutral-400 text-xs">-</span>
              <input 
                type="number" 
                v-model.number="maxPrice" 
                placeholder="Đến (đ)"
                class="w-full border border-neutral-200 px-3 py-1.5 text-xs font-text rounded-xs outline-none focus:border-black"
              />
            </div>
            <button 
              @click="fetchProducts"
              class="w-full bg-black text-white py-2 text-[11px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors cursor-pointer border-none"
            >
              Áp dụng giá
            </button>
          </div>
        </div>

        <!-- 3. Thương hiệu (Brand) -->
        <div v-if="brands.length > 0" class="space-y-3 pt-6">
          <h3 class="font-text text-[12px] font-bold uppercase tracking-wider text-black flex items-center justify-between">
            <span>Thương hiệu</span>
            <span v-if="selectedBrand" @click="selectedBrand = null" class="text-[10px] text-neutral-400 hover:text-black cursor-pointer font-normal">Xóa</span>
          </h3>
          <div class="space-y-2 max-h-48 overflow-y-auto scrollbar-thin">
            <label 
              v-for="brand in brands" 
              :key="brand"
              class="flex items-center gap-2 text-[13px] font-text text-neutral-600 cursor-pointer hover:text-black"
            >
              <input 
                type="radio" 
                name="brand"
                :value="brand"
                v-model="selectedBrand"
                class="accent-black cursor-pointer"
              />
              <span>{{ brand }}</span>
            </label>
          </div>
        </div>

        <!-- Reset All Filters -->
        <div class="pt-6">
          <button 
            @click="resetAllFilters"
            class="w-full border border-neutral-300 text-neutral-700 py-2.5 text-[11px] font-bold uppercase tracking-wider hover:border-black hover:text-black transition-colors cursor-pointer bg-transparent"
          >
            Xóa tất cả bộ lọc
          </button>
        </div>

      </aside>

      <!-- RIGHT: Products Grid -->
      <div class="flex-1 min-w-0">

        <!-- Toolbar: Count & Sorting -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-neutral-200">
          <p class="font-text text-[13px] text-neutral-500">
            Hiển thị <strong class="text-black font-semibold">{{ products.length }}</strong> sản phẩm
          </p>

          <div class="flex items-center gap-3">
            <label class="font-text text-[12px] text-neutral-500 font-medium">Sắp xếp:</label>
            <select 
              v-model="sortBy"
              class="border border-neutral-200 px-3 py-1.5 text-xs font-text outline-none focus:border-black bg-white cursor-pointer"
            >
              <option value="latest">Mới nhất</option>
              <option value="price_asc">Giá: Thấp đến Cao</option>
              <option value="price_desc">Giá: Cao đến Thấp</option>
            </select>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-20">
          <div class="inline-block w-8 h-8 border-4 border-neutral-200 border-t-black rounded-full animate-spin mb-4"></div>
          <p class="text-sm text-neutral-500 font-text">Đang tải danh sách sản phẩm...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="products.length === 0" class="text-center py-20 bg-neutral-50 rounded-lg">
          <p class="text-sm text-neutral-600 font-text mb-4">Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
          <button @click="resetAllFilters" class="px-5 py-2 bg-black text-white text-xs font-bold uppercase tracking-wider hover:bg-neutral-800 border-none cursor-pointer">
            Bỏ chọn bộ lọc
          </button>
        </div>

        <!-- Products Grid -->
        <div v-else class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10 mb-12">
          <ProductCard
            v-for="prod in products"
            :key="prod.id"
            :id="prod.id"
            :slug="prod.slug || String(prod.id)"
            :image="getImageUrl(prod.thumbnail)"
            :name="prod.name"
            :currentPrice="formatPrice(getMinPrice(prod)) + ' đ'"
          />
        </div>

        <!-- Category Pagination Bar (Centered like Admin design) -->
        <div v-if="products.length > 0" class="flex flex-col items-center justify-center gap-3.5 mt-12 mb-8 pt-8 border-t border-neutral-200 text-center">
          <!-- Page Number Buttons (Centered) -->
          <div class="flex items-center gap-1.5">
            <!-- Prev button -->
            <button 
              @click="changePage(currentPage - 1)" 
              :disabled="currentPage === 1"
              class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-100 hover:text-black transition-all duration-200 disabled:opacity-30 disabled:hover:bg-white disabled:hover:text-slate-600 disabled:cursor-not-allowed cursor-pointer shadow-2xs active:scale-95"
              title="Trang trước"
              aria-label="Trang trước"
            >
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            
            <!-- Page numbers -->
            <button 
              v-for="page in lastPage" 
              :key="page"
              @click="changePage(page)"
              :class="[
                'w-9 h-9 flex items-center justify-center text-xs font-bold rounded-lg border transition-all duration-200 cursor-pointer active:scale-95',
                currentPage === page ? 'border-black bg-black text-white shadow-xs' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400 hover:text-black hover:bg-slate-50'
              ]"
            >
              {{ page }}
            </button>
            
            <!-- Next button -->
            <button 
              @click="changePage(currentPage + 1)" 
              :disabled="currentPage === lastPage"
              class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-100 hover:text-black transition-all duration-200 disabled:opacity-30 disabled:hover:bg-white disabled:hover:text-slate-600 disabled:cursor-not-allowed cursor-pointer shadow-2xs active:scale-95"
              title="Trang tiếp theo"
              aria-label="Trang tiếp theo"
            >
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

          <!-- Items Info Text (Centered below buttons) -->
          <p class="font-text text-xs text-neutral-400 font-medium m-0">
            Hiển thị <strong class="text-neutral-700 font-semibold">{{ ((currentPage - 1) * 12) + 1 }}-{{ Math.min(currentPage * 12, totalProducts) }}</strong> trong số <strong class="text-neutral-700 font-semibold">{{ totalProducts }}</strong> sản phẩm
          </p>
        </div>

      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { productService } from '@/services/client/productService'
import ProductCard from '@/components/client/ui/ProductCard.vue'

const router = useRouter()
const route = useRoute()

const products = ref([])
const categories = ref([])
const brands = ref([])
const loading = ref(true)
const isMobileFilterOpen = ref(false)

// Pagination states
const currentPage = ref(1)
const lastPage = ref(1)
const totalProducts = ref(0)

// Filter states — đồng bộ với route.params.slug
const selectedCategory = ref(null)
const selectedBrand = ref(null)
const minPrice = ref(null)
const maxPrice = ref(null)
const sortBy = ref('latest')

const categoryTitle = computed(() => {
  const slugOrId = route.params.slug
  const currentCat = categories.value.find(c => c.id === selectedCategory.value || c.slug === slugOrId || c.id == slugOrId)
  if (currentCat) {
    return `${(currentCat.name || '').toUpperCase()} | LUXURY`
  }

  if (slugOrId) {
    const lower = String(slugOrId).toLowerCase()
    if (lower.includes('ao')) return 'ÁO | LUXURY'
    if (lower.includes('quan')) return 'QUẦN | LUXURY'
    if (lower.includes('phu-kien') || lower.includes('phukien')) return 'PHỤ KIỆN | LUXURY'
  }

  return 'CỬA HÀNG | LUXURY'
})

const categoryBannerImage = computed(() => {
  const slugOrId = route.params.slug
  const currentCat = categories.value.find(c => c.id === selectedCategory.value || c.slug === slugOrId || c.id == slugOrId)
  
  const slug = (currentCat?.slug || slugOrId || '').toLowerCase()
  const name = (currentCat?.name || '').toLowerCase()

  if (slug.includes('ao') || name.includes('áo')) {
    return '/img/category/bannercategoryao.jpg'
  }
  if (slug.includes('quan') || name.includes('quần')) {
    return '/img/category/bannercategoryquan.jpg'
  }
  if (slug.includes('phu-kien') || slug.includes('phukien') || name.includes('phụ kiện')) {
    return '/img/category/bannercategoryphukien.jpg'
  }

  return '/img/category/bannercategoryao.jpg'
})

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=300&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const getMinPrice = (prod) => {
  if (prod.product_variants && prod.product_variants.length > 0) {
    const prices = prod.product_variants.map(v => v.sale_price ?? v.price)
    return Math.min(...prices)
  }
  return 0
}



const fetchProducts = async () => {
  loading.value = true
  try {
    const params = {
      category_id: selectedCategory.value,
      category_slug: !selectedCategory.value ? route.params.slug : null,
      brand: selectedBrand.value,
      min_price: minPrice.value,
      max_price: maxPrice.value,
      sort: sortBy.value,
      search: route.query.search || null,
      page: currentPage.value
    }
    
    // Remove null/empty keys
    Object.keys(params).forEach(key => {
      if (params[key] === null || params[key] === undefined || params[key] === '') {
        delete params[key]
      }
    })

    const res = await productService.getProducts(params)
    if (res.data && res.data.success) {
      products.value = res.data.data
      if (res.data.meta) {
        currentPage.value = res.data.meta.current_page
        lastPage.value = res.data.meta.last_page
        totalProducts.value = res.data.meta.total
      }
    }
  } catch (err) {
    console.error('Lỗi tải sản phẩm:', err)
  } finally {
    loading.value = false
    isMobileFilterOpen.value = false
  }
}

const fetchCategoriesAndBrands = async () => {
  try {
    const [catRes, brandRes] = await Promise.all([
      productService.getCategories(),
      productService.getBrands()
    ])
    if (catRes.data && catRes.data.success) {
      categories.value = catRes.data.data
      syncCategoryFromRoute()
    }
    if (brandRes.data && brandRes.data.success) {
      brands.value = brandRes.data.data
    }
  } catch (err) {
    console.error('Lỗi tải danh mục / thương hiệu:', err)
  }
}

const syncCategoryFromRoute = () => {
  const slugOrId = route.params.slug
  if (!slugOrId) {
    selectedCategory.value = null
  } else {
    const cat = categories.value.find(c => c.slug === slugOrId || c.id == slugOrId)
    if (cat) {
      selectedCategory.value = cat.id
    }
  }
}

const selectCategory = (cat) => {
  if (!cat) {
    router.push({ path: '/category' })
  } else {
    router.push({ path: `/category/${cat.slug || cat.id}` })
  }
}

const resetAllFilters = () => {
  selectedCategory.value = null
  selectedBrand.value = null
  minPrice.value = null
  maxPrice.value = null
  sortBy.value = 'latest'
  currentPage.value = 1
  if (route.params.slug) {
    router.push({ path: '/category' })
  } else {
    fetchProducts()
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page
    fetchProducts()
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Watch params slug
watch(() => route.params.slug, () => {
  syncCategoryFromRoute()
  currentPage.value = 1
  fetchProducts()
})

// Watch all filters and search query
watch([selectedCategory, selectedBrand, minPrice, maxPrice, sortBy, () => route.query.search], () => {
  currentPage.value = 1
  fetchProducts()
})

onMounted(async () => {
  await fetchCategoriesAndBrands()
  fetchProducts()
})
</script>

<style scoped>
.font-title { font-family: var(--font-title, 'Playfair Display', serif); }
.font-text { font-family: var(--font-text, 'Montserrat', sans-serif); }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e5e5; border-radius: 4px; }
</style>
