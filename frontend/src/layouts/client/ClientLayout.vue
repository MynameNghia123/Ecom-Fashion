<template>
  <div class="client-layout">
    <Header :is-transparent="isTransparent" />
    <main class="main-content">
      <slot />
    </main>
    <Footer />
    <!-- AI Assistant Floating Chat Widget -->
    <AiAssistant />
    <!-- Coupon Popup -->
    <CouponPopup />
  </div>
</template>

<script setup>
import Header from '@/components/client/Header.vue'
import Footer from '@/components/client/Footer.vue'
import AiAssistant from '@/components/client/ai-assistant/AiAssistant.vue'
import CouponPopup from '@/components/client/CouponPopup.vue'

defineProps({
  isTransparent: {
    type: Boolean,
    default: false
  }
})
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
