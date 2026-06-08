<template>
  <div class="w-full bg-white text-black py-10 px-5 lg:px-20 font-text">
    <!-- Breadcrumb Navigation -->
    <div class="text-[12px] uppercase tracking-[1px] text-gray-500 mb-8 font-medium">
      <span class="hover:text-black cursor-pointer transition-colors">TRANG CHỦ</span>
      <span class="mx-2">&gt;</span>
      <span class="text-black">ÁO SƠ MI - AB258041NTR26</span>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
      
      <!-- Left side: Images (6 columns, vertical thumbnails next to large image) -->
      <div class="lg:col-span-6 flex flex-col-reverse md:flex-row gap-4">
        <!-- Vertical Thumbnail Stack -->
        <div class="flex md:flex-col gap-3 md:w-[100px] shrink-0">
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

      <!-- Right side: Product Information & Controls (6 columns) -->
      <div class="lg:col-span-6 flex flex-col justify-start">
        <!-- Header & Wishlist -->
        <div class="flex justify-between items-start gap-4 mb-2">
          <h1 class="font-title text-[32px] font-bold leading-tight tracking-tight text-gray-900">
            ÁO SƠ MI - AB258041NTR26
          </h1>
          <button 
            @click="isWishlisted = !isWishlisted" 
            class="text-gray-400 hover:text-red-500 transition-colors p-1"
            aria-label="Thêm vào danh sách yêu thích"
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

        <!-- SKU Code -->
        <p class="text-[12px] text-gray-400 tracking-[1px] uppercase mb-4">
          MÃ SP: AB258041NTR26
        </p>

        <!-- Price -->
        <div class="mb-6">
          <span class="text-[26px] font-bold border-b-2 border-black pb-1">
            380.000 đ
          </span>
        </div>

        <hr class="border-gray-200 mb-6">

        <!-- Color Selector -->
        <div class="mb-6">
          <p class="text-[13px] font-bold uppercase tracking-[1px] mb-3 text-gray-700">Màu sắc:</p>
          <div class="flex gap-3">
            <button 
              @click="selectedColor = 'xanh_than'"
              class="flex items-center gap-2 px-4 py-2.5 border text-[13px] font-semibold transition-all duration-200"
              :class="selectedColor === 'xanh_than' ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'"
            >
              <span class="w-4 h-4 bg-slate-900 border border-white/20"></span>
              Xanh Than
            </button>
            <button 
              @click="selectedColor = 'den'"
              class="flex items-center gap-2 px-4 py-2.5 border text-[13px] font-semibold transition-all duration-200"
              :class="selectedColor === 'den' ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'"
            >
              <span class="w-4 h-4 bg-black border border-white/20"></span>
              Đen
            </button>
          </div>
        </div>

        <!-- Size Selector -->
        <div class="mb-6">
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

          <!-- Size suggestions inline tool (Imported Component) -->
          <SizeCalculator v-if="showSizeCalculator" class="mb-4" />

          <!-- Size Grid -->
          <div class="grid grid-cols-6 gap-2">
            <button 
              v-for="size in ['38', '39', '40', '41', '42', '43']" 
              :key="size"
              @click="selectedSize = size"
              :disabled="size === '41'" 
              class="py-3 border text-[13px] font-semibold transition-all duration-200"
              :class="[
                size === '41' ? 'border-gray-200 text-gray-300 cursor-not-allowed bg-gray-50 line-through' : '',
                selectedSize === size && size !== '41' ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'
              ]"
            >
              {{ size }}
            </button>
          </div>
        </div>

        <!-- Add To Cart Button -->
        <button 
          @click="handleAddToCart"
          class="w-full bg-[#1c1c1c] text-white hover:bg-black uppercase py-4 text-[14px] font-bold tracking-[1px] transition-all duration-300 mb-8 border border-black"
        >
          THÊM VÀO GIỎ HÀNG
        </button>

        <!-- Accordions -->
        <div class="border-t border-gray-200">
          <!-- Description Accordion -->
          <div class="border-b border-gray-200">
            <button 
              @click="toggleAccordion('mota')"
              class="w-full flex justify-between items-center py-4 text-[13px] font-bold uppercase tracking-[1px] text-left text-gray-800"
            >
              <span>MÔ TẢ</span>
              <span class="text-[18px] font-normal font-mono">{{ accordions.mota ? '-' : '+' }}</span>
            </button>
            <div 
              v-show="accordions.mota" 
              class="pb-5 text-[13px] text-gray-600 leading-relaxed font-normal"
            >
              Áo sơ mi cao cấp được chế tác từ 100% sợi cotton tự nhiên, mang lại cảm giác mềm mại và thoáng mát tối đa. Thiết kế tối giản với đường cắt may tinh tế, phù hợp cho mọi dịp từ công sở đến những buổi dạo phố cuối tuần. Form dáng chuẩn giúp tôn lên nét thanh lịch của người mặc.
            </div>
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
        average-rating="4.67" 
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
import { ref } from 'vue'
import ProductCard from '@/components/client/ui/ProductCard.vue'
import SizeCalculator from '@/components/client/ui/SizeCalculator.vue'
import ProductReviews from '@/components/client/ui/ProductReviews.vue'

