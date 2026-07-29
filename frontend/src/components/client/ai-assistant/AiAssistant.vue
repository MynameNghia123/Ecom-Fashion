<template>
  <div class="fixed bottom-6 right-6 z-[990]">
    <!-- Toggle Floating Bubble Button -->
    <button 
      @click="chatStore.toggleChat"
      class="w-14 h-14 rounded-full bg-black hover:bg-neutral-800 text-white shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-105 cursor-pointer border-none relative group"
      aria-label="Open AI Assistant"
    >
      <svg v-if="!chatStore.isOpen" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      </svg>
      <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
      <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white"></span>
    </button>

    <!-- Chat Modal Window -->
    <transition name="chat-slide">
      <div 
        v-if="chatStore.isOpen"
        class="absolute bottom-16 right-0 w-[360px] sm:w-[400px] h-[520px] bg-white rounded-2xl shadow-2xl border border-neutral-200 flex flex-col overflow-hidden"
      >
        <AssistantHeader />
        <AssistantMessages />
        <AssistantInput />
      </div>
    </transition>
  </div>
</template>

<script setup>
import { useAiChatStore } from '@/stores/client/aiChatStore'
import AssistantHeader from './AssistantHeader.vue'
import AssistantMessages from './AssistantMessages.vue'
import AssistantInput from './AssistantInput.vue'

const chatStore = useAiChatStore()
</script>

<style scoped>
.chat-slide-enter-active,
.chat-slide-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.chat-slide-enter-from,
.chat-slide-leave-to {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}
</style>
