<template>
  <div class="client-layout">
    <Header />
    <main class="main-content">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '@/components/client/Header.vue'
import Footer from '@/components/client/Footer.vue'
</script>

<style scoped>
.client-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  position: relative;
}

.main-content {
  flex: 1;
}
</style>
