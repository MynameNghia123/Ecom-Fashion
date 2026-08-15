<script setup>
// Xử lý logic tại đây, như chuyển hướng về trang chủ
const goHome = () => {
  // Thay thế bằng router.push('/') nếu đã cài vue-router
  window.location.href = '/'
}
</script>

<template>
  <div class="not-found-container">
    <h1 class="error-code">404</h1>
    <h2 class="error-title">KHÔNG TÌM THẤY TRANG</h2>
    <p class="error-message">Có vẻ như không có gì được tìm thấy tại địa điểm này. Có lẽ hãy thử tìm kiếm những gì bạn đang tìm kiếm?</p>
    <!-- Nếu đã dùng vue-router thì có thể thay bằng <router-link to="/"> -->
    <button class="home-button" @click="goHome">
      QUAY LẠI TRANG CHỦ
    </button>
  </div>
</template>

<style scoped>
@import '@css/not-found.css';
</style>
