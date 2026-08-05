<template>
  <div v-if="loading" class="w-full bg-white text-black py-20 px-5 text-center font-text">
    <div class="inline-block w-8 h-8 border-4 border-neutral-200 border-t-black rounded-full animate-spin mb-4"></div>
    <p class="text-sm text-neutral-500">Đang tải chi tiết sản phẩm...</p>
  </div>

  <div v-else-if="!product" class="w-full bg-white text-black py-20 px-5 text-center font-text">
    <p class="text-sm text-neutral-500">Không tìm thấy sản phẩm này hoặc sản phẩm đã bị ngừng bán.</p>
    <router-link to="/" class="inline-block mt-4 text-sm underline hover:text-neutral-600">Quay lại trang chủ</router-link>
  </div>

  <div v-else class="w-full bg-white text-black py-10 px-5 lg:px-20 font-text">
    <!-- Breadcrumb Navigation -->
    <div class="text-[12px] uppercase tracking-[1px] text-gray-500 mb-8 font-medium">
      <router-link to="/" class="hover:text-black transition-colors">TRANG CHỦ</router-link>
      <span class="mx-2">&gt;</span>
      <span class="text-black">{{ product.name }}</span>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
      
      <!-- Left side: Images -->
      <div class="lg:col-span-6 flex flex-col-reverse md:flex-row gap-4">
        <!-- Vertical Thumbnail Stack -->
        <div v-if="productImages.length > 1" class="flex md:flex-col gap-3 md:w-[100px] shrink-0">
          <button 
            v-for="(img, idx) in productImages" 
            :key="idx"
            @click="activeImageIdx = idx"
            class="w-16 h-20 md:w-full md:h-[120px] border overflow-hidden transition-all duration-200"
            :class="activeImageIdx === idx ? 'border-black opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100'"
          >
            <img :src="img" alt="Thumbnail" class="w-full h-full object-cover">
          </button>
        </div>

        <!-- Main Large Image View -->
        <div class="grow aspect-[3/4] max-h-[600px] md:max-h-[650px] relative bg-gray-50 border border-gray-100 overflow-hidden">
          <img :src="productImages[activeImageIdx]" alt="Main Product View" class="w-full h-full object-cover transition-all duration-300">
        </div>
      </div>

      <!-- Right side: Product Information & Controls -->
      <div class="lg:col-span-6 flex flex-col justify-start">
        <!-- Header & Wishlist -->
        <div class="flex justify-between items-start gap-4 mb-2">
          <h1 class="font-title text-[32px] font-bold leading-tight tracking-tight text-gray-900">
            {{ product.name }}
          </h1>
          <button 
            @click="handleToggleWishlist" 
            class="text-gray-400 hover:text-red-500 transition-colors p-1"
            :title="isWishlisted ? 'Xóa khỏi danh sách yêu thích' : 'Thêm vào danh sách yêu thích'"
            aria-label="Danh sách yêu thích"
          >
            <svg 
              width="28" 
              height="28" 
              viewBox="0 0 24 24" 
              :fill="isWishlisted ? 'currentColor' : 'none'" 
              stroke="currentColor" 
              stroke-width="1.5" 
              class="transition-colors"
              :class="isWishlisted ? 'text-red-500' : ''"
            >
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
            </svg>
          </button>
        </div>

        <!-- Brand name -->
        <p v-if="product.brand" class="text-sm text-neutral-500 font-medium mb-1">
          Thương hiệu: {{ product.brand }}
        </p>

        <!-- SKU Code -->
        <p class="text-[12px] text-gray-400 tracking-[1px] uppercase mb-4">
          MÃ SP: {{ selectedVariant?.sku || product.slug || product.id }}
        </p>

        <!-- Price -->
        <div class="mb-6 flex items-baseline gap-4">
          <span class="text-[26px] font-bold border-b-2 border-black pb-1">
            {{ formatPrice(currentPrice) }} đ
          </span>
          <span v-if="originalPrice" class="text-[16px] text-gray-400 line-through">
            {{ formatPrice(originalPrice) }} đ
          </span>
        </div>

        <hr class="border-gray-200 mb-6">

        <!-- Color Selector -->
        <div v-if="colors.length > 0" class="mb-6">
          <p class="text-[13px] font-bold uppercase tracking-[1px] mb-3 text-gray-700">Màu sắc:</p>
          <div class="flex flex-wrap gap-3">
            <button 
              v-for="color in colors"
              :key="color"
              @click="selectedColor = color; selectedSize = ''"
              class="flex items-center gap-2 px-4 py-2.5 border text-[13px] font-semibold transition-all duration-200"
              :class="selectedColor === color ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'"
            >
              <span class="w-4 h-4 rounded-full border border-white/20" :style="{ backgroundColor: color.toLowerCase() === 'đen' ? '#000000' : (color.toLowerCase() === 'trắng' ? '#ffffff' : '#888888') }"></span>
              {{ color }}
            </button>
          </div>
        </div>

        <!-- Size Selector -->
        <div v-if="allSizes.length > 0" class="mb-6">
          <div class="flex justify-between items-center mb-3">
            <p class="text-[13px] font-bold uppercase tracking-[1px] text-gray-700">Kích cỡ:</p>
            <!-- Size Helper Trigger -->
            <button 
              @click="showSizeCalculator = !showSizeCalculator"
              class="text-[12px] text-gray-500 underline hover:text-black flex items-center gap-1 font-medium transition-colors"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
              Gợi ý chọn size phù hợp
            </button>
          </div>

          <!-- Size suggestions inline tool -->
          <SizeCalculator v-if="showSizeCalculator" class="mb-4" />

          <!-- Size Grid -->
          <div class="grid grid-cols-6 gap-2">
            <button 
              v-for="size in allSizes" 
              :key="size"
              @click="selectedSize = size"
              :disabled="!availableSizesForSelectedColor.some(item => item.value === size && item.stock > 0)" 
              class="py-3 border text-[13px] font-semibold transition-all duration-200"
              :class="[
                !availableSizesForSelectedColor.some(item => item.value === size && item.stock > 0) ? 'border-gray-200 text-gray-300 cursor-not-allowed bg-gray-50 line-through' : '',
                selectedSize === size && availableSizesForSelectedColor.some(item => item.value === size && item.stock > 0) ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'
              ]"
            >
              {{ size }}
            </button>
          </div>
        </div>

        <!-- Quantity Selector -->
        <div class="mb-8">
          <p class="text-[13px] font-bold uppercase tracking-[1px] text-gray-700 mb-3">Số lượng:</p>
          <div class="flex items-center gap-4">
            <div class="flex items-center border border-gray-300 h-[50px]">
              <button 
                @click="decreaseQuantity"
                class="px-4 h-full text-gray-600 hover:bg-gray-100 transition-colors"
                :disabled="quantity <= 1"
              >-</button>
              <input 
                type="number" 
                v-model.number="quantity"
                @change="validateQuantity"
                class="w-16 h-full text-center border-0 border-x border-gray-300 focus:outline-none focus:ring-0 text-[14px] font-medium p-0"
                min="1"
                :max="maxQuantity"
              />
              <button 
                @click="increaseQuantity"
                class="px-4 h-full text-gray-600 hover:bg-gray-100 transition-colors"
                :disabled="quantity >= maxQuantity || !selectedVariant"
              >+</button>
            </div>
            <span v-if="selectedVariant" class="text-[13px] text-gray-500 font-medium">
              Còn {{ selectedVariant.stock_quantity }} sản phẩm
            </span>
          </div>
        </div>

        <!-- Purchase Buttons (Add to Cart + Buy Now) -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
          <!-- Add To Cart Button -->
          <button 
            @click="handleAddToCart"
            class="flex-1 bg-white hover:bg-neutral-50 text-black uppercase py-4 text-[14px] font-bold tracking-[1px] transition-all duration-300 border border-black"
          >
            THÊM VÀO GIỎ HÀNG
          </button>
          
          <!-- Buy Now Button -->
          <button 
            @click="handleBuyNow"
            class="flex-1 bg-black hover:bg-neutral-800 text-white uppercase py-4 text-[14px] font-bold tracking-[1px] transition-all duration-300 border border-black"
          >
            MUA NGAY
          </button>
        </div>

        <!-- Accordions -->

        <div class="border-t border-gray-200">
          <!-- Description Accordion -->
          <div class="border-b border-gray-200">
            <button 
              @click="toggleAccordion('description')"
              class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
            >
              <span>MÔ TẢ</span>
              <span class="text-[18px] font-normal font-mono">{{ accordions.description ? '-' : '+' }}</span>
            </button>
            <div 
              v-show="accordions.description" 
              class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
              v-html="product.description"
            ></div>
          </div>

          <!-- Usage Accordion -->
          <div class="border-b border-gray-200">
            <button 
              @click="toggleAccordion('hdsd')"
              class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
            >
              <span>HƯỚNG DẪN SỬ DỤNG SẢN PHẨM</span>
              <span class="text-[18px] font-normal font-mono">{{ accordions.hdsd ? '-' : '+' }}</span>
            </button>
            <div 
              v-show="accordions.hdsd" 
              class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
            >
              <ul class="list-disc pl-5 space-y-1">
                <li>Giặt máy ở chế độ nhẹ nhàng, nhiệt độ nước không quá 30°C.</li>
                <li>Không sử dụng chất tẩy rửa mạnh.</li>
                <li>Phơi trong bóng râm, tránh ánh nắng trực tiếp.</li>
                <li>Ủi ở nhiệt độ thấp hoặc vừa phải.</li>
              </ul>
            </div>
          </div>

          <!-- Shipping Accordion -->
          <div class="border-b border-gray-200">
            <button 
              @click="toggleAccordion('giaohang')"
              class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
            >
              <span>GIAO HÀNG / ĐỔI HÀNG</span>
              <span class="text-[18px] font-normal font-mono">{{ accordions.giaohang ? '-' : '+' }}</span>
            </button>
            <div 
              v-show="accordions.giaohang" 
              class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
            >
              Giao hàng miễn phí toàn quốc cho đơn hàng từ 1.000.000đ. Hỗ trợ đổi trả trong vòng 30 ngày kể từ ngày nhận hàng với điều kiện sản phẩm còn nguyên tem mác và chưa qua sử dụng. Vui lòng liên hệ bộ phận CSKH để được hỗ trợ chi tiết.
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Bottom Section: Tabs for Reviews and Size Guide -->
    <div class="mb-16">
      <div class="flex justify-center border-b border-gray-200 mb-8 gap-12">
        <button 
          @click="activeTab = 'reviews'"
          class="pb-4 text-[13px] font-bold uppercase tracking-[1px] transition-all relative"
          :class="activeTab === 'reviews' ? 'text-black font-semibold' : 'text-gray-400 hover:text-black'"
        >
          ĐÁNH GIÁ SẢN PHẨM
          <span 
            v-if="activeTab === 'reviews'" 
            class="absolute bottom-0 left-0 right-0 h-[2px] bg-black"
          ></span>
        </button>
        <button 
          @click="activeTab = 'sizeguide'"
          class="pb-4 text-[13px] font-bold uppercase tracking-[1px] transition-all relative"
          :class="activeTab === 'sizeguide' ? 'text-black font-semibold' : 'text-gray-400 hover:text-black'"
        >
          HƯỚNG DẪN CHỌN SIZE
          <span 
            v-if="activeTab === 'sizeguide'" 
            class="absolute bottom-0 left-0 right-0 h-[2px] bg-black"
          ></span>
        </button>
      </div>

      <!-- Tab Content: Reviews (Imported Component) -->
      <ProductReviews 
        v-show="activeTab === 'reviews'" 
        :reviews="reviews" 
        :rating-stats="ratingStats" 
        :average-rating="averageRating" 
      />

      <!-- Tab Content: Size Guide -->
      <div v-show="activeTab === 'sizeguide'" class="flex justify-center overflow-x-auto w-full">
        <table class="min-w-[700px] max-w-[900px] border-collapse text-center border border-gray-300 font-text text-[13px]">
          <thead>
            <tr class="bg-[#d4e6cc] text-gray-800 font-bold">
              <th colspan="3" class="border border-gray-300 py-3 text-[14px]">NAM</th>
              <th colspan="3" class="border border-gray-300 py-3 text-[14px]">NỮ</th>
            </tr>
            <tr class="bg-gray-100 font-bold text-gray-700">
              <th class="border border-gray-300 py-2.5 w-[16%]">Chiều cao</th>
              <th class="border border-gray-300 py-2.5 w-[16%]">Cân nặng</th>
              <th class="border border-gray-300 py-2.5 w-[16%]">Size</th>
              <th class="border border-gray-300 py-2.5 w-[16%]">Chiều cao</th>
              <th class="border border-gray-300 py-2.5 w-[16%]">Cân nặng</th>
              <th class="border border-gray-300 py-2.5 w-[16%]">Size</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="(row, idx) in sizeTable" :key="idx" class="hover:bg-gray-50">
              <td class="border border-gray-200 py-3 font-medium">{{ row.mHeight }}</td>
              <td class="border border-gray-200 py-3 text-gray-600">{{ row.mWeight }}</td>
              <td class="border border-gray-200 py-3 font-semibold text-emerald-800">{{ row.mSize }}</td>
              <td class="border border-gray-200 py-3 font-medium">{{ row.wHeight }}</td>
              <td class="border border-gray-200 py-3 text-gray-600">{{ row.wWeight }}</td>
              <td class="border border-gray-200 py-3 font-semibold text-rose-800">{{ row.wSize }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Related Products Section -->
    <div class="border-t border-gray-200 pt-16">
      <h2 class="font-title text-[28px] font-bold text-gray-900 mb-8 tracking-tight">
        Sản phẩm liên quan
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Reusing the shared ProductCard component -->
        <ProductCard 
          v-for="rel in relatedProducts" 
          :key="rel.id" 
          :image="rel.image"
          :name="rel.name"
          :currentPrice="formatPrice(rel.price) + ' đ'"
          :originalPrice="formatPrice(rel.originalPrice) + ' đ'"
          :discount="'-' + rel.discount + '%'"
          :rating="rel.rating"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { productService } from '@/services/client/productService'
import { useCartStore } from '@/stores/client/cartStore'
import { useWishlistStore } from '@/stores/client/wishlistStore'
import ProductCard from '@/components/client/ui/ProductCard.vue'
import SizeCalculator from '@/components/client/ui/SizeCalculator.vue'
import ProductReviews from '@/components/client/ui/ProductReviews.vue'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()

// State
const loading = ref(true)
const product = ref(null)
const activeImageIdx = ref(0)
const selectedColor = ref('')
const selectedSize = ref('')
const activeTab = ref('reviews')
const showSizeCalculator = ref(false)

const isWishlisted = computed(() => {
  if (!product.value) return false
  return wishlistStore.isInWishlist(product.value.id)
})

const handleToggleWishlist = () => {
  if (!product.value) return
  wishlistStore.toggleWishlist({
    id: product.value.id,
    name: product.value.name,
    currentPrice: formatPrice(currentPrice.value) + ' đ',
    originalPrice: originalPrice.value ? formatPrice(originalPrice.value) + ' đ' : null,
    image: productImages.value[0],
    description: product.value.brand || 'THỜI TRANG CAO CẤP'
  })
}

// Review State
const averageRating = ref(5)
const ratingStats = ref([
  { stars: 5, percentage: '100%', count: 1 },
  { stars: 4, percentage: '0%', count: 0 },
  { stars: 3, percentage: '0%', count: 0 },
  { stars: 2, percentage: '0%', count: 0 },
  { stars: 1, percentage: '0%', count: 0 }
])
const reviews = ref([])

// Accordions
const accordions = ref({
  mota: true,
  hdsd: false,
  giaohang: false
})

const toggleAccordion = (section) => {
  accordions.value[section] = !accordions.value[section]
}

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

// Fetch Product Details & Reviews
onMounted(async () => {
  try {
    const id = route.params.id || '1'
    const res = await productService.getProductDetail(id)
    if (res.data && res.data.success) {
      product.value = res.data.data
      
      if (colors.value.length > 0) {
        selectedColor.value = colors.value[0]
      }
      if (availableSizesForSelectedColor.value.length > 0) {
        selectedSize.value = availableSizesForSelectedColor.value[0]
      }
    }

    // Fetch Reviews
    try {
      const reviewRes = await productService.getProductReviews(id)
      if (reviewRes.data && reviewRes.data.success) {
        const revData = reviewRes.data.data || []
        if (revData.length > 0) {
          reviews.value = revData.map(r => ({
            author: r.customer ? `${r.customer.first_name || ''} ${r.customer.last_name || ''}`.trim() || 'Khách hàng' : 'Khách hàng',
            date: r.created_at ? new Date(r.created_at).toLocaleDateString('vi-VN') : 'Gần đây',
            rating: r.rating || 5,
            comment: r.comment || 'Sản phẩm rất tốt'
          }))
          averageRating.value = reviewRes.data.average_rating || 5

          // Compute star distribution percentages
          const total = revData.length
          ratingStats.value = [5, 4, 3, 2, 1].map(stars => {
            const count = revData.filter(r => r.rating === stars).length
            return {
              stars,
              count,
              percentage: total > 0 ? `${Math.round((count / total) * 100)}%` : '0%'
            }
          })
        } else {
          // Default initial review state if no reviews in DB yet
          reviews.value = [
            {
              author: 'Nguyễn Văn A (Đã mua hàng)',
              date: '20/07/2026',
              rating: 5,
              comment: 'Sản phẩm tuyệt vời, chất vải mát, đúng như mô tả. Sẽ tiếp tục ủng hộ shop!'
            }
          ]
          averageRating.value = 5
          ratingStats.value = [
            { stars: 5, percentage: '100%', count: 1 },
            { stars: 4, percentage: '0%', count: 0 },
            { stars: 3, percentage: '0%', count: 0 },
            { stars: 2, percentage: '0%', count: 0 },
            { stars: 1, percentage: '0%', count: 0 }
          ]
        }
      }
    } catch (e) {
      console.warn('Chưa có đánh giá hoặc lỗi khi tải đánh giá:', e)
    }

  } catch (err) {
    console.error('Lỗi khi tải chi tiết sản phẩm:', err)
  } finally {
    loading.value = false
  }
})

// Dynamic Attributes Resolution
const colors = computed(() => {
  if (!product.value) return []
  const variants = product.value.product_variants || product.value.productVariants || []
  const list = new Set()
  variants.forEach(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || []
    attributeValues.forEach(av => {
      const name = av.attribute?.name || ''
      const normalizedName = name.toLowerCase().trim()
      if (
        normalizedName === 'màu sắc' || 
        normalizedName === 'color' || 
        normalizedName === 'màu' ||
        normalizedName.includes('màu')
      ) {
        list.add(av.value)
      }
    })
  })
  return Array.from(list)
})

