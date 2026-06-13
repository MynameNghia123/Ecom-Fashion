<template>
  <div class="flex items-center justify-between flex-wrap gap-3">
    <!-- Info Text -->
    <p class="text-xs text-slate-500">
      Hiển thị
      <span class="font-semibold text-slate-700">{{ paginationFrom }}-{{ paginationTo }}</span>
      trong số
      <span class="font-semibold text-slate-700">{{ total }}</span>
      mục
    </p>

    <!-- Pagination Controls -->
    <div class="flex items-center gap-1">
      <!-- Previous Button -->
      <button
        @click="emit('update:currentPage', currentPage - 1)"
        :disabled="currentPage === 1 || loading"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all disabled:cursor-not-allowed"
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
            ? 'bg-[#0258cb] text-white border border-[#0258cb] shadow-sm'
            : page === '...'
            ? 'text-slate-400 cursor-default border border-transparent'
            : 'border border-slate-200 text-slate-600 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 disabled:cursor-not-allowed'
        ]"
      >
        {{ page }}
      </button>

      <!-- Next Button -->
      <button
        @click="emit('update:currentPage', currentPage + 1)"
        :disabled="currentPage === lastPage || loading"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-[#0258cb] hover:text-[#0258cb] hover:bg-blue-50 transition-all disabled:cursor-not-allowed"
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

const emit = defineEmits(['update:currentPage'])

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
