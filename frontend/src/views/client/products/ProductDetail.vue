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
      <ProductGallery :product-images="productImages" />

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

        <!-- Dynamic Attribute Selectors -->
        <div v-for="(values, attrName) in product?.attributes" :key="attrName" class="mb-6">
          <div class="flex justify-between items-center mb-3">
            <p class="text-[13px] font-bold uppercase tracking-[1px] text-gray-700">{{ attrName }}:</p>
            <button 
              v-if="attrName.toLowerCase().includes('size') || attrName.toLowerCase().includes('kích')"
              @click="showSizeCalculator = !showSizeCalculator"
              class="text-[12px] text-gray-500 underline hover:text-black flex items-center gap-1 font-medium transition-colors"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
              Gợi ý chọn size phù hợp
            </button>
          </div>

          <SizeCalculator v-if="(attrName.toLowerCase().includes('size') || attrName.toLowerCase().includes('kích')) && showSizeCalculator" class="mb-4" />

          <div class="flex flex-wrap gap-3">
            <button 
              v-for="val in values" 
              :key="val"
              @click="selectAttribute(attrName, val)"
              :disabled="!isAttributeValueAvailable(attrName, val)"
              class="flex items-center justify-center gap-2 px-4 py-2.5 border text-[13px] font-semibold transition-all duration-200"
              :class="[
                !isAttributeValueAvailable(attrName, val) ? 'border-gray-200 text-gray-300 cursor-not-allowed bg-gray-50 line-through' : '',
                selectedAttributes[attrName] === val && isAttributeValueAvailable(attrName, val) ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'
              ]"
            >
              <span v-if="attrName.toLowerCase().includes('màu') || attrName.toLowerCase().includes('color')" 
                class="w-4 h-4 rounded-full border border-white/20" 
                :style="{ backgroundColor: val.toLowerCase() === 'đen' ? '#000000' : (val.toLowerCase() === 'trắng' ? '#ffffff' : '#888888') }"
              ></span>
              {{ val }}
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

        <!-- Notification Area -->
        <div 
          v-if="notification.show" 
          class="mb-4 p-3 text-[13px] font-medium border"
          :class="notification.type === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200'"
        >
          {{ notification.message }}
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
        <ProductAccordions :product="product" />

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
        :reviews="reviews" 
        :average-rating="averageRating" 
        :rating-stats="ratingStats" 
        :is-eligible-to-review="isEligibleToReview"
        :eligible-order-detail-id="eligibleOrderDetailId"
        v-show="activeTab === 'reviews'" 
      />

      <!-- Tab Content: Size Guide -->
      <ProductSizeGuide v-show="activeTab === 'sizeguide'" />
    </div>

    <!-- Related Products Section -->
    <div v-if="relatedProducts.length > 0" class="border-t border-gray-200 pt-16">
      <div class="text-center mb-10">
        <h2 class="font-title text-[28px] font-bold text-gray-900 tracking-tight uppercase">Sản phẩm liên quan</h2>
        <div class="w-16 h-[3px] bg-black mx-auto mt-4"></div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <ProductCard 
          v-for="rel in relatedProducts" 
          :key="rel.id" 
          :id="rel.id"
          :slug="rel.slug"
          :image="rel.image"
          :name="rel.name"
          :currentPrice="rel.currentPrice"
          :originalPrice="rel.originalPrice"
          :discount="rel.discount ? '-' + rel.discount + '%' : null"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { productService } from '@/services/client/productService'
import { reviewService } from '@/services/client/reviewService'
import { useCartStore } from '@/stores/client/cartStore'
import { useWishlistStore } from '@/stores/client/wishlistStore'
import { useClientAuthStore } from '@/stores/client/authStore'
import ProductCard from '@/components/client/ui/ProductCard.vue'
import SizeCalculator from '@/components/client/ui/SizeCalculator.vue'
import ProductGallery from '@/components/client/ui/ProductGallery.vue'
import ProductAccordions from '@/components/client/ui/ProductAccordions.vue'
import ProductSizeGuide from '@/components/client/ui/ProductSizeGuide.vue'
import ProductReviews from '@/components/client/ui/ProductReviews.vue'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useClientAuthStore()

// State
const loading = ref(true)
const product = ref(null)
const selectedAttributes = ref({})
const activeTab = ref('reviews')
const showSizeCalculator = ref(false)

const isEligibleToReview = ref(false)
const eligibleOrderDetailId = ref(null)

const notification = ref({
  show: false,
  message: '',
  type: 'error'
})
const showNotification = (message, type = 'error') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

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
const averageRating = ref(0)
const ratingStats = ref([
  { stars: 5, percentage: '0%', count: 0 },
  { stars: 4, percentage: '0%', count: 0 },
  { stars: 3, percentage: '0%', count: 0 },
  { stars: 2, percentage: '0%', count: 0 },
  { stars: 1, percentage: '0%', count: 0 }
])
const reviews = ref([])

// Review State

const formatPrice = (value) => {
  if (!value) return '0'
  return new Intl.NumberFormat('vi-VN').format(value)
}

