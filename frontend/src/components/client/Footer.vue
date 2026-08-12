<template>
  <footer class="bg-black text-white py-10 lg:py-20 px-5 lg:px-20 font-text">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 mb-10 lg:mb-[60px]">
      <div class="flex flex-col">
        <h4 class="font-title text-[15px] font-semibold mb-[25px] mt-0 text-white">Công ty</h4>
        <ul class="list-none p-0 m-0 flex flex-col gap-[15px]">
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Tuyển dụng</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Về chúng tôi</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Quan hệ nhà đầu tư</router-link></li>
          <li><router-link to="/category" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Thiết bị Luxury</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Đánh giá khách hàng</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Trách nhiệm xã hội</router-link></li>
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Hệ thống cửa hàng</router-link></li>
        </ul>
      </div>
      
      <div class="flex flex-col">
        <h4 class="font-title text-[15px] font-semibold mb-[25px] mt-0 text-white">Tài nguyên</h4>
        <ul class="list-none p-0 m-0 flex flex-col gap-[15px]">
          <li><router-link to="/profile/orders" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Đơn hàng của bạn</router-link></li>
          <li><router-link to="/profile/returns" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Trả hàng & Đổi trả</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Phí & Chính sách giao hàng</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Chính sách hoàn tiền</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Chính sách bảo mật</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Điều khoản và điều kiện</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Cài đặt Cookie</router-link></li>
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Trung tâm trợ giúp</router-link></li>
        </ul>
      </div>
      
      <div class="flex flex-col">
        <h4 class="font-title text-[15px] font-semibold mb-[25px] mt-0 text-white">Hỗ trợ</h4>
        <ul class="list-none p-0 m-0 flex flex-col gap-[15px]">
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Bán hàng trên Luxury</router-link></li>
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Cung cấp dịch vụ</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Luxury Doanh nghiệp</router-link></li>
          <li><router-link to="/" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Ứng dụng Luxury</router-link></li>
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Trở thành đối tác liên kết</router-link></li>
          <li><router-link to="/category" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Quảng cáo sản phẩm</router-link></li>
          <li><router-link to="/about" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Hợp tác xuất bản</router-link></li>
          <li><router-link to="/contact" class="text-[#aaa] no-underline text-[13px] transition-colors duration-300 hover:text-white">Trở thành nhà cung cấp</router-link></li>
        </ul>
      </div>
      
      <div class="flex flex-col">
        <h4 class="font-title text-[15px] font-semibold mb-[25px] mt-0 text-white">Đăng ký nhận bản tin</h4>
        <p class="text-[#aaa] text-[13px] leading-[1.6] mb-5 mt-0">Đăng ký để nhận các cập nhật, ưu đãi của chúng tôi và nhận giảm giá 10% cho lần mua hàng đầu tiên.</p>
        <form class="flex flex-col gap-[15px] mb-[15px]" @submit.prevent>
          <input type="email" placeholder="Nhập địa chỉ email của bạn" required class="bg-transparent border border-[#333] text-white py-3 px-[15px] font-text text-[13px] outline-none focus:border-white">
          <button type="submit" class="bg-transparent text-white border-none text-left p-0 font-text text-[12px] font-bold cursor-pointer tracking-[0.5px]">ĐĂNG KÝ</button>
        </form>
        <p class="text-[11px] text-[#666] leading-[1.5]">Bằng cách đăng ký, bạn đồng ý với <router-link to="/about" class="text-[#aaa] underline">Điều khoản & Điều kiện</router-link> và <router-link to="/about" class="text-[#aaa] underline">Chính sách Bảo mật</router-link> của chúng tôi.</p>
      </div>
    </div>
    
    <div class="flex justify-between items-center border-t border-[#222] pt-[30px] text-[12px] text-[#888] max-sm:flex-col max-sm:gap-5 max-sm:text-center">
      <div>
        Copyright 2026 © EcomFashion Theme. All right reserved.
      </div>
      <div class="flex gap-[15px]">
         <span class="font-semibold text-[#aaa]">Mastercard</span>
         <span class="font-semibold text-[#aaa]">VISA</span>
         <span class="font-semibold text-[#aaa]">PayPal</span>
         <span class="font-semibold text-[#aaa]">Skrill</span>
         <span class="font-semibold text-[#aaa]">Klarna</span>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
