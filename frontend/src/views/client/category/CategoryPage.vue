<template>
  <div class="max-w-[1280px] mx-auto px-5 py-12">

    <!-- Page Title -->
    <h1 class="font-title text-[42px] md:text-[52px] font-normal text-black text-center mb-12 tracking-[0.5px]">Cửa hàng</h1>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 items-start">

      <!-- LEFT SIDEBAR: Filters (Static UI for layout) -->
      <aside class="w-full lg:w-[210px] shrink-0 space-y-8">
        <!-- Danh mục sản phẩm -->
        <div class="space-y-3">
          <h3 class="font-text text-[11px] font-bold uppercase tracking-wider text-black">Danh mục sản phẩm</h3>
          <ul class="space-y-2 font-text text-[13px] text-neutral-600">
            <li class="flex items-center gap-2 cursor-pointer hover:text-black transition-colors font-bold text-black">
              Đầy đủ
            </li>
          </ul>
        </div>
      </aside>

      <!-- RIGHT: Products Grid -->
      <div class="flex-1 min-w-0">

        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
          <p class="font-text text-[13px] text-neutral-500">
            Hiển thị {{ products.length }} kết quả sản phẩm
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-20">
          <div class="inline-block w-8 h-8 border-4 border-neutral-200 border-t-black rounded-full animate-spin mb-4"></div>
          <p class="text-sm text-neutral-500">Đang tải danh sách sản phẩm...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="products.length === 0" class="text-center py-20">
          <p class="text-sm text-neutral-500">Không tìm thấy sản phẩm nào.</p>
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { productService } from '@/services/client/productService'
import ProductCard from '@/components/client/ui/ProductCard.vue'

const router = useRouter()
const products = ref([])
const loading = ref(true)

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

onMounted(async () => {
  try {
    const res = await productService.getProducts()
    if (res.data && res.data.success) {
      products.value = res.data.data
    }
  } catch (err) {
    console.error('Không tải được danh sách sản phẩm:', err)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
