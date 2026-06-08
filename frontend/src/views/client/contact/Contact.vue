<template>
  <div class="w-full bg-white text-black py-12 px-5 lg:px-20 font-text">
    
    <!-- Title & Intro Section -->
    <div class="mb-16 max-w-[800px] mx-auto">
      <h1 class="font-title text-[48px] md:text-[60px] font-bold tracking-wide mb-6 text-gray-900 uppercase text-center">
        LIÊN HỆ
      </h1>
      <p class="text-[15px] md:text-[16px] text-gray-600 leading-relaxed font-normal text-center">
        Trải nghiệm sự tinh tế trong từng điểm chạm. Đội ngũ trợ lý riêng của Luxury luôn sẵn sàng hỗ trợ bạn kiến tạo phong cách cá nhân độc bản.
      </p>
    </div>

    <!-- Grid Columns: Direct Links vs Office Info -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 mb-20 items-start">
      
      <!-- Left Column: Direct Connect & Gmail Expanded details -->
      <div class="lg:col-span-7 flex flex-col gap-6">
        <div>
          <h2 class="text-[13px] font-bold uppercase tracking-[2px] text-gray-400 mb-2">
            LIÊN HỆ TRỰC TIẾP
          </h2>
          <p class="text-[14px] text-gray-500 leading-relaxed font-normal mb-8">
            Hãy kết nối với chúng tôi qua các kênh truyền thông chính thức để được hỗ trợ nhanh nhất.
          </p>
        </div>

        <!-- Contact Links List -->
        <div class="border-t border-gray-200">
          <!-- Facebook Link -->
          <a 
            href="https://facebook.com" 
            target="_blank" 
            class="flex justify-between items-center py-5 border-b border-gray-200 hover:opacity-75 transition-opacity group"
          >
            <span class="text-[14px] font-bold uppercase tracking-[1.5px] text-gray-800">FACEBOOK</span>
            <span class="text-[18px] text-gray-400 group-hover:text-black group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-200">↗</span>
          </a>

          <!-- Gmail - Detailed Expansion -->
          <div class="border-b border-gray-200">
            <button 
              @click="isGmailExpanded = !isGmailExpanded"
              class="w-full flex justify-between items-center py-5 hover:opacity-75 transition-opacity group text-left"
            >
              <span class="text-[14px] font-bold uppercase tracking-[1.5px] text-gray-800">GMAIL</span>
              <span 
                class="text-[18px] text-gray-400 font-mono transition-transform duration-300"
                :class="isGmailExpanded ? 'rotate-90 text-black' : ''"
              >
                @
              </span>
            </button>
            
            <!-- Gmail Expanded Detail Section -->
            <div 
              v-show="isGmailExpanded"
              class="pb-8 pt-2 transition-all duration-300"
            >
              <!-- Department Emails -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 bg-gray-50 p-5 border border-gray-100 rounded-sm">
                <div>
                  <h4 class="text-[11px] font-bold uppercase tracking-[1px] text-gray-400 mb-1">Chăm sóc khách hàng</h4>
                  <a href="mailto:support@luxury.com" class="text-[13px] font-semibold text-black hover:underline">support@luxury.com</a>
                </div>
                <div>
                  <h4 class="text-[11px] font-bold uppercase tracking-[1px] text-gray-400 mb-1">Hợp tác & Kinh doanh</h4>
                  <a href="mailto:partner@luxury.com" class="text-[13px] font-semibold text-black hover:underline">partner@luxury.com</a>
                </div>
                <div>
                  <h4 class="text-[11px] font-bold uppercase tracking-[1px] text-gray-400 mb-1">Truyền thông & Báo chí</h4>
                  <a href="mailto:press@luxury.com" class="text-[13px] font-semibold text-black hover:underline">press@luxury.com</a>
                </div>
              </div>

              <!-- Quick Contact Form -->
              <form @submit.prevent="handleFormSubmit" class="space-y-4">
                <h3 class="text-[13px] font-bold uppercase tracking-[1px] text-gray-800 mb-2">Gửi tin nhắn trực tiếp</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-[10px] text-gray-500 uppercase mb-1">Họ tên của bạn</label>
                    <input 
                      type="text" 
                      v-model="form.name" 
                      required 
                      placeholder="Ví dụ: Nguyễn Văn A"
                      class="w-full px-4 py-2.5 border border-gray-300 text-[13px] focus:outline-none focus:border-black bg-white rounded-none"
                    >
                  </div>
                  <div>
                    <label class="block text-[10px] text-gray-500 uppercase mb-1">Địa chỉ Email</label>
                    <input 
                      type="email" 
                      v-model="form.email" 
                      required 
                      placeholder="Ví dụ: email@gmail.com"
                      class="w-full px-4 py-2.5 border border-gray-300 text-[13px] focus:outline-none focus:border-black bg-white rounded-none"
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-[10px] text-gray-500 uppercase mb-1">Nội dung liên hệ</label>
                  <textarea 
                    v-model="form.message" 
                    required 
                    rows="4" 
                    placeholder="Hãy viết thắc mắc hoặc yêu cầu hỗ trợ của bạn..."
                    class="w-full px-4 py-2.5 border border-gray-300 text-[13px] focus:outline-none focus:border-black bg-white rounded-none"
                  ></textarea>
                </div>
                <div>
                  <button 
                    type="submit" 
                    class="bg-black text-white hover:bg-gray-800 uppercase px-8 py-3 text-[12px] font-bold tracking-[1.5px] transition-colors duration-300"
                  >
                    GỬI ĐI
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Instagram Link -->
          <a 
            href="https://instagram.com" 
            target="_blank" 
            class="flex justify-between items-center py-5 border-b border-gray-200 hover:opacity-75 transition-opacity group"
          >
            <span class="text-[14px] font-bold uppercase tracking-[1.5px] text-gray-800">INSTAGRAM</span>
            <span class="text-[18px] text-gray-400 group-hover:text-black group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-200">↗</span>
          </a>
        </div>
      </div>

      <!-- Right Column: Office Information Block -->
      <div class="lg:col-span-5 bg-[#fafafa] border border-gray-200 p-8 md:p-10 flex flex-col gap-8">
        <!-- Address -->
        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            ĐỊA CHỈ VĂN PHÒNG
          </h3>
          <p class="text-[14px] text-gray-800 font-medium leading-relaxed">
            428 Soho Architecture Loft,<br>
            NY 10012, United States
          </p>
        </div>

        <!-- Phone & General Email -->
        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            KẾT NỐI TRỰC TIẾP
          </h3>
          <p class="text-[14px] text-gray-800 font-medium space-y-1">
            <span class="block">Phone: +44 20 7946 0123</span>
            <span class="block">Email: concierge@luxury.com</span>
          </p>
        </div>

        <!-- Working Hours -->
        <div>
          <h3 class="text-[11px] font-bold uppercase tracking-[2px] text-gray-400 mb-3">
            GIỜ LÀM VIỆC
          </h3>
          <div class="text-[13px] text-gray-800 font-medium divide-y divide-gray-200/50">
            <div class="flex justify-between py-2">
              <span class="text-gray-500">Thứ 2 - Thứ 6</span>
              <span>9AM - 6PM</span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-500">Thứ 7</span>
              <span>10AM - 4PM</span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-500">Chủ nhật</span>
              <span class="text-red-500">Đóng cửa</span>
            </div>
          </div>
        </div>

        <!-- Social text links -->
        <div class="flex gap-6 pt-4 border-t border-gray-200 text-[11px] font-bold uppercase tracking-[1.5px] text-gray-400">
          <a href="#" class="hover:text-black transition-colors">INSTAGRAM</a>
          <a href="#" class="hover:text-black transition-colors">LINKEDIN</a>
          <a href="#" class="hover:text-black transition-colors">VOGUE</a>
        </div>
      </div>

    </div>

    <!-- Bottom Map Banner Section -->
    <div class="w-full relative aspect-[21/9] min-h-[300px] border border-gray-200 overflow-hidden bg-gray-100 flex items-center justify-center">
      <!-- Simulated Grayscale Map Image -->
      <div 
        class="absolute inset-0 bg-cover bg-center grayscale opacity-60 filter contrast-125 saturate-0"
        style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1600&auto=format&fit=crop');"
      ></div>
      <!-- Center Showroom Badge -->
      <div class="relative z-10 bg-black text-white p-6 md:p-8 text-center flex flex-col items-center justify-center border border-white/10 min-w-[220px] shadow-[0_10px_30px_rgba(0,0,0,0.3)]">
        <svg class="mb-3 text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <span class="text-[12px] font-bold uppercase tracking-[2.5px] text-white">SHOWROOM NY</span>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

const isGmailExpanded = ref(false)

const form = ref({
  name: '',
  email: '',
  message: ''
})

const handleFormSubmit = () => {
  alert(`Cảm ơn ${form.value.name}! Tin nhắn của bạn đã được gửi thành công đến hệ thống của chúng tôi.`)
  // Clear form
  form.value.name = ''
  form.value.email = ''
  form.value.message = ''
  isGmailExpanded.value = false
}
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