// Tất cả các kích cỡ hiện có của sản phẩm này
const allSizes = computed(() => {
  if (!product.value) return []
  const variants = product.value.product_variants || product.value.productVariants || []
  const list = new Set()
  variants.forEach(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || []
    attributeValues.forEach(av => {
      const name = av.attribute?.name || ''
      const normalizedName = name.toLowerCase().trim()
      const isColorAttr = normalizedName === 'màu sắc' || normalizedName === 'color' || normalizedName === 'màu' || normalizedName.includes('màu')
      if (!isColorAttr) {
        list.add(av.value)
      }
    })
  })
  return Array.from(list).sort()
})

// Các size khả dụng cho màu đang được chọn
const availableSizesForSelectedColor = computed(() => {
  if (!product.value) return []
  const variants = product.value.product_variants || product.value.productVariants || []
  const list = []
  variants.forEach(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || []
    // Nếu có tùy chọn màu sắc, kiểm tra xem có khớp màu đang chọn không
    const matchColor = colors.value.length === 0 || !selectedColor.value || attributeValues.some(av => {
      const name = av.attribute?.name || ''
      const normalizedName = name.toLowerCase().trim()
      const isColorAttr = normalizedName === 'màu sắc' || normalizedName === 'color' || normalizedName === 'màu' || normalizedName.includes('màu')
      return isColorAttr && av.value === selectedColor.value
    })
    
    if (matchColor) {
      attributeValues.forEach(av => {
        const name = av.attribute?.name || ''
        const normalizedName = name.toLowerCase().trim()
        const isColorAttr = normalizedName === 'màu sắc' || normalizedName === 'color' || normalizedName === 'màu' || normalizedName.includes('màu')
        const isSizeAttr = !isColorAttr
        if (isSizeAttr) {
          list.push({
            value: av.value,
            stock: variant.stock_quantity || variant.stockQuantity || 0
          })
        }
      })
    }
  })
  return list
})

