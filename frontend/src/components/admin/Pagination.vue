<template>
  <div class="flex items-center justify-between flex-wrap gap-3 w-full">
    <!-- Info Text & Per Page Selector -->
    <div class="flex items-center gap-3 flex-wrap">
      <p class="text-xs text-slate-500">
        Hiển thị
        <span class="font-semibold text-slate-700">{{ paginationFrom }}-{{ paginationTo }}</span>
        trong số
        <span class="font-semibold text-slate-700">{{ total }}</span>
        mục
      </p>

      <!-- Per Page Selector -->
      <div class="relative flex items-center gap-1.5 text-xs text-slate-500">
        <span class="text-slate-300">·</span>
        <select
          :value="perPage"
          @change="onPerPageChange"
          :disabled="loading"
          class="appearance-none pl-2.5 pr-7 py-1.5 border border-slate-200 rounded-lg text-slate-600 bg-white hover:border-black focus:border-black focus:ring-2 focus:ring-black/10 focus:outline-none transition-all cursor-pointer font-medium"
        >
          <option :value="4">4 / trang</option>
          <option :value="10">10 / trang</option>
          <option :value="25">25 / trang</option>
          <option :value="50">50 / trang</option>
        </select>
        <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-400">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </span>
      </div>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center gap-1">
      <!-- Previous Button -->
      <button
        @click="emit('update:currentPage', currentPage - 1)"
        :disabled="currentPage === 1 || loading"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-black hover:text-black hover:bg-neutral-100 transition-all disabled:cursor-not-allowed"
        title="Trang trước"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>

      <!-- Page Numbers -->
      <button
        v-for="page in displayedPages"
        :key="page"
        @click="page !== '...' && emit('update:currentPage', page)"
        :disabled="page === '...' || loading"
        :class="[
          'w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold transition-all',
          page === currentPage
            ? 'bg-black text-white border border-black shadow-sm'
            : page === '...'
            ? 'text-slate-400 cursor-default border border-transparent'
            : 'border border-slate-200 text-slate-600 hover:border-black hover:text-black hover:bg-neutral-100 disabled:cursor-not-allowed'
        ]"
      >
        {{ page }}
      </button>

      <!-- Next Button -->
      <button
        @click="emit('update:currentPage', currentPage + 1)"
        :disabled="currentPage === lastPage || loading"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-black hover:text-black hover:bg-neutral-100 transition-all disabled:cursor-not-allowed"
        title="Trang sau"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    required: true,
  },
  lastPage: {
    type: Number,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:currentPage', 'update:  '])

// ========== Events ==========
const onPerPageChange = (event) => {
  const newPerPage = parseInt(event.target.value, 10)
  emit('update:perPage', newPerPage)
  emit('update:currentPage', 1)
}

// ========== Computed ==========
const paginationFrom = computed(() => {
  if (props.total === 0) return 0
  return (props.currentPage - 1) * props.perPage + 1
})

const paginationTo = computed(() =>
  Math.min(props.currentPage * props.perPage, props.total)
)

const displayedPages = computed(() => {
  const total = props.lastPage
  const cur = props.currentPage
  
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }
  
  const pages = [1]
  if (cur > 3) pages.push('...')
  
  for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) {
    pages.push(i)
  }
  
  if (cur < total - 2) pages.push('...')
  pages.push(total)
  
  return pages
})
</script>
