<template>
  <div ref="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-4 bg-neutral-50 scrollbar-thin">
    <div 
      v-for="(msg, index) in chatStore.messages" 
      :key="index"
      :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']"
    >
      <div 
        :class="[
          'max-w-[82%] px-4 py-2.5 rounded-2xl text-xs font-text leading-relaxed shadow-xs',
          msg.role === 'user' 
            ? 'bg-black text-white rounded-br-xs' 
            : 'bg-white text-neutral-800 border border-neutral-200 rounded-bl-xs'
        ]"
      >
        <div class="whitespace-pre-wrap">{{ msg.content }}</div>
      </div>
    </div>

    <!-- Loading state animation -->
    <div v-if="chatStore.isLoading" class="flex justify-start">
      <div class="bg-white border border-neutral-200 text-neutral-500 px-4 py-3 rounded-2xl rounded-bl-xs shadow-xs flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-neutral-400 animate-bounce"></span>
        <span class="w-1.5 h-1.5 rounded-full bg-neutral-400 animate-bounce [animation-delay:0.2s]"></span>
        <span class="w-1.5 h-1.5 rounded-full bg-neutral-400 animate-bounce [animation-delay:0.4s]"></span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { useAiChatStore } from '@/stores/client/aiChatStore'

const chatStore = useAiChatStore()
const messagesContainer = ref(null)

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

watch(() => chatStore.messages.length, () => {
  scrollToBottom()
}, { immediate: true })
</script>

<style scoped>
.font-text { font-family: var(--font-text, 'Montserrat', sans-serif); }
.scrollbar-thin::-webkit-scrollbar { width: 4px; }
.scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e5e5; border-radius: 4px; }
</style>