// Fetch Product Details & Reviews
onMounted(async () => {
  try {
    const slug = route.params.slug || route.params.id || '1'
    const res = await productService.getProductDetail(slug)
    if (res.data && res.data.success) {
      product.value = res.data.data
      
      if (product.value.attributes) {
        Object.keys(product.value.attributes).forEach(attrName => {
          if (product.value.attributes[attrName].length > 0) {
            selectedAttributes.value[attrName] = product.value.attributes[attrName][0]
          }
        })
      }
    }

    // Fetch Reviews
    const productId = product.value?.id
    try {
      if (authStore.isAuthenticated && productId) {
        const eligRes = await reviewService.checkReviewEligibility(productId)
        if (eligRes.data && eligRes.data.success) {
          isEligibleToReview.value = eligRes.data.data.eligible
          eligibleOrderDetailId.value = eligRes.data.data.order_detail_id
        }
      }
    } catch (e) {
      console.warn('Lỗi khi kiểm tra quyền đánh giá:', e)
    }

    try {
      const reviewRes = await productService.getProductReviews(productId || slug)
        if (reviewRes.data && reviewRes.data.success) {
        const revData = reviewRes.data.data || []
        if (revData.length > 0) {
          reviews.value = revData.map(r => ({
            author: r.customer ? `${r.customer.first_name || ''} ${r.customer.last_name || ''}`.trim() || 'Khách hàng' : 'Khách hàng',
            date: r.created_at ? new Date(r.created_at).toLocaleDateString('vi-VN') : 'Gần đây',
            rating: r.rating || 5,
            comment: r.comment || ''
          }))
          averageRating.value = reviewRes.data.average_rating || 0

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
          // No reviews yet — show empty state
          reviews.value = []
          averageRating.value = 0
          ratingStats.value = [
            { stars: 5, percentage: '0%', count: 0 },
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

    // Fetch related products from same category
    try {
      const categoryId = product.value?.category_id
      if (categoryId) {
        const relRes = await productService.getProducts({ category_id: categoryId, per_page: 4 })
        if (relRes.data && relRes.data.success) {
          const allCatProducts = relRes.data.data?.data || relRes.data.data || []
          relatedProducts.value = allCatProducts
            .filter(p => p.id !== product.value?.id)
            .slice(0, 4)
            .map(p => {
              const variants = p.product_variants || p.productVariants || []
              const prices = variants.map(v => v.sale_price ?? v.salePrice ?? v.price).filter(Boolean)
              const minPrice = prices.length > 0 ? Math.min(...prices) : 0
              const originalPrices = variants.map(v => v.price).filter(Boolean)
              const maxOriginal = originalPrices.length > 0 ? Math.max(...originalPrices) : 0
              const hasSale = minPrice > 0 && maxOriginal > 0 && minPrice < maxOriginal
              return {
                id: p.id,
                slug: p.slug,
                name: p.name,
                image: p.thumbnail,
                currentPrice: minPrice,
                originalPrice: hasSale ? maxOriginal : null,
                discount: hasSale ? Math.round((1 - minPrice / maxOriginal) * 100) : null
              }
            })
        }
      }
    } catch (e) {
      console.warn('Lỗi tải sản phẩm liên quan:', e)
    }

  } catch (err) {
    console.error('Lỗi khi tải chi tiết sản phẩm:', err)
  } finally {
    loading.value = false
  }
})

// Dynamic Attributes state update
const selectAttribute = (attrName, value) => {
  selectedAttributes.value[attrName] = value;
}

const isAttributeValueAvailable = (attrName, val) => {
  if (!product.value) return false;
  const variants = product.value.product_variants || product.value.productVariants || [];
  
  return variants.some(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || [];
    
    // Check if this variant has the tested value for this attribute
    const hasThisValue = attributeValues.some(av => (av.attribute?.name || 'Unknown') === attrName && av.value === val);
    if (!hasThisValue) return false;
    
    // Check stock
    const stock = variant.stock_quantity || variant.stockQuantity || 0;
    if (stock <= 0) return false;

    // Check against other currently selected attributes
    for (const [sName, sVal] of Object.entries(selectedAttributes.value)) {
      if (sName !== attrName && sVal) {
        const matchesOther = attributeValues.some(av => (av.attribute?.name || 'Unknown') === sName && av.value === sVal);
        if (!matchesOther) return false;
      }
    }
    return true;
  });
}

const selectedVariant = computed(() => {
  if (!product.value || !product.value.attributes) return null;
  const variants = product.value.product_variants || product.value.productVariants || [];
  const requiredAttrNames = Object.keys(product.value.attributes);
  
  // Must have selected all required attributes
  for (const req of requiredAttrNames) {
    if (!selectedAttributes.value[req]) return null;
  }

  return variants.find(variant => {
    const attributeValues = variant.attribute_values || variant.attributeValues || [];
    // Variant must match all selected attributes
    for (const req of requiredAttrNames) {
      const selectedVal = selectedAttributes.value[req];
      const matches = attributeValues.some(av => (av.attribute?.name || 'Unknown') === req && av.value === selectedVal);
      if (!matches) return false;
    }
    return true;
  });
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
    showNotification('Vui lòng chọn đầy đủ các tùy chọn sản phẩm.', 'error')
    return
  }
  if (selectedVariant.value.stock_quantity <= 0) {
    showNotification('Sản phẩm đã hết hàng.', 'error')
    return
  }
  
  const mappedAttributes = Object.entries(selectedAttributes.value).map(([attr, val]) => ({
    attribute: attr,
    value: val
  }))

  cartStore.addItem({
    product_variant_id: selectedVariant.value.id,
    quantity: quantity.value,
    price: currentPrice.value,
    product_name: product.value.name,
    product_thumbnail: product.value.thumbnail,
    sku: selectedVariant.value.sku,
    stock_quantity: selectedVariant.value.stock_quantity,
    attributes: mappedAttributes
  })
  
  showNotification('Đã thêm sản phẩm vào giỏ hàng thành công!', 'success')
}

// Mua ngay
const handleBuyNow = () => {
  if (!selectedVariant.value) {
    showNotification('Vui lòng chọn đầy đủ các tùy chọn sản phẩm.', 'error')
    return
  }
  if (selectedVariant.value.stock_quantity <= 0) {
    showNotification('Sản phẩm đã hết hàng.', 'error')
    return
  }

  handleAddToCart()
  
  // Chuyển hướng thẳng tới checkout
  router.push('/checkout')
}

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
