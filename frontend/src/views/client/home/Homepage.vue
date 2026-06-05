<template>
  <div class="w-full">
    <!-- Hero Section -->
    <div class="relative h-[90vh] min-h-[700px] flex flex-col text-white overflow-hidden">
      <!-- Background placeholder since user will add banner later -->
      <div class="absolute inset-0 bg-gradient-to-b from-[rgba(0,0,0,0.4)] to-[rgba(0,0,0,0.3)] bg-[url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop')] bg-center bg-cover bg-no-repeat -z-10"></div>
      <!-- Hero Content -->
      <div class="grow flex flex-col justify-center items-center text-center px-5 -mt-[50px]">
        <h2 class="font-title text-[36px] max-md:text-[24px] font-bold mb-0 tracking-[-0.5px]">Xu Hướng Mùa Hè Này</h2>
        <h1 class="font-title text-[180px] max-lg:text-[120px] max-md:text-[80px] m-[-10px_0_20px_0] font-bold leading-none tracking-[5px] drop-shadow-[2px_4px_10px_rgba(0,0,0,0.1)]">NURFIA</h1>
        <p class="font-text text-[16px] max-md:text-[14px] leading-[1.6] max-w-[650px] mx-auto mb-10 font-normal drop-shadow-[1px_1px_3px_rgba(0,0,0,0.3)]">
          Được thiết kế cho những ai trân trọng sự tối giản và mạnh mẽ. Hình dáng đương đại, chi tiết cân đối
          <br>và phong thái tự tin trong mùa hè.
        </p>
        <button class="bg-white text-black border-none py-4 px-9 text-[13px] font-bold uppercase tracking-[1px] cursor-pointer transition-all duration-300 hover:bg-black hover:text-white">XEM BỘ SƯU TẬP</button>
      </div>

      <!-- Slider Controls (Mockup) -->
      <div>
        <button class="absolute top-1/2 -translate-y-1/2 left-5 bg-transparent border-none text-white cursor-pointer p-5 opacity-70 transition-all duration-300 hover:opacity-100 hover:scale-110">
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="absolute top-1/2 -translate-y-1/2 right-5 bg-transparent border-none text-white cursor-pointer p-5 opacity-70 transition-all duration-300 hover:opacity-100 hover:scale-110">
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
      
      <div class="absolute bottom-[30px] left-1/2 -translate-x-1/2 flex gap-2.5">
        <span class="w-1.5 h-1.5 bg-white rounded-full cursor-pointer transition-colors duration-300"></span>
        <span class="w-1.5 h-1.5 bg-white/50 rounded-full cursor-pointer transition-colors duration-300"></span>
      </div>
    </div>

    <!-- Redefine Your Wardrobe Section -->
    <div class="py-[60px] px-5 lg:px-20 bg-white text-black max-md:py-10">
      <div class="flex justify-between items-center mb-10 max-md:flex-col max-md:items-start max-md:gap-[15px]">
        <h2 class="font-title text-[24px] font-semibold m-0 tracking-[-0.5px]">Định Hình Lại Tủ Đồ Của Bạn</h2>
        <div class="flex gap-6">
          <a href="#" class="no-underline text-black font-text text-[14px] font-medium transition-colors duration-300">Váy</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Nam</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Áo thun</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Nữ</a>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[30px]">
        <ProductCard 
          v-for="product in newArrivals" 
          :key="product.id"
          :image="product.image"
          :name="product.name"
          :currentPrice="product.currentPrice"
          :originalPrice="product.originalPrice"
          :discount="product.discount"
        />
      </div>
    </div>

    <!-- Category Banners Section -->
    <div class="flex w-full h-[600px] max-md:flex-col max-md:h-auto">
      <div class="flex-1 relative flex items-end justify-center pb-[50px] bg-cover bg-center bg-no-repeat text-center text-white transition-[flex] duration-500 ease-in-out overflow-hidden max-md:h-[400px] before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-t before:from-black/60 before:to-transparent before:z-10 before:transition-opacity before:duration-300 hover:before:opacity-80 bg-[url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=1200&auto=format&fit=crop')]">
        <div class="relative z-20 max-w-[400px] px-5">
          <h3 class="font-title text-[32px] font-normal tracking-[2px] m-[0_0_15px_0]">NỮ</h3>
          <p class="font-text text-[13px] leading-[1.6] opacity-90 m-0">Sức mạnh mềm mại, chi tiết tinh tế, và hình dáng tự tin được định hình cho phong cách đương đại.</p>
        </div>
      </div>
      <div class="flex-1 relative flex items-end justify-center pb-[50px] bg-cover bg-center bg-no-repeat text-center text-white transition-[flex] duration-500 ease-in-out overflow-hidden max-md:h-[400px] before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-t before:from-black/60 before:to-transparent before:z-10 before:transition-opacity before:duration-300 hover:before:opacity-80 bg-[url('https://images.unsplash.com/photo-1617137968427-85924c800a22?q=80&w=1200&auto=format&fit=crop')]">
        <div class="relative z-20 max-w-[400px] px-5">
          <h3 class="font-title text-[32px] font-normal tracking-[2px] m-[0_0_15px_0]">NAM</h3>
          <p class="font-text text-[13px] leading-[1.6] opacity-90 m-0">Cấu trúc rõ ràng, phong thái hiện đại, và những thiết kế thoải mái dành cho sự tự tin mỗi ngày.</p>
        </div>
      </div>
    </div>

    <!-- Elegant Quote Section -->
    <div class="py-[100px] px-5 lg:px-20 bg-transparent flex justify-center items-center text-center max-md:py-[60px] max-md:px-5">
      <div class="max-w-[900px] relative">
        <h2 class="font-title text-[42px] max-md:text-[28px] font-medium leading-[1.4] text-[#111] m-0 tracking-[0.5px] italic drop-shadow-[1px_1px_2px_rgba(0,0,0,0.05)]">
          "Nâng tầm phong cách của bạn với vẻ thanh lịch vượt thời gian trong bộ sưu tập mới."
        </h2>
        <div class="w-20 h-[3px] bg-black mx-auto mt-10"></div>
      </div>
    </div>

    <!-- Featured Products Section -->
    <div class="py-[60px] px-5 lg:px-20 bg-white text-black max-md:py-10">
      <div class="flex justify-between items-center mb-10 max-md:flex-col max-md:items-start max-md:gap-[15px]">
        <h2 class="font-title text-[24px] font-semibold m-0 tracking-[-0.5px]">Sản Phẩm Nổi Bật</h2>
        <div class="flex gap-6">
          <a href="#" class="no-underline text-black font-text text-[14px] font-medium transition-colors duration-300">Áo khoác</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Nam</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Áo</a>
          <a href="#" class="no-underline text-[#888] font-text text-[14px] font-medium transition-colors duration-300 hover:text-black">Nữ</a>
        </div>
      </div>

      <div class="relative flex items-center">
        <button class="absolute top-[35%] -translate-y-1/2 -left-[22px] bg-white border border-[#eaeaea] rounded-full w-11 h-11 flex items-center justify-center cursor-pointer z-10 text-[#111] shadow-[0_4px_10px_rgba(0,0,0,0.05)] transition-all duration-300 hover:bg-[#111] hover:text-white max-sm:hidden" aria-label="Trước">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        
        <div class="w-full overflow-hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[30px]">
            <ProductCard 
              v-for="product in featuredProducts" 
              :key="product.id"
              :image="product.image"
              :name="product.name"
              :currentPrice="product.currentPrice"
              :originalPrice="product.originalPrice"
              :discount="product.discount"
              :rating="product.rating"
            />
          </div>
        </div>
        
        <button class="absolute top-[35%] -translate-y-1/2 -right-[22px] bg-white border border-[#eaeaea] rounded-full w-11 h-11 flex items-center justify-center cursor-pointer z-10 text-[#111] shadow-[0_4px_10px_rgba(0,0,0,0.05)] transition-all duration-300 hover:bg-[#111] hover:text-white max-sm:hidden" aria-label="Tiếp theo">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>

      <div class="flex justify-center gap-2 mt-10">
        <span class="w-1.5 h-1.5 bg-[#111] rounded-full cursor-pointer transition-colors duration-300"></span>
        <span class="w-1.5 h-1.5 bg-[#ccc] rounded-full cursor-pointer transition-colors duration-300"></span>
        <span class="w-1.5 h-1.5 bg-[#ccc] rounded-full cursor-pointer transition-colors duration-300"></span>
        <span class="w-1.5 h-1.5 bg-[#ccc] rounded-full cursor-pointer transition-colors duration-300"></span>
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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[30px]">
        <BlogCard 
          v-for="blog in blogs" 
          :key="blog.id"
          :image="blog.image"
          :category="blog.category"
          :title="blog.title"
          :author="blog.author"
          :date="blog.date"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import ProductCard from '@/components/client/ui/ProductCard.vue';
