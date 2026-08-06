<template>
  <div class="flex justify-center overflow-x-auto w-full">
    <table class="min-w-[700px] max-w-[900px] border-collapse text-center border border-gray-300 font-text text-[13px]">
      <thead>
        <tr class="bg-[#d4e6cc] text-gray-800 font-bold">
          <th colspan="3" class="border border-gray-300 py-3 text-[14px]">NAM</th>
          <th colspan="3" class="border border-gray-300 py-3 text-[14px]">NỮ</th>
        </tr>
        <tr class="bg-gray-100 font-bold text-gray-700">
          <th class="border border-gray-300 py-2.5 w-[16%]">Chiều cao</th>
          <th class="border border-gray-300 py-2.5 w-[16%]">Cân nặng</th>
          <th class="border border-gray-300 py-2.5 w-[16%]">Size</th>
          <th class="border border-gray-300 py-2.5 w-[16%]">Chiều cao</th>
          <th class="border border-gray-300 py-2.5 w-[16%]">Cân nặng</th>
          <th class="border border-gray-300 py-2.5 w-[16%]">Size</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, idx) in sizeTable" :key="idx" class="hover:bg-gray-50 transition-colors">
          <td class="border border-gray-300 py-2.5">{{ row.mHeight }}</td>
          <td class="border border-gray-300 py-2.5">{{ row.mWeight }}</td>
          <td class="border border-gray-300 py-2.5 font-bold">{{ row.mSize }}</td>
          <td class="border border-gray-300 py-2.5">{{ row.wHeight }}</td>
          <td class="border border-gray-300 py-2.5">{{ row.wWeight }}</td>
          <td class="border border-gray-300 py-2.5 font-bold">{{ row.wSize }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const sizeTable = ref([
  { mHeight: '1m60-1m65', mWeight: '55-60kg', mSize: '38', wHeight: '1m48-1m53', wWeight: '38-43kg', wSize: '38' },
  { mHeight: '1m64-1m69', mWeight: '60-65kg', mSize: '39', wHeight: '1m53-1m55', wWeight: '43-46kg', wSize: '39' },
  { mHeight: '1m70-1m74', mWeight: '66-70kg', mSize: '40', wHeight: '1m53-1m58', wWeight: '46-53kg', wSize: '40' },
  { mHeight: '1m74-1m76', mWeight: '70-76kg', mSize: '41', wHeight: '1m55-1m62', wWeight: '53-57kg', wSize: '41' },
  { mHeight: '1m65-1m77', mWeight: '76-80kg', mSize: '42', wHeight: '1m55-1m66', wWeight: '57-66kg', wSize: '42' }
])
</script>
