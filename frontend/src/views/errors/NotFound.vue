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
    <h2 class="error-title">That Page Cant Be Found</h2>
    <p class="error-message">It looks like nothing was found at this location. Maybe try to search for what you are looking for?</p>
    <!-- Nếu đã dùng vue-router thì có thể thay bằng <router-link to="/"> -->
    <button class="home-button" @click="goHome">
      GO TO HOMEPAGE
    </button>
  </div>
</template>

<style scoped>
@import '@css/not-found.css';
</style>
