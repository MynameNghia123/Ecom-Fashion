<template>
  <div class="border border-slate-200 rounded-xl p-5 space-y-4">
    <h3 class="text-sm font-bold text-slate-700">Quản lý hình ảnh sản phẩm</h3>
    
    <input 
      ref="fileInputRef"
      type="file" 
      multiple 
      @change="handleFileChange"
      class="hidden" 
      accept="image/jpeg,image/png,image/webp,image/gif"
    />

    <!-- Khu vực kéo thả / click để chọn ảnh -->
    <div 
      @click="triggerFileInput"
      class="border-2 border-dashed border-slate-300 hover:border-[#0258cb] rounded-xl p-8 flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors group bg-blue-50/30 hover:bg-blue-50/60"
    >
      <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
        <svg class="w-6 h-6 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
          <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
        </svg>
      </div>
      <p class="text-sm font-semibold text-[#0258cb]">Kéo thả ảnh vào đây hoặc nhấp để tải lên</p>
      <p class="text-xs text-slate-400">Hỗ trợ JPG, PNG, WebP. Tối đa 5MB/ảnh. Tối đa 10 ảnh.</p>
    </div>

    <!-- Danh sách ảnh đã chọn -->
    <div class="space-y-2">
      <div
        v-for="(image, index) in images"
        :key="index"
        class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50"
      >
        <!-- Preview ảnh -->
        <div class="w-12 h-12 rounded-lg bg-slate-200 border border-slate-300 flex items-center justify-center overflow-hidden shrink-0 relative">
          <img v-if="image.preview_url" :src="image.preview_url" class="w-full h-full object-cover" alt="preview" />
          <!-- Đang upload spinner -->
          <div v-if="image._uploading" class="absolute inset-0 bg-white/80 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#0258cb] animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
          </div>
          <!-- Upload lỗi badge -->
          <div v-if="image._error" class="absolute inset-0 bg-red-50/90 flex items-center justify-center" :title="image._error">
            <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
        </div>

        <div class="flex-1 grid grid-cols-3 gap-3 items-center">
          <div class="col-span-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Alt Text</p>
            <input v-model="image.alt_text" type="text" placeholder="Mô tả ảnh..." class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all" />
          </div>
          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Thứ tự</p>
            <input v-model.number="image.display_order" type="number" min="1" class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:border-[#0258cb] focus:outline-none transition-all text-center" />
          </div>
          <div class="flex items-center gap-3">
            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Ảnh đại diện</p>
              <button
                @click="setThumbnail(image)"  
                type="button"
                :class="[
                  'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all',
                  image.is_thumbnail ? 'border-[#0258cb] bg-[#0258cb]' : 'border-slate-300 bg-white'
                ]"  
              >
                <div v-if="image.is_thumbnail" class="w-2.5 h-2.5 rounded-full bg-white"></div>
              </button>
            </div>
            <!-- Hiện URL storage sau khi upload thành công -->
            <div v-if="image.image_url" class="flex-1 min-w-0">
              <p class="text-[10px] font-bold text-emerald-500 uppercase mb-1">✓ Đã upload</p>
              <!-- <p class="text-[10px] text-slate-400 truncate font-mono" :title="image.image_url">{{ image.image_url }}</p> -->
            </div>
            <button @click="removeImage(index)" type="button" class="ml-auto p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineModel } from 'vue'
import { uploadService } from '@/services/admin/uploadService'

const fileInputRef = ref(null)
const images = defineModel({ default: () => [] })

// Trigger input file
const triggerFileInput = () => {
  fileInputRef.value.click()
}

/**
 * Xử lý khi người dùng chọn file:
 * 1. Tạo preview ngay (blob URL) để hiện trên UI
 * 2. Upload file lên server ngay lập tức
 * 3. Khi nhận được URL storage → cập nhật image.image_url và giải phóng blob
 */
const handleFileChange = async (event) => {
  const files = Array.from(event.target.files)
  if (!files.length) return

  for (const file of files) {
    if (!file.type.startsWith('image/')) continue
    if (file.size > 5 * 1024 * 1024) {
      alert(`Ảnh "${file.name}" vượt quá 5MB, bỏ qua.`)
      continue
    }

    // Tạo object ảnh với blob URL để hiển thị preview ngay
    const imageObj = {
      id: null,                          // null = ảnh mới, có id = ảnh cũ từ DB
      image_url: null,                   // URL storage (sẽ được set sau khi upload)
      preview_url: URL.createObjectURL(file),  // Blob URL chỉ dùng để preview
      _storage_path: null,               // Path tương đối để xóa nếu cần
      alt_text: file.name.replace(/\.[^.]+$/, ''),
      display_order: images.value.length + 1,
      is_thumbnail: images.value.length === 0,
      _uploading: true,
      _error: null,
    }

    images.value.push(imageObj)
    const insertedIndex = images.value.length - 1

    // Upload lên server ngầm (không chặn UI)
    try {
      const result = await uploadService.uploadImage(file, 'products')
      // Cập nhật image_url thật từ server
      images.value[insertedIndex].image_url = result.url
      images.value[insertedIndex]._storage_path = result.path
    } catch (err) {
      images.value[insertedIndex]._error = err.message || 'Upload thất bại'
      console.error('[ProductImageUploader] Upload thất bại:', err)
    } finally {
      images.value[insertedIndex]._uploading = false
    }
  }

  // Reset input để có thể chọn lại file cùng tên
  event.target.value = ''
}

// Đặt ảnh đại diện (thumbnail)
const setThumbnail = (selectedImage) => {
  images.value.forEach(img => {
    img.is_thumbnail = (img === selectedImage)
  })
}

// Xóa ảnh khỏi danh sách + xóa file trên storage (nếu chưa lưu vào DB)
const removeImage = async (index) => {
  const image = images.value[index]

  // Giải phóng blob URL
  if (image.preview_url?.startsWith('blob:')) {
    URL.revokeObjectURL(image.preview_url)
  }

  // Xóa file trên storage nếu đã upload nhưng chưa lưu vào DB (id = null)
  if (!image.id && image._storage_path) {
    uploadService.deleteImage(image._storage_path).catch(() => {})
  }

  images.value.splice(index, 1)

  // Đảm bảo luôn có ảnh thumbnail nếu còn ảnh
  if (images.value.length > 0) {
    const hasThumbnail = images.value.some(img => img.is_thumbnail)
    if (!hasThumbnail) images.value[0].is_thumbnail = true
  }

  // Cập nhật lại display_order
  images.value.forEach((img, i) => { img.display_order = i + 1 })
}
</script>