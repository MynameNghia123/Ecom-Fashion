<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import ClientLayout from './layouts/client/ClientLayout.vue'
import AdminLayout from './layouts/AdminLayout.vue'
import BlankLayout from './layouts/BlankLayout.vue'

const router = useRouter()

const currentLayout = computed(() => {
  const layout = router.currentRoute.value?.meta?.layout
  if (layout === 'AdminLayout') return AdminLayout
  if (layout === 'BlankLayout') return BlankLayout
  return ClientLayout
})

const isTransparent = computed(() => {
  return router.currentRoute.value ? router.currentRoute.value.path === '/' : true
})
</script>

<template>
  <component :is="currentLayout" :is-transparent="isTransparent">
    <router-view v-slot="{ Component }">
      
      <keep-alive v-if="currentLayout === AdminLayout">
        <component :is="Component" />
      </keep-alive>
      
      <component v-else :is="Component" />
      
    </router-view>
  </component>
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