// Biến thể khớp với Màu & Size đã chọn
const selectedVariant = computed(() => {
  if (!product.value) return null
  const variants = product.value.product_variants || product.value.productVariants || []
  return variants.find(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || []
    // Khớp màu sắc (nếu sản phẩm có thuộc tính màu)
    const matchColor = colors.value.length === 0 || attributeValues.some(av => {
      const name = av.attribute?.name || ''
      const normalizedName = name.toLowerCase().trim()
      const isColorAttr = normalizedName === 'màu sắc' || normalizedName === 'color' || normalizedName === 'màu' || normalizedName.includes('màu')
      return isColorAttr && av.value === selectedColor.value
    })
    
    // Khớp kích cỡ (nếu sản phẩm có thuộc tính kích cỡ)
    const matchSize = allSizes.value.length === 0 || attributeValues.some(av => {
      const name = av.attribute?.name || ''
      const normalizedName = name.toLowerCase().trim()
      const isColorAttr = normalizedName === 'màu sắc' || normalizedName === 'color' || normalizedName === 'màu' || normalizedName.includes('màu')
      const isSizeAttr = !isColorAttr
      return isSizeAttr && av.value === selectedSize.value
    })
    
    return matchColor && matchSize
  })
})

const quantity = ref(1)

const maxQuantity = computed(() => {
  if (!selectedVariant.value) return 1
  return selectedVariant.value.stock_quantity || 1
})

