<template>
  <div class="w-full">
    <!-- Hero Section -->
    <div class="relative h-[90vh] min-h-[700px] flex flex-col text-white overflow-hidden">
      <!-- Slide Backgrounds with smooth opacity transition -->
      <div 
        v-for="(slide, index) in slides" 
        :key="index"
        :style="{ backgroundImage: `linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.3)), url(${slide.image})` }"
        :class="[
          'absolute inset-0 bg-center bg-cover bg-no-repeat -z-10 transition-opacity duration-1000 ease-in-out',
          currentSlide === index ? 'opacity-100' : 'opacity-0'
        ]"
      ></div>
      <!-- Hero Content -->
      <div class="grow flex flex-col justify-center items-center text-center px-5 -mt-[50px] relative w-full select-none">
        <div 
          v-for="(slide, index) in slides"
          :key="index"
          class="absolute inset-0 flex flex-col justify-center items-center text-center px-5 transition-all duration-700 ease-in-out"
          :class="[
            currentSlide === index ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-8 scale-95 pointer-events-none'
          ]"
        >
          <h2 class="font-title text-[36px] max-md:text-[24px] font-bold mb-0 tracking-[-0.5px]">
            {{ slide.subtitle }}
          </h2>
          <h1 class="font-title text-[180px] max-lg:text-[120px] max-md:text-[80px] m-[-10px_0_20px_0] font-bold leading-none tracking-[5px] drop-shadow-[2px_4px_10px_rgba(0,0,0,0.1)]">
            {{ slide.title }}
          </h1>
        </div>
      </div>

      <!-- Slider Controls -->
      <div>
        <button 
          @click="prevSlide"
          class="absolute top-1/2 -translate-y-1/2 left-5 bg-transparent border-none text-white cursor-pointer p-5 opacity-70 transition-all duration-300 hover:opacity-100 hover:scale-110 z-10"
        >
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button 
          @click="nextSlide"
          class="absolute top-1/2 -translate-y-1/2 right-5 bg-transparent border-none text-white cursor-pointer p-5 opacity-70 transition-all duration-300 hover:opacity-100 hover:scale-110 z-10"
        >
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
      
      <div class="absolute bottom-[30px] left-1/2 -translate-x-1/2 flex gap-2.5 z-10">
        <span 
          v-for="(slide, index) in slides"
          :key="index"
          @click="setSlide(index)"
          class="w-1.5 h-1.5 rounded-full cursor-pointer transition-colors duration-300"
          :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
        ></span>
      </div>
    </div>

    <!-- Brand Logos Section (Infinite Marquee) -->
    <div class="py-8 bg-neutral-50/50 border-b border-neutral-100/50 overflow-hidden select-none relative">
      <!-- Gradient Fade Overlays for Premium Look -->
      <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-[#fbfbfb] to-transparent z-10 pointer-events-none max-md:w-8"></div>
      <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-[#fbfbfb] to-transparent z-10 pointer-events-none max-md:w-8"></div>

      <div class="marquee-wrapper overflow-hidden w-full flex">
        <div class="marquee-track flex gap-8 items-center">
          <div 
            v-for="(brand, idx) in duplicatedBrands" 
            :key="brand.domain + '-' + idx"
            class="flex-shrink-0 w-[140px] h-[60px] rounded-full bg-white border border-neutral-200/60 shadow-sm flex items-center justify-center px-6 py-2 transition-all duration-300 hover:shadow-md hover:scale-105 cursor-pointer"
          >
            <img 
              v-if="brand.logoUrl" 
              :src="brand.logoUrl" 
              :alt="brand.name"
              class="max-h-full max-w-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300"
            />
            <span 
              v-else 
              class="font-title text-[9px] tracking-[1.5px] font-bold text-neutral-400 hover:text-neutral-900 transition-colors duration-300 uppercase text-center"
            >
              {{ brand.name }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Hàng Mới Về Section -->
    <div class="py-[60px] px-5 lg:px-20 bg-white text-black max-md:py-10">
      <div class="flex justify-between items-center mb-10 max-md:flex-col max-md:items-start max-md:gap-[15px]">
        <h2 class="font-title text-[24px] font-semibold m-0 tracking-[-0.5px]">Hàng mới về</h2>
        <!-- Slider navigation -->
        <div class="flex items-center gap-3">
          <button
            @click="prevNewArrivalPage"
            :disabled="newArrivalsPage === 0"
            class="w-10 h-10 rounded-full border border-[#eaeaea] bg-white flex items-center justify-center text-[#111] shadow-sm transition-all duration-300 hover:bg-[#111] hover:text-white hover:border-[#111] disabled:opacity-30 disabled:cursor-not-allowed"
            aria-label="Trang trước"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </button>
          <button
            @click="nextNewArrivalPage"
            :disabled="newArrivalsPage >= totalNewArrivalPages - 1"
            class="w-10 h-10 rounded-full border border-[#eaeaea] bg-white flex items-center justify-center text-[#111] shadow-sm transition-all duration-300 hover:bg-[#111] hover:text-white hover:border-[#111] disabled:opacity-30 disabled:cursor-not-allowed"
            aria-label="Trang tiếp"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[30px]">
        <div 
          v-for="product in visibleNewArrivals" 
          :key="product.id"
          class="cursor-pointer"
        >
          <ProductCard 
            :id="product.id"
            :slug="product.slug"
            :image="product.image"
            :name="product.name"
            :currentPrice="product.currentPrice"
            :originalPrice="product.originalPrice"
            :discount="product.discount"
          />
        </div>
      </div>
    </div>

  

  

  

  

    <!-- SẢN PHẨM NỔI BẬT Section -->
    <div class="py-[60px] px-5 lg:px-20 bg-white text-black max-md:py-10">
      <!-- Tiêu đề căn giữa -->
      <div class="text-center mb-12">
        <h2 class="font-title text-[28px] font-bold m-0 tracking-[2px] uppercase">S&#7843;n Ph&#7849;m N&#7893;i B&#7853;t</h2>
        <div class="w-16 h-[3px] bg-black mx-auto mt-4"></div>
      </div>

      <div class="relative flex items-center">
        <button
          @click="prevFeaturedPage"
          :disabled="featuredPage === 0"
          class="absolute top-[35%] -translate-y-1/2 -left-[22px] bg-white border border-[#eaeaea] rounded-full w-11 h-11 flex items-center justify-center cursor-pointer z-10 text-[#111] shadow-[0_4px_10px_rgba(0,0,0,0.05)] transition-all duration-300 hover:bg-[#111] hover:text-white max-sm:hidden disabled:opacity-30 disabled:cursor-not-allowed"
          aria-label="Trước"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        
        <div class="w-full overflow-hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[30px]">
            <div 
              v-for="product in visibleFeaturedProducts" 
              :key="product.id"
              class="cursor-pointer"
            >
              <ProductCard 
                :id="product.id"
                :slug="product.slug"
                :image="product.image"
                :name="product.name"
                :currentPrice="product.currentPrice"
                :originalPrice="product.originalPrice"
                :discount="product.discount"
                :rating="product.rating"
              />
            </div>
          </div>
        </div>
        
        <button
          @click="nextFeaturedPage"
          :disabled="featuredPage >= totalFeaturedPages - 1"
          class="absolute top-[35%] -translate-y-1/2 -right-[22px] bg-white border border-[#eaeaea] rounded-full w-11 h-11 flex items-center justify-center cursor-pointer z-10 text-[#111] shadow-[0_4px_10px_rgba(0,0,0,0.05)] transition-all duration-300 hover:bg-[#111] hover:text-white max-sm:hidden disabled:opacity-30 disabled:cursor-not-allowed"
          aria-label="Tiếp theo"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
    </div>

    <!-- Elegant Quote Section -->
    <div class="py-[60px] px-5 lg:px-20 bg-transparent flex justify-center items-center text-center max-md:py-[40px] max-md:px-5">
      <div class="max-w-[900px] relative">
        <p class="font-title text-[42px] max-md:text-[28px] font-medium leading-[1.4] text-[#111] m-0 tracking-[0.5px] italic drop-shadow-[1px_1px_2px_rgba(0,0,0,0.05)] px-10">
          "Nâng tầm phong cách của bạn với vẻ thanh lịch vượt thời gian trong bộ sưu tập mới."
        </p>
        <div class="w-20 h-[3px] bg-black mx-auto mt-10"></div>
      </div>
    </div>


    <!-- Eco Fashion Section -->
    <div class="flex py-[100px] px-5 lg:px-20 gap-[50px] items-center bg-white max-lg:flex-col max-lg:py-[60px]">
      <div class="flex-1 max-w-[450px] max-lg:max-w-none max-lg:text-center max-lg:mb-10">
        <h2 class="font-title text-[46px] font-normal leading-[1.2] text-[#111] m-[0_0_20px_0] tracking-[1px]">THỜI TRANG YÊU<br>THƯƠNG TRÁI ĐẤT</h2>
        <p class="font-text text-[14px] text-[#555] leading-[1.6] mb-[35px] mt-0">Được thiết kế có trách nhiệm cho cuộc sống hàng ngày. Những đường nét tinh tế, chất liệu tự nhiên, và phong cách trường tồn vượt thời gian.</p>
        <button class="bg-black text-white border-none py-[15px] px-[35px] font-text text-[12px] font-bold tracking-[1px] uppercase cursor-pointer transition-colors duration-300 hover:bg-[#333]">KHÁM PHÁ NGAY</button>
      </div>
      <div class="flex-1.5 flex gap-10 items-center justify-end max-lg:w-full max-lg:justify-center">
        <div class="w-[55%] aspect-[3/4]">
          <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=800&auto=format&fit=crop" alt="Áo khoác thời trang" class="w-full h-full object-cover">
        </div>
        <div class="w-[40%] aspect-[3/4] h-[70%]">
          <img src="https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?q=80&w=800&auto=format&fit=crop" alt="Áo thun cơ bản" class="w-full h-full object-cover">
        </div>
      </div>
    </div>
   

    <!-- Blog Section -->
    <div class="pt-[60px] pb-[100px] px-5 lg:px-20 bg-white">
      <!-- Blog Header -->
      <div class="flex justify-between items-center mb-10">
        <h2 class="font-title text-[24px] font-semibold m-0 tracking-[-0.5px]">Tin Tức &amp; Bài Viết</h2>
        <div class="flex items-center gap-3">
          <button
            @click="prevBlogPage"
            :disabled="blogPage === 0"
            class="w-10 h-10 rounded-full border border-[#eaeaea] bg-white flex items-center justify-center text-[#111] shadow-sm transition-all duration-300 hover:bg-[#111] hover:text-white hover:border-[#111] disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-[#111]"
            aria-label="Blog trước"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </button>
          <button
            @click="nextBlogPage"
            :disabled="blogPage >= totalBlogPages - 1"
            class="w-10 h-10 rounded-full border border-[#eaeaea] bg-white flex items-center justify-center text-[#111] shadow-sm transition-all duration-300 hover:bg-[#111] hover:text-white hover:border-[#111] disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-[#111]"
            aria-label="Blog tiếp theo"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>
      </div>

      <!-- Blog Grid -->
      <div v-if="blogs.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[30px]">
        <BlogCard 
          v-for="blog in visibleBlogs" 
          :key="blog.id"
          :id="blog.id"
          :slug="blog.slug"
          :image="blog.image"
          :category="blog.category"
          :title="blog.title"
          :author="blog.author"
          :date="blog.date"
        />
      </div>

      <!-- Fallback: loading or empty -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[30px]">
        <div v-for="i in 4" :key="i" class="flex flex-col gap-[15px] animate-pulse">
          <div class="w-full aspect-[4/3] bg-[#f0f0f0] rounded"></div>
          <div class="h-3 bg-[#f0f0f0] rounded w-1/3"></div>
          <div class="h-4 bg-[#f0f0f0] rounded w-full"></div>
          <div class="h-3 bg-[#f0f0f0] rounded w-2/3"></div>
        </div>
      </div>
    </div>
   

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import ProductCard from '@/components/client/ui/ProductCard.vue';
import BlogCard from '@/components/client/ui/BlogCard.vue';
import { productService } from '@/services/client/productService';
import { blogService } from '@/services/client/blogService';
import api from '@/plugins/axios';

const slides = [
  {
    image: '/product homepage/phuc1.jpg',
    subtitle: 'Xu Hướng Mùa Hè Này',
    title: 'Luxury'
  },
  {
    image: '/product homepage/phuc2.jpg',
    subtitle: 'Độc Đáo & Tinh Tế',
    title: 'Luxury'
  }
];

const currentSlide = ref(0);
let slideInterval = null;

// Brand Logos state (Hardcoded since backend doesn't manage this)
const brandLogos = ref([
  { name: 'Gucci', domain: 'gucci.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=GUCCI&font=lora' },
  { name: 'Balenciaga', domain: 'balenciaga.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=BALENCIAGA&font=lora' },
  { name: 'Louis Vuitton', domain: 'louisvuitton.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=LOUIS+VUITTON&font=lora' },
  { name: 'Dior', domain: 'dior.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=DIOR&font=lora' },
  { name: 'Chanel', domain: 'chanel.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=CHANEL&font=lora' },
  { name: 'Hermès', domain: 'hermes.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=HERMÈS&font=lora' },
  { name: 'Prada', domain: 'prada.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=PRADA&font=lora' },
  { name: 'Versace', domain: 'versace.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=VERSACE&font=lora' },
  { name: 'Burberry', domain: 'burberry.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=BURBERRY&font=lora' },
  { name: 'Armani', domain: 'armani.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=ARMANI&font=lora' },
  { name: 'Fendi', domain: 'fendi.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=FENDI&font=lora' },
  { name: 'Givenchy', domain: 'givenchy.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=GIVENCHY&font=lora' },
  { name: 'YSL', domain: 'ysl.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=YSL&font=lora' },
  { name: 'Rolex', domain: 'rolex.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=ROLEX&font=lora' },
  { name: 'Cartier', domain: 'cartier.com', logoUrl: 'https://placehold.co/400x150/ffffff/000000/png?text=CARTIER&font=lora' }
]);

// Nhân bản danh sách thương hiệu nhiều lần để cuộn vô hạn mượt mà
const duplicatedBrands = computed(() => {
  return [
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value,
    ...brandLogos.value
  ];
});

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

const setSlide = (index) => {
  currentSlide.value = index;
};

const startSlideTimer = () => {
  if (slideInterval) clearInterval(slideInterval);
  slideInterval = setInterval(nextSlide, 10000);
};

onMounted(() => {
  startSlideTimer();
});

onUnmounted(() => {
  if (slideInterval) clearInterval(slideInterval);
});

const newArrivals = ref([]);
const featuredProducts = ref([]);
const newArrivalsPage = ref(0)
const featuredPage = ref(0)
const PRODUCTS_PER_PAGE = 4

const totalNewArrivalPages = computed(() =>
  Math.max(1, Math.ceil(newArrivals.value.length / PRODUCTS_PER_PAGE))
)
const visibleNewArrivals = computed(() => {
  const start = newArrivalsPage.value * PRODUCTS_PER_PAGE
  return newArrivals.value.slice(start, start + PRODUCTS_PER_PAGE)
})
const prevNewArrivalPage = () => { if (newArrivalsPage.value > 0) newArrivalsPage.value-- }
const nextNewArrivalPage = () => { if (newArrivalsPage.value < totalNewArrivalPages.value - 1) newArrivalsPage.value++ }

const totalFeaturedPages = computed(() =>
  Math.max(1, Math.ceil(featuredProducts.value.length / PRODUCTS_PER_PAGE))
)
const visibleFeaturedProducts = computed(() => {
  const start = featuredPage.value * PRODUCTS_PER_PAGE
  return featuredProducts.value.slice(start, start + PRODUCTS_PER_PAGE)
})
const prevFeaturedPage = () => { if (featuredPage.value > 0) featuredPage.value-- }
const nextFeaturedPage = () => { if (featuredPage.value < totalFeaturedPages.value - 1) featuredPage.value++ }

const router = useRouter();

const formatPrice = (value) => {
  if (!value) return '0 đ'
  return Number(value).toLocaleString('vi-VN') + ' đ'
}

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const mapProduct = (item) => {
  const variants = item.product_variants || item.productVariants || []
  const firstVariant = variants[0]
  const currentPriceVal = firstVariant 
    ? (firstVariant.sale_price || firstVariant.price) 
    : 0
  const originalPriceVal = firstVariant && firstVariant.sale_price 
    ? firstVariant.price 
    : null

  let discount = null
  if (firstVariant && firstVariant.sale_price && firstVariant.price > 0) {
    const pct = Math.round((1 - (firstVariant.sale_price / firstVariant.price)) * 100)
    if (pct > 0) {
      discount = `-${pct}%`
    }
  }

  let rawImage = item.thumbnail
  if (!rawImage && item.product_images && item.product_images.length > 0) {
    rawImage = item.product_images[0].image_url
  }
  const image = getImageUrl(rawImage)

  // Build rating from reviews_avg_rating if available
  const avgRating = item.reviews_avg_rating ? parseFloat(item.reviews_avg_rating).toFixed(1) : null
  const reviewCount = item.reviews_count || 0
  let rating = null
  if (avgRating) {
    const score = parseFloat(avgRating)
    const stars = [1,2,3,4,5].map(s => {
      if (score >= s) return 'filled'
      if (score >= s - 0.5) return 'half-filled'
      return 'empty'
    })
    rating = { score: avgRating, count: reviewCount, stars }
  }

  return {
    id: item.id,
    slug: item.slug || String(item.id),
    image: image,
    name: item.name,
    currentPrice: formatPrice(currentPriceVal),
    originalPrice: originalPriceVal ? formatPrice(originalPriceVal) : null,
    discount: discount,
    rating: rating
  }
}

const goToDetail = (slugOrId) => {
  router.push({ name: 'ProductDetail', params: { slug: slugOrId } })
}

// Fetch Hàng Mới Về (latest)
onMounted(async () => {
  try {
    const response = await productService.getProducts({ per_page: 12, sort: 'latest' })
    if (response.data && response.data.success) {
      newArrivals.value = response.data.data.map(mapProduct)
    }
  } catch (err) {
    console.error('Lỗi khi tải hàng mới về:', err)
  }
});

// Fetch Sản phẩm nổi bật (top-rated)
onMounted(async () => {
  try {
    const response = await productService.getTopRatedProducts(8)
    if (response.data && response.data.success) {
      featuredProducts.value = response.data.data.map(mapProduct)
    }
  } catch (err) {
    // Fallback: dùng sản phẩm mới nếu chưa có đánh giá
    console.warn('Fallback to latest products for featured:', err)
  }
});


// ─── Blog Slider ──────────────────────────────────────────────────────────────
const blogs = ref([])
const blogPage = ref(0)
const BLOGS_PER_PAGE = 4

const totalBlogPages = computed(() =>
  Math.max(1, Math.ceil(blogs.value.length / BLOGS_PER_PAGE))
)

const visibleBlogs = computed(() => {
  const start = blogPage.value * BLOGS_PER_PAGE
  return blogs.value.slice(start, start + BLOGS_PER_PAGE)
})

const nextBlogPage = () => {
  if (blogPage.value < totalBlogPages.value - 1) blogPage.value++
}
const prevBlogPage = () => {
  if (blogPage.value > 0) blogPage.value--
}

const getBlogImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=600&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

onMounted(async () => {
  try {
    const res = await blogService.getBlogs({ per_page: 20 })
    if (res.data && res.data.data && res.data.data.length > 0) {
      blogs.value = res.data.data.map(b => ({
        id: b.id,
        slug: b.slug || String(b.id),
        image: getBlogImageUrl(b.image),
        category: 'TIN TỨC',
        title: b.name,
        author: 'Luxury',
        date: b.created_at || ''
      }))
    }
  } catch (err) {
    console.error('Lỗi khi tải bài viết:', err)
  }
})
</script>

<style scoped>
.marquee-wrapper {
  width: 100%;
}
.marquee-track {
  display: flex;
  width: max-content;
  animation: marquee 30s linear infinite;
}
/* Tự động dừng nhẹ khi hover chuột để tăng trải nghiệm */
.marquee-track:hover {
  animation-play-state: paused;
}

@keyframes marquee {
  0% {
    transform: translateX(0);
  }
  100% {
    /* Cuộn một nửa chiều rộng danh sách nhân bản để lặp vô hạn mượt mà */
    transform: translateX(-50%);
  }
}
</style>

