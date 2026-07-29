<template>
  <div class="max-w-[1280px] mx-auto px-5 py-12">

    <!-- Page Title -->
    <h1 class="font-title text-[42px] md:text-[52px] font-normal text-black text-center mb-3 tracking-[0.5px]">Cửa hàng</h1>
    <p v-if="route.query.search" class="text-center font-text text-sm text-neutral-500 mb-10">
      Kết quả tìm kiếm cho: <strong class="text-black font-semibold">"{{ route.query.search }}"</strong>
      <button @click="router.push('/category')" class="ml-2 text-xs text-neutral-400 hover:text-black underline border-none bg-transparent cursor-pointer">Xóa tìm kiếm</button>
    </p>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-start">

      <!-- LEFT SIDEBAR: Filters -->
      <aside class="w-full lg:w-[250px] shrink-0 space-y-8 divide-y divide-neutral-200">
        
        <!-- 1. Danh mục sản phẩm -->
        <div class="space-y-3 pt-0">
          <h3 class="font-text text-[12px] font-bold uppercase tracking-wider text-black flex items-center justify-between">
            <span>Danh mục</span>
            <span v-if="selectedCategory" @click="selectedCategory = null" class="text-[10px] text-neutral-400 hover:text-black cursor-pointer font-normal">Xóa</span>
          </h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li 
              @click="selectedCategory = null" 
              :class="['cursor-pointer hover:text-black transition-colors', !selectedCategory ? 'font-bold text-black' : '']"
            >
              Tất cả danh mục
            </li>
            <li 
              v-for="cat in categories" 
              :key="cat.id"
              @click="selectedCategory = cat.id"
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
          <div 
            v-for="prod in products" 
            :key="prod.id"
            @click="goToDetail(prod.id)"
            class="cursor-pointer group"
          >
            <ProductCard
              :image="getImageUrl(prod.thumbnail)"
              :name="prod.name"
              :currentPrice="formatPrice(getMinPrice(prod)) + ' đ'"
            />
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { productService } from '@/services/client/productService'
import ProductCard from '@/components/client/ui/ProductCard.vue'

const router = useRouter()
const route = useRoute()

const products = ref([])
const categories = ref([])
const brands = ref([])
const loading = ref(true)

// Filter states — đồng bộ với route.query nếu có
const selectedCategory = ref(route.query.category_id ? Number(route.query.category_id) : null)
const selectedBrand = ref(null)
const minPrice = ref(null)
const maxPrice = ref(null)
const sortBy = ref('latest')

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

const goToDetail = (id) => {
  router.push({ name: 'ProductDetail', params: { id } })
}

const fetchProducts = async () => {
  loading.value = true
  try {
    const params = {
      category_id: selectedCategory.value || (route.query.category_id ? Number(route.query.category_id) : null),
      brand: selectedBrand.value,
      min_price: minPrice.value,
      max_price: maxPrice.value,
      sort: sortBy.value,
      search: route.query.search || null
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
    }
  } catch (err) {
    console.error('Lỗi tải sản phẩm:', err)
  } finally {
    loading.value = false
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
    }
    if (brandRes.data && brandRes.data.success) {
      brands.value = brandRes.data.data
    }
  } catch (err) {
    console.error('Lỗi tải danh mục / thương hiệu:', err)
  }
}

const resetAllFilters = () => {
  selectedCategory.value = null
  selectedBrand.value = null
  minPrice.value = null
  maxPrice.value = null
  sortBy.value = 'latest'
  fetchProducts()
}

// Watch filters & search/category query
watch([selectedCategory, selectedBrand, sortBy, () => route.query.search, () => route.query.category_id], (newVals, oldVals) => {
  // Nếu category_id từ URL thay đổi thì sync vào selectedCategory
  if (newVals[4] !== oldVals?.[4]) {
    selectedCategory.value = newVals[4] ? Number(newVals[4]) : null
  }
  fetchProducts()
})

onMounted(() => {
  fetchCategoriesAndBrands()
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
