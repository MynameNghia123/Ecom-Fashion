<template>
  <div class="p-3 bg-white border-t border-neutral-200">
    <form @submit.prevent="handleSend" class="flex items-center gap-2">
      <input 
        type="text" 
        v-model="inputMsg"
        placeholder="Hỏi về thời trang, phối đồ..."
        :disabled="chatStore.isLoading"
        class="flex-1 px-3.5 py-2.5 bg-neutral-100 border border-transparent rounded-full text-xs text-neutral-800 outline-none focus:bg-white focus:border-black transition-colors font-text"
      />
      <button 
        type="submit" 
        :disabled="!inputMsg.trim() || chatStore.isLoading"
        class="w-9 h-9 rounded-full bg-black hover:bg-neutral-800 disabled:bg-neutral-200 disabled:cursor-not-allowed text-white flex items-center justify-center transition-colors shrink-0 border-none cursor-pointer"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13"></line>
          <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAiChatStore } from '@/stores/client/aiChatStore'

const chatStore = useAiChatStore()
const inputMsg = ref('')

const handleSend = () => {
  if (inputMsg.value.trim()) {
    chatStore.sendMessage(inputMsg.value)
    inputMsg.value = ''
  }
}
</script>

<style scoped>
.font-text { font-family: var(--font-text, 'Montserrat', sans-serif); }
</style>
