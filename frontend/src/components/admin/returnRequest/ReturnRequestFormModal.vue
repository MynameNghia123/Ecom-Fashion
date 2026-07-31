<template>
  <Transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center p-4 z-50"
      @click.self="$emit('close')"
    >
      <Transition name="pop" appear>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto overflow-x-hidden">

          <!-- Header -->
          <div class="flex items-start justify-between px-6 pt-6 pb-5">
            <div>
              <h2 class="text-lg font-bold text-slate-900">
                {{ isEdit ? 'Chỉnh sửa yêu cầu trả hàng' : 'Tạo đơn trả hàng mới' }}
              </h2>
              <p class="text-xs text-slate-400 mt-0.5">
                {{ isEdit ? 'Cập nhật thông tin xử lý đổi trả.' : 'Khởi tạo quy trình xử lý trả hàng mới.' }}
              </p>
            </div>
            <button
              @click="$emit('close')"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Divider -->
          <div class="mx-6 border-t border-slate-100"></div>

          <!-- Form Body -->
          <form @submit.prevent="handleSubmit" class="px-6 pt-5 pb-6 space-y-5">

            <!-- Order ID + Refund Amount -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                  Mã đơn hàng <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                  </span>
                  <input
                    v-model="form.order_code"
                    type="text"
                    required
                    placeholder="VD: ORD-2023-458"
                    class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0258cb]/25 focus:border-[#0258cb] focus:bg-white transition-all"
                  />
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số tiền hoàn (yêu cầu)</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">₫</span>
                  <input
                    v-model.number="form.refund_amount"
                    type="number"
                    min="0"
                    placeholder="0.00"
                    class="w-full pl-7 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0258cb]/25 focus:border-[#0258cb] focus:bg-white transition-all"
                  />
                </div>
              </div>
            </div>

            <!-- Status (inline badge style) -->
            <div class="flex items-center gap-3">
              <label class="text-xs font-semibold text-slate-600 shrink-0">Trạng thái:</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="opt in statusOptions"
                  :key="opt.value"
                  type="button"
                  @click="form.status = opt.value"
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-semibold border transition-all',
                    form.status === opt.value ? opt.activeClass : 'bg-slate-100 text-slate-500 border-slate-200 hover:border-slate-300'
                  ]"
                >
                  {{ opt.label }}
                </button>
              </div>
            </div>

            <!-- Reason for Return -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                Lý do trả hàng <span class="text-rose-500">*</span>
              </label>
              <textarea
                v-model="form.reason"
                rows="4"
                required
                placeholder="Mô tả chi tiết vấn đề của sản phẩm..."
                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0258cb]/25 focus:border-[#0258cb] focus:bg-white transition-all resize-none leading-relaxed"
              ></textarea>
            </div>

            <!-- Staff ID (edit mode only) -->
            <div v-if="isEdit">
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">ID Nhân viên xử lý</label>
              <input
                v-model.number="form.processed_by_staff_id"
                type="number"
                placeholder="VD: 1"
                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0258cb]/25 focus:border-[#0258cb] focus:bg-white transition-all"
              />
            </div>

            <!-- Evidence Images -->
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1.5">Hình ảnh bằng chứng</label>
              <div
                class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center gap-2 bg-slate-50/60 hover:border-[#0258cb]/40 hover:bg-blue-50/30 transition-all cursor-pointer"
                @click="triggerFileInput"
              >
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                  </svg>
                </div>
                <p class="text-sm text-slate-500">
                  <span class="text-[#0258cb] font-semibold hover:underline">Tải ảnh lên</span> hoặc kéo thả vào đây
                </p>
                <p class="text-xs text-slate-400">PNG, JPG, GIF tối đa 10MB</p>
              </div>
              <input ref="fileInputRef" type="file" accept="image/*" multiple class="hidden" @change="handleFileChange" />
              <!-- Preview uploaded images -->
              <div v-if="previewImages.length" class="mt-2 flex flex-wrap gap-2">
                <div
                  v-for="(item, i) in previewImages"
                  :key="i"
                  class="relative w-16 h-16 rounded-lg overflow-hidden border border-slate-200 group"
                >
                  <img :src="item.preview" class="w-full h-full object-cover" />
                  <!-- Loading overlay -->
                  <div v-if="item.uploading" class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white animate-spin" viewBox="0 0 24 24" fill="none">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                  </div>
                  <!-- Error overlay -->
                  <div v-else-if="item.error" class="absolute inset-0 bg-red-500/70 flex items-center justify-center" :title="item.error">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                  </div>
                  <!-- Success indicator -->
                  <div v-else-if="item.url" class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 rounded-tl-md flex items-center justify-center">
                    <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                  </div>
                  <!-- Remove button -->
                  <button
                    type="button"
                    @click="removeImage(i)"
                    class="absolute top-0 left-0 inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-2.5 pt-2">
              <button
                type="button"
                @click="$emit('close')"
                class="px-5 py-2.5 text-sm rounded-xl border border-slate-200 font-semibold text-slate-600 hover:bg-slate-50 transition-colors"
              >
                Hủy
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl bg-[#0258cb] hover:bg-[#004bb3] active:scale-[0.98] text-white font-semibold transition-all disabled:opacity-50 shadow-sm"
              >
                <span>{{ submitting ? 'Đang lưu...' : (isEdit ? 'Cập nhật' : 'Gửi yêu cầu') }}</span>
                <svg v-if="!submitting" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
              </button>
            </div>
          </form>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  item: { type: Object, default: null },
})

const emit = defineEmits(['close', 'save'])

const submitting = ref(false)
const fileInputRef = ref(null)
const isUploadingImages = ref(false)

// Mảng các ảnh { preview: string, url: string|null, uploading: bool, error: string|null }
const previewImages = ref([])

const isEdit = computed(() => !!props.item)

const statusOptions = [
  { value: 'pending',   label: 'Chờ xử lý',   activeClass: 'bg-amber-100 text-amber-700 border-amber-300' },
  { value: 'approved',  label: 'Đã chấp nhận',  activeClass: 'bg-emerald-100 text-emerald-700 border-emerald-300' },
  { value: 'rejected',  label: 'Đã từ chối',  activeClass: 'bg-rose-100 text-rose-700 border-rose-300' },
  { value: 'completed', label: 'Hoàn thành', activeClass: 'bg-blue-100 text-blue-700 border-blue-300' },
]

const form = ref({
  order_code: '',
  refund_amount: 0,
  reason: '',
  status: 'pending',
  processed_by_staff_id: null,
})

watch(
  () => props.item,
  (newItem) => {
    previewImages.value = []
    if (newItem) {
      form.value = {
        order_code: newItem.order_code || '',
        refund_amount: newItem.refund_amount || 0,
        reason: newItem.reason || '',
        status: newItem.status || 'pending',
        processed_by_staff_id: newItem.processed_by_staff_id || null,
      }
      if (newItem.evidence_images && Array.isArray(newItem.evidence_images)) {
        previewImages.value = newItem.evidence_images.map(imgUrl => ({
          preview: imgUrl,
          url: imgUrl,
          uploading: false,
          error: null
        }))
      }
    } else {
      form.value = { order_code: '', refund_amount: 0, reason: '', status: 'pending', processed_by_staff_id: null }
    }
  },
  { immediate: true }
)

function triggerFileInput() {
  fileInputRef.value?.click()
}

async function handleFileChange(e) {
  const files = Array.from(e.target.files)
  e.target.value = '' // reset input để có thể chọn lại file cũ
  
  for (const file of files) {
    // Thêm ảnh vào danh sách với trạng thái đang tải
    const index = previewImages.value.length
    const reader = new FileReader()
    const previewData = await new Promise(resolve => {
      reader.onload = e => resolve(e.target.result)
      reader.readAsDataURL(file)
    })

    previewImages.value.push({ preview: previewData, url: null, uploading: true, error: null })

    // Upload ngay lập tức bằng fetch API (tự đặt boundary chuẩn)
    try {
      const apiBase = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
      const token = localStorage.getItem('admin_token')
      const fd = new FormData()
      fd.append('file', file)
      fd.append('folder', 'returns')

      const response = await fetch(`${apiBase}/admin/upload-image`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
          // Không set Content-Type để browser tự đặt multipart/form-data kèm boundary
        },
        body: fd,
      })

      const result = await response.json()
      if (response.ok && result.success) {
        previewImages.value[index].url = result.url
        previewImages.value[index].uploading = false
      } else {
        // Lấy lỗi validation cụ thể từ backend
        let errorMsg = result.message || 'Upload thất bại'
        if (result.errors) {
          // Lấy lỗi đầu tiên của trường file
          const firstError = Object.values(result.errors)[0][0]
          errorMsg = firstError
        }
        throw new Error(errorMsg)
      }
    } catch (err) {
      previewImages.value[index].uploading = false
      previewImages.value[index].error = err.message || 'Upload thất bại'
      console.error('Lỗi upload:', err)
      alert('Lỗi khi tải ảnh lên: ' + err.message)
    }
  }
}

function removeImage(index) {
  previewImages.value.splice(index, 1)
}

async function handleSubmit() {
  // Kiểm tra có ảnh nào đang upload không
  const stillUploading = previewImages.value.some(img => img.uploading)
  if (stillUploading) {
    alert('Vui lòng chờ ảnh tải lên xong trước khi gửi!')
    return
  }

  // Kiểm tra có ảnh nào bị lỗi không
  const hasError = previewImages.value.some(img => img.error)
  if (hasError) {
    alert('Một số ảnh tải lên bị lỗi. Vui lòng xóa ảnh lỗi và chọn lại.')
    return
  }

  submitting.value = true
  try {
    const payload = { ...form.value }
    
    // Lấy các URL ảnh đã upload thành công
    const uploadedUrls = previewImages.value
      .filter(img => img.url)
      .map(img => img.url)
    
    if (uploadedUrls.length > 0) {
      payload.evidence_images = uploadedUrls
    }
    
    await emit('save', payload)
  } catch (error) {
    console.error('Lỗi khi lưu yêu cầu:', error)
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
.pop-leave-active { transition: all 0.12s ease; }
.pop-enter-from, .pop-leave-to { opacity: 0; transform: scale(0.95) translateY(10px); }
</style>
