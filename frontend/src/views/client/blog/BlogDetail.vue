<template>
  <div class="w-full bg-white text-black py-12 px-5 lg:px-20 font-text min-h-[60vh]">
    <div v-if="loading" class="text-center py-20">
      <div class="inline-block w-8 h-8 border-4 border-neutral-200 border-t-black rounded-full animate-spin mb-4"></div>
      <p class="text-sm text-neutral-500 font-medium">Đang tải bài viết...</p>
    </div>

    <div v-else-if="!blog" class="text-center py-20 max-w-[500px] mx-auto space-y-4">
      <p class="text-base text-neutral-600 font-medium">Bài viết không tồn tại hoặc đã bị gỡ bỏ.</p>
      <router-link to="/blog" class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
        Quay lại Tạp chí
      </router-link>
    </div>

    <article v-else class="max-w-[900px] mx-auto">
      <!-- Breadcrumb -->
      <div class="text-[12px] uppercase tracking-[1.5px] text-gray-400 mb-8 font-semibold">
        <router-link to="/" class="hover:text-black transition-colors">TRANG CHỦ</router-link>
        <span class="mx-2.5">&gt;</span>
        <router-link to="/blog" class="hover:text-black transition-colors">TẠP CHÍ</router-link>
        <span class="mx-2.5">&gt;</span>
        <span class="text-black">{{ blog.name }}</span>
      </div>

      <!-- Header -->
      <header class="mb-10 text-center">
        <span class="inline-block text-[11px] font-bold tracking-[2px] uppercase text-neutral-400 mb-3 bg-neutral-100 px-3 py-1 rounded-full">
          TẬP SAN LUXURY
        </span>
        <h1 class="font-title text-[32px] md:text-[46px] font-bold leading-tight text-neutral-900 mb-6 tracking-tight">
          {{ blog.name }}
        </h1>
        <div class="flex items-center justify-center gap-6 text-xs text-neutral-500 font-medium border-t border-b border-neutral-100 py-3 max-w-[400px] mx-auto">
          <span>Tác giả: <strong class="text-neutral-800">Luxury Editorial</strong></span>
          <span>•</span>
          <span>{{ blog.created_at || 'Mới cập nhật' }}</span>
        </div>
      </header>

      <!-- Featured Image -->
      <div v-if="blog.image" class="w-full aspect-[16/9] bg-neutral-100 overflow-hidden mb-12 rounded-lg shadow-xs">
        <img :src="getImageUrl(blog.image)" :alt="blog.name" class="w-full h-full object-cover" />
      </div>

      <!-- Content HTML -->
      <div class="prose prose-neutral max-w-none text-[15px] leading-relaxed text-neutral-700 space-y-6 font-normal border-b border-neutral-100 pb-16 mb-16" v-html="blog.description || blog.content || '<p>Nội dung bài viết đang được cập nhật...</p>'">
      </div>

      <!-- Navigation Footer -->
      <div class="flex justify-between items-center">
        <router-link to="/blog" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-neutral-900 border-b-2 border-black pb-1 hover:opacity-70 transition-opacity">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          Quay lại danh sách bài viết
        </router-link>
      </div>
    </article>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { blogService } from '@/services/client/blogService'

const route = useRoute()
const loading = ref(true)
const blog = ref(null)

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=1200&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

onMounted(async () => {
  try {
    const slug = route.params.slug
    const res = await blogService.getBlogs({ per_page: 50 })
    if (res.data && res.data.data) {
      const found = res.data.data.find(b => b.slug === slug || String(b.id) === String(slug))
      if (found) {
        blog.value = found
      }
    }
  } catch (err) {
    console.error('Lỗi tải chi tiết bài viết:', err)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.font-title {
  font-family: var(--font-title);
}
.font-text {
  font-family: var(--font-text);
}
</style>
