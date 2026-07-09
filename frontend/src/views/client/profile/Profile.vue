<script setup>
import ProfileSidebar from '@/components/client/profile/ProfileSidebar.vue';
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 select-none">
    
    <div class="flex flex-col md:flex-row gap-8">
      
      <div class="w-full md:w-64 flex-shrink-0">
        <ProfileSidebar />
      </div>

      <div class="flex-grow bg-white space-y-10 animate-fade-in text-[#111111]">
        
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-gray-900">Chào bạn, Anh Tuấn!</h1>
          <p class="text-sm text-gray-400 mt-1 font-normal">Chào mừng bạn quay lại</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="border border-gray-900 bg-[#FBFBFB] p-6 min-h-[140px] flex flex-col justify-between">
            <p class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold">Đơn đặt gần đây</p>
            <div class="mt-4">
              <span class="text-4xl font-medium tracking-tight">12</span>
              <p class="text-xs text-gray-400 mt-1">Tổng số đơn đã hoàn thành</p>
            </div>
          </div>

          <div class="border border-gray-200 bg-white p-6 min-h-[140px] flex flex-col justify-between">
            <p class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold">Voucher</p>
            <div class="mt-4">
              <span class="text-4xl font-medium tracking-tight">03</span>
              <p class="text-xs text-gray-400 mt-1">Sẵn sàng cho thanh toán</p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="border border-gray-200 p-6 bg-[#FBFBFB] flex flex-col justify-between min-h-[260px]">
            <div>
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider">Thông tin cá nhân</h3>
                <button class="text-xs font-bold uppercase tracking-wider underline hover:text-gray-600 transition-colors">
                  EDIT
                </button>
              </div>
              
              <div class="space-y-4">
                <div>
                  <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Tên</p>
                  <p class="text-sm font-normal text-gray-800 mt-0.5">Anh Tuấn Nguyễn</p>
                </div>
                <div>
                  <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Email</p>
                  <p class="text-sm font-normal text-gray-800 mt-0.5">anhtuan.n@luxury-mail.com</p>
                </div>
                <div>
                  <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Số điện thoại</p>
                  <p class="text-sm font-normal text-gray-800 mt-0.5">+84 90 123 4567</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative overflow-hidden bg-black min-h-[260px] flex items-center justify-center p-8 group">
            <img 
              src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop" 
              alt="New Collection" 
              class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale group-hover:scale-105 transition-transform duration-700"
            />
            <div class="absolute inset-0 bg-black/20"></div>
            
            <div class="relative z-10 text-center space-y-4 w-full">
              <div>
                <span class="inline-block bg-white text-[9px] font-bold uppercase tracking-[0.2em] text-gray-900 px-3 py-1">
                  New Collection
                </span>
              </div>
              <h4 class="text-2xl font-bold tracking-[0.15em] text-white uppercase leading-tight">
                Essentials<br>Autumn '24
              </h4>
              <div>
                <button class="inline-block border border-white text-white text-[10px] font-bold uppercase tracking-widest px-6 py-2.5 hover:bg-white hover:text-gray-900 transition-all duration-300">
                  Shop Now
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4 pt-4">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider">Đơn đã đặt</h3>
            <router-link to="/profile/orders" class="text-xs font-bold uppercase tracking-wider underline hover:text-gray-600 transition-colors">
              XEM TẤT CẢ
            </router-link>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[650px]">
              <thead>
                <tr class="border-b-2 border-gray-900">
                  <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Order ID</th>
                  <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Date</th>
                  <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Status</th>
                  <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5">Total</th>
                  <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 w-1/5 text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-xs font-medium">
                <tr class="hover:bg-gray-50/50 transition-colors">
                  <td class="py-4 font-bold tracking-wide">#NF-882941</td>
                  <td class="py-4 text-gray-500 font-normal">Oct 12, 2023</td>
                  <td class="py-4">
                    <span class="inline-block text-[9px] font-bold tracking-wider uppercase border border-gray-400 text-gray-700 bg-white px-2 py-0.5 rounded-none scale-95 origin-left">
                      DELIVERED
                    </span>
                  </td>
                  <td class="py-4 font-normal text-gray-800">$1,250.00</td>
                  <td class="py-4 text-right">
                    <button class="text-[10px] font-bold uppercase tracking-wider underline hover:text-gray-500">DETAILS</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50/50 transition-colors">
                  <td class="py-4 font-bold tracking-wide">#NF-871032</td>
                  <td class="py-4 text-gray-500 font-normal">Sep 28, 2023</td>
                  <td class="py-4">
                    <span class="inline-block text-[9px] font-bold tracking-wider uppercase border border-gray-400 text-gray-700 bg-white px-2 py-0.5 rounded-none scale-95 origin-left">
                      IN TRANSIT
                    </span>
                  </td>
                  <td class="py-4 font-normal text-gray-800">$420.00</td>
                  <td class="py-4 text-right">
                    <button class="text-[10px] font-bold uppercase tracking-wider underline hover:text-gray-500">DETAILS</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50/50 transition-colors">
                  <td class="py-4 font-bold tracking-wide">#NF-865510</td>
                  <td class="py-4 text-gray-500 font-normal">Sep 15, 2023</td>
                  <td class="py-4">
                    <span class="inline-block text-[9px] font-bold tracking-wider uppercase border border-gray-400 text-gray-700 bg-white px-2 py-0.5 rounded-none scale-95 origin-left">
                      DELIVERED
                    </span>
                  </td>
                  <td class="py-4 font-normal text-gray-800">$2,100.00</td>
                  <td class="py-4 text-right">
                    <button class="text-[10px] font-bold uppercase tracking-wider underline hover:text-gray-500">DETAILS</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>


<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>