<template>
  <article 
    @click="goToBlog"
    class="group flex flex-col gap-[15px] cursor-pointer"
  >
    <div class="w-full aspect-[4/3] overflow-hidden bg-[#f8f8f8]">
      <img :src="image" :alt="title" class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
    </div>
    <div class="flex flex-col gap-2">
      <span class="font-text text-[10px] font-semibold text-[#888] uppercase tracking-[0.5px]">{{ category }}</span>
      <h3 class="font-title text-[16px] font-semibold leading-[1.4] m-0 text-[#111] transition-colors duration-300 group-hover:text-[#666]">
        {{ title }}
      </h3>
      <span class="font-text text-[12px] text-[#999] mt-[5px]">bởi {{ author }}, {{ date }}</span>
    </div>
  </article>
</template>

<script setup>
import { useRouter } from 'vue-router'

const props = defineProps({
  slug: { type: String, default: '' },
  id: { type: [Number, String], default: '' },
  image: { type: String, required: true },
  category: { type: String, required: true },
  title: { type: String, required: true },
  author: { type: String, required: true },
  date: { type: String, required: true }
});

const router = useRouter()

const goToBlog = () => {
  const target = props.slug || props.id
  if (target) {
    router.push({ name: 'BlogDetail', params: { slug: target } })
  } else {
    router.push({ name: 'Blog' })
  }
}
</script>