// Local state
const activeImageIdx = ref(0)
const isWishlisted = ref(false)
const selectedColor = ref('xanh_than')
const selectedSize = ref('38')
const activeTab = ref('reviews')
const showSizeCalculator = ref(false)

// Accordion open/close states
const accordions = ref({
  mota: true,
  hdsd: false,
  giaohang: false
})

// Toggle accordion helper
const toggleAccordion = (section) => {
  accordions.value[section] = !accordions.value[section]
}

// Price formatter
const formatPrice = (value) => {
  return new Intl.NumberFormat('vi-VN').format(value)
}

// Add to cart action placeholder
const handleAddToCart = () => {
  alert(`Đã thêm ÁO SƠ MI - AB258041NTR26 (${selectedColor.value === 'xanh_than' ? 'Xanh Than' : 'Đen'}, Size: ${selectedSize.value}) vào giỏ hàng thành công!`)
}

// Mock Images
const productImages = ref([
  'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop', // Main suit photo
  'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=400', // Box photo
  'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?q=80&w=400', // Clip photo
  'https://images.unsplash.com/photo-1621600411688-4be93cc685e5?q=80&w=400', // Gold detail
  'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=400'  // Pocket detail
])

// Mock Review statistics
const ratingStats = ref([
  { stars: 5, percentage: '66.6%', count: 2 },
  { stars: 4, percentage: '33.3%', count: 1 },
  { stars: 3, percentage: '0%', count: 0 },
  { stars: 2, percentage: '0%', count: 0 },
  { stars: 1, percentage: '0%', count: 0 }
])

// Mock Review List
const reviews = ref([
  {
    author: 'admin',
    date: 'Tháng 8 1, 2025',
    rating: 5,
    comment: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.'
  },
  {
    author: 'admin',
    date: 'Tháng 8 1, 2025',
    rating: 4,
    comment: 'Sed ut perspiciatis unde omnis iste natus aliquid cumque nihil impedit quo minus id quod maxime placeat.'
  }
])

// Mock Size recommendations table data
const sizeTable = ref([
  { mHeight: '1m60-1m65', mWeight: '55-60kg', mSize: 'S', wHeight: '1m48-1m53', wWeight: '38-43kg', wSize: 'S' },
  { mHeight: '1m64-1m69', mWeight: '60-65kg', mSize: 'M', wHeight: '1m53-1m55', wWeight: '43-46kg', wSize: 'M' },
  { mHeight: '1m70-1m74', mWeight: '66-70kg', mSize: 'L', wHeight: '1m53-1m58', wWeight: '46-53kg', wSize: 'L' },
  { mHeight: '1m74-1m76', mWeight: '70-76kg', mSize: 'XL', wHeight: '1m55-1m62', wWeight: '53-57kg', wSize: 'XL' },
  { mHeight: '1m65-1m77', mWeight: '76-80kg', mSize: 'XXL', wHeight: '1m55-1m66', wWeight: '57-66kg', wSize: 'XXL' }
])

// Mock Related Products for reusable ProductCard
const relatedProducts = ref([
  {
    id: 101,
    name: 'Áo khoác cotton có mũ STWD',
    price: 612500,
    originalPrice: 745500,
    discount: '18',
    rating: { score: '2.0', count: 2, stars: ['filled', 'filled', 'empty', 'empty', 'empty'] },
    image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 102,
    name: 'Áo khoác cotton nhẹ',
    price: 245000,
    originalPrice: 343300,
    discount: '28',
    rating: { score: '4.3', count: 3, stars: ['filled', 'filled', 'filled', 'filled', 'half-filled'] },
    image: 'https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 103,
    name: 'Áo khoác gió thời trang',
    price: 350000,
    originalPrice: 583300,
    discount: '40',
    rating: { score: '5.0', count: 5, stars: ['filled', 'filled', 'filled', 'filled', 'filled'] },
    image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=600&auto=format&fit=crop'
  }
])
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
