<template>
  <div class="p-4 bg-black text-white flex items-center justify-between border-b border-neutral-800">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-full bg-neutral-800 flex items-center justify-center text-xs font-bold border border-neutral-700">
        AI
      </div>
      <div>
        <h4 class="font-title text-sm font-semibold text-white leading-none">Trợ lý Thời trang AI</h4>
        <span class="text-[10px] text-emerald-400 font-text flex items-center gap-1 mt-1">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Sẵn sàng hỗ trợ
        </span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button 
        @click="chatStore.clearHistory" 
        class="text-neutral-400 hover:text-white p-1 text-xs transition-colors cursor-pointer bg-transparent border-none"
        title="Xóa đoạn chat"
      >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
      </button>
      <button 
        @click="chatStore.toggleChat" 
        class="text-neutral-400 hover:text-white p-1 text-xs transition-colors cursor-pointer bg-transparent border-none"
        title="Đóng"
      >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useAiChatStore } from '@/stores/client/aiChatStore'
const chatStore = useAiChatStore()
</script>

<style scoped>
.font-title { font-family: var(--font-title, 'Playfair Display', serif); }
.font-text { font-family: var(--font-text, 'Montserrat', sans-serif); }
</style>