import BlogCard from '@/components/client/ui/BlogCard.vue';

const newArrivals = ref([
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop',
    name: 'Váy midi tulle bất đối xứng',
    currentPrice: '$30.00',
    originalPrice: '$50.00',
    discount: '40%'
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?q=80&w=800&auto=format&fit=crop',
    name: 'Váy midi hiệu ứng nhăn',
    currentPrice: '$34.11',
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1515347619253-122e1b1d403f?q=80&w=800&auto=format&fit=crop',
    name: 'Váy quây xếp nếp',
    currentPrice: '$54.33',
    originalPrice: '$66.99',
    discount: '19%'
  },
  {
    id: 4,
    image: 'https://images.unsplash.com/photo-1612336307429-8a898d10e223?q=80&w=800&auto=format&fit=crop',
    name: 'Váy midi crepe họa tiết hoa',
    currentPrice: '$61.32',
    originalPrice: '$78.33',
    discount: '22%'
  }
]);

const featuredProducts = ref([
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=800&auto=format&fit=crop',
    name: "Áo hoodie 'Run & Brunch'",
    currentPrice: '$59.99',
    originalPrice: '$79.99',
    discount: '26%',
    rating: { score: '4.67', count: 3, stars: ['filled', 'filled', 'filled', 'filled', 'half-filled'] }
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop',
    name: 'Áo khoác cotton siêu nhẹ',
    currentPrice: '$24.90',
    originalPrice: '$34.33',
    discount: '28%',
    rating: { score: '4.33', count: 3, stars: ['filled', 'filled', 'filled', 'filled', 'half-filled'] }
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop',
    name: 'Áo khoác denim',
    currentPrice: '$44.96',
    originalPrice: '$74.93',
    discount: '40%',
    rating: { score: '3.67', count: 5, stars: ['filled', 'filled', 'filled', 'half-filled', 'empty'] }
  },
  {
    id: 4,
    image: 'https://images.unsplash.com/photo-1599305090598-fe179d501227?q=80&w=800&auto=format&fit=crop',
    name: 'Áo khoác bomber 100% linen',
    currentPrice: '$34.76 - $19.66',
    rating: { score: '4.33', count: 6, stars: ['filled', 'filled', 'filled', 'filled', 'half-filled'] }
  }
]);

const blogs = ref([
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=600&auto=format&fit=crop',
    category: 'MẸO THỜI TRANG',
    title: 'Trò Chuyện Về Thời Trang Bền Vững Với Một Influencer Thân Thiện Môi Trường',
    author: 'Simam',
    date: '9 tháng 7, 2025'
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop',
    category: 'MẸO THỜI TRANG',
    title: 'Những Trang Phục Đáng Nhớ Nhất Từ Thảm Đỏ Oscar',
    author: 'Simam',
    date: '9 tháng 7, 2025'
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?q=80&w=600&auto=format&fit=crop',
    category: 'XU HƯỚNG MỚI',
    title: 'Những Phong Cách Street Style Đẹp Nhất Từ Tuần Lễ Thời Trang London',
    author: 'Simam',
    date: '9 tháng 7, 2025'
  },
  {
    id: 4,
    image: 'https://images.unsplash.com/photo-1492447166138-50c3889f1b1a?q=80&w=600&auto=format&fit=crop',
    category: 'MẸO THỜI TRANG',
    title: 'Nhìn Lại Những Lễ Trao Giải Thời Trang Có Ảnh Hưởng Nhất',
    author: 'Simam',
    date: '9 tháng 7, 2025'
  }
]);
</script>