const increaseQuantity = () => {
  if (quantity.value < maxQuantity.value) {
    quantity.value++
  }
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const validateQuantity = () => {
  if (typeof quantity.value !== 'number' || isNaN(quantity.value) || quantity.value < 1) {
    quantity.value = 1
  }
  if (selectedVariant.value && selectedVariant.value.stock_quantity > 0) {
    if (quantity.value > selectedVariant.value.stock_quantity) {
      quantity.value = selectedVariant.value.stock_quantity
    }
  }
}

watch(selectedVariant, (newVal) => {
  if (newVal && newVal.stock_quantity > 0) {
    if (quantity.value > newVal.stock_quantity) {
      quantity.value = newVal.stock_quantity
    }
  } else {
    quantity.value = 1
  }
})


// Giá hiển thị hiện tại
const currentPrice = computed(() => {
  if (selectedVariant.value) {
    return selectedVariant.value.sale_price ?? selectedVariant.value.salePrice ?? selectedVariant.value.price
  }
  if (product.value) {
    const variants = product.value.product_variants || product.value.productVariants || []
    if (variants.length > 0) {
      // Trả về giá thấp nhất của các biến thể
      const prices = variants.map(v => v.sale_price ?? v.salePrice ?? v.price)
      return Math.min(...prices)
    }
  }
  return 0
})

const originalPrice = computed(() => {
  if (selectedVariant.value) {
    return (selectedVariant.value.sale_price ?? selectedVariant.value.salePrice) ? selectedVariant.value.price : null
  }
  return null
})

// Danh sách ảnh
const productImages = computed(() => {
  if (product.value) {
    const images = []
    if (product.value.thumbnail) {
      images.push(product.value.thumbnail)
    }
    const apiImages = product.value.product_images || product.value.productImages || []
    if (apiImages.length > 0) {
      apiImages.forEach(img => {
        const url = img.image_url || img.imageUrl
        if (url && url !== product.value.thumbnail) {
          images.push(url)
        }
      })
    }
    if (images.length === 0) {
      images.push('https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop')
    }
    return images
  }
  return [
    'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop'
  ]
})

// Thao tác giỏ hàng
const handleAddToCart = () => {
  if (!selectedVariant.value) {
    alert('Vui lòng chọn màu sắc và kích cỡ.')
    return
  }
  if (selectedVariant.value.stock_quantity <= 0) {
    alert('Sản phẩm đã hết hàng.')
    return
  }
  
  cartStore.addItem({
    product_variant_id: selectedVariant.value.id,
    quantity: quantity.value,
    price: currentPrice.value,
    product_name: product.value.name,
    product_thumbnail: product.value.thumbnail,
    sku: selectedVariant.value.sku,
    stock_quantity: selectedVariant.value.stock_quantity,
    attributes: [
      { attribute: 'Màu sắc', value: selectedColor.value },
      { attribute: 'Kích cỡ', value: selectedSize.value }
    ]
  })
  alert(`Đã thêm ${product.value.name} (Màu: ${selectedColor.value}, Size: ${selectedSize.value}, SL: ${quantity.value}) vào giỏ hàng thành công!`)
}

// Mua ngay
const handleBuyNow = () => {
  if (!selectedVariant.value) {
    alert('Vui lòng chọn màu sắc và kích cỡ.')
    return
  }
  if (selectedVariant.value.stock_quantity <= 0) {
    alert('Sản phẩm đã hết hàng.')
    return
  }

  cartStore.addItem({
    product_variant_id: selectedVariant.value.id,
    quantity: quantity.value,
    price: currentPrice.value,
    product_name: product.value.name,
    product_thumbnail: product.value.thumbnail,
    sku: selectedVariant.value.sku,
    stock_quantity: selectedVariant.value.stock_quantity,
    attributes: [
      { attribute: 'Màu sắc', value: selectedColor.value },
      { attribute: 'Kích cỡ', value: selectedSize.value }
    ]
  })
  
  // Chuyển hướng thẳng tới checkout
  router.push('/checkout')
}

const sizeTable = ref([
  { mHeight: '1m60-1m65', mWeight: '55-60kg', mSize: '38', wHeight: '1m48-1m53', wWeight: '38-43kg', wSize: '38' },
  { mHeight: '1m64-1m69', mWeight: '60-65kg', mSize: '39', wHeight: '1m53-1m55', wWeight: '43-46kg', wSize: '39' },
  { mHeight: '1m70-1m74', mWeight: '66-70kg', mSize: '40', wHeight: '1m53-1m58', wWeight: '46-53kg', wSize: '40' },
  { mHeight: '1m74-1m76', mWeight: '70-76kg', mSize: '41', wHeight: '1m55-1m62', wWeight: '53-57kg', wSize: '41' },
  { mHeight: '1m65-1m77', mWeight: '76-80kg', mSize: '42', wHeight: '1m55-1m66', wWeight: '57-66kg', wSize: '42' }
])

const relatedProducts = ref([])
</script>


<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
