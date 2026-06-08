<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import ClientLayout from './layouts/client/ClientLayout.vue'

const router = useRouter()
const isTransparent = computed(() => {
  return router.currentRoute.value ? router.currentRoute.value.path === '/' : true
})
</script>

<template>
  <ClientLayout :is-transparent="isTransparent">
    <router-view />
  </ClientLayout>
</template>

<style>
body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

* {
  box-sizing: inherit;
}
</style>
