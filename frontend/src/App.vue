<script setup>
import ClientLayout from './layouts/client/ClientLayout.vue'
import Homepage from './views/client/home/Homepage.vue'
</script>

<template>
  <ClientLayout>
    <Homepage />
  </ClientLayout>
</template>

<style>
/* Bạn có thể thêm các style toàn cục phụ ở đây nếu cần, hiện tại đang dùng style.css rồi */
body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

* {
  box-sizing: inherit;
}
</style>
