<template>
  <div class="w-full bg-white text-black font-text min-h-[70vh]">
    <!-- Background Banner -->
    <div class="relative bg-[url('/img/blog/bannercategoryao.jpg')] bg-cover bg-center bg-no-repeat py-24 flex flex-col items-center justify-center">
      <div class="absolute inset-0 bg-black/40"></div>
      <h1 class="relative font-title text-[40px] md:text-[56px] font-bold text-white uppercase tracking-[1px] m-0 z-10 text-center drop-shadow-md">
        TẠP CHÍ THỜI TRANG
      </h1>
      <p class="relative text-xs text-white/90 font-semibold tracking-widest uppercase mt-4 z-10 max-w-[500px] text-center px-5">
        Cập nhật xu hướng, cảm hứng phong cách và câu chuyện thương hiệu Luxury.
      </p>
    </div>

    <div class="py-12 px-5 lg:px-20 max-w-[1280px] mx-auto">

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-8 py-12">
      <div v-for="i in 6" :key="i" class="flex flex-col gap-4 animate-pulse">
        <div class="w-full aspect-[4/3] bg-neutral-100 rounded"></div>
        <div class="h-3 bg-neutral-100 w-1/3 rounded"></div>
        <div class="h-5 bg-neutral-100 w-full rounded"></div>
        <div class="h-3 bg-neutral-100 w-2/3 rounded"></div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="blogs.length === 0" class="text-center py-20 border border-dashed border-neutral-200 rounded">
      <p class="text-neutral-500 font-medium">Chưa có bài viết nào được đăng bản.</p>
    </div>

    <div v-else class="space-y-16">
      <!-- Featured Post (First Item) -->
      <article
        v-if="featuredBlog"
        @click="goToDetail(featuredBlog.slug || featuredBlog.id)"
        class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center border-b border-gray-100 pb-16 cursor-pointer group"
      >
        <div class="lg:col-span-7 aspect-[16/10] bg-gray-50 border border-gray-100 overflow-hidden rounded-lg">
          <img 
            :src="getImageUrl(featuredBlog.image)" 
            :alt="featuredBlog.name" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102"
          />
        </div>
        <div class="lg:col-span-5 flex flex-col justify-center">
          <span class="text-[11px] font-bold tracking-[2px] uppercase text-gray-400 mb-3">TIÊU ĐIỂM</span>
          <h2 class="font-title text-[28px] md:text-[38px] font-bold leading-tight text-gray-900 mb-4 tracking-tight group-hover:text-neutral-600 transition-colors">
            {{ featuredBlog.name }}
          </h2>
          <p class="text-[14px] text-gray-500 leading-relaxed mb-6 font-normal line-clamp-3">
            {{ getSnippet(featuredBlog.description || featuredBlog.content) }}
          </p>
          <div>
            <span class="inline-block text-[12px] font-bold uppercase tracking-[2px] border-b-2 border-black pb-1 group-hover:opacity-70 transition-opacity">
              ĐỌC BÀI VIẾT
            </span>
          </div>
        </div>
      </article>

      <!-- Blog Grid (Remaining Items) -->
      <div v-if="gridBlogs.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-12">
        <BlogCard 
          v-for="blog in gridBlogs" 
          :key="blog.id"
          :id="blog.id"
          :slug="blog.slug || String(blog.id)"
          :image="getImageUrl(blog.image)"
          category="TẠP CHÍ"
          :title="blog.name"
          author="Luxury"
          :date="blog.created_at || ''"
        />
      </div>
    </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { blogService } from '@/services/client/blogService'
import BlogCard from '@/components/client/ui/BlogCard.vue'

const router = useRouter()
const loading = ref(true)
const blogs = ref([])

const featuredBlog = computed(() => blogs.value[0] || null)
const gridBlogs = computed(() => blogs.value.slice(1))

const getImageUrl = (path) => {
  if (!path) return 'https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=800&auto=format&fit=crop'
  if (path.startsWith('http')) return path
  return `http://localhost:8000/storage/${path}`
}

const getSnippet = (htmlContent) => {
  if (!htmlContent) return 'Khám phá bài viết mới nhất từ thương hiệu thời trang Luxury.'
  const plainText = htmlContent.replace(/<[^>]+>/g, '').trim()
  return plainText.length > 150 ? plainText.substring(0, 150) + '...' : plainText
}

const goToDetail = (slugOrId) => {
  router.push({ name: 'BlogDetail', params: { slug: slugOrId } })
}

onMounted(async () => {
  try {
    const res = await blogService.getBlogs({ per_page: 20 })
    if (res.data && res.data.data) {
      blogs.value = res.data.data
    }
  } catch (err) {
    console.error('Lỗi khi tải bài viết:', err)
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
