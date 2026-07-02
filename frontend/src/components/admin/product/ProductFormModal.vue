<template>
  <!-- Modal Thêm / Sửa sản phẩm -->
  <Teleport to="body">
    <Transition name="modal-fade">
      <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[1080px] max-h-[92vh] flex flex-col animate-modal-in">

          <div class="flex items-center justify-between px-7 pt-5 pb-4 border-b border-slate-100 shrink-0">
            <h2 class="text-base font-bold text-slate-800">{{ action === 'add' ? 'Thêm sản phẩm mới' : 'Chỉnh sửa sản phẩm'}}</h2>
            <button
              @click="$emit('closeForm')"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <!-- Thông báo lỗi từ API -->
          <div v-if="apiErrors.length > 0" class="mx-7 mt-4 p-3 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-xs font-bold text-red-600 mb-1">Vui lòng kiểm tra lại thông tin:</p>
            <ul class="list-disc list-inside space-y-0.5">
              <li v-for="(err, i) in apiErrors" :key="i" class="text-xs text-red-500">{{ err }}</li>
            </ul>
          </div>

          <div class="overflow-y-auto flex-1 px-7 py-6 space-y-6">

            <div class="border border-slate-200 rounded-xl p-5 space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700">Thông tin chung</h3>
                <div class="flex items-center gap-2.5">
                  <span class="text-sm font-semibold text-slate-600">Trạng thái hoạt động</span>
                    <button
                      @click="formProduct.is_active = !formProduct.is_active"
                      type="button" 
                      :class="[
                        'w-11 h-6 rounded-full flex items-center px-0.5 transition-colors duration-200 focus:outline-none',
                        formProduct.is_active ? 'bg-[#0258cb]' : 'bg-slate-300'
                      ]"
                    >
                      <div 
                        :class="[
                          'w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-200 ease-in-out',
                          formProduct.is_active ? 'translate-x-5' : 'translate-x-0'
                        ]"
                      ></div>
                    </button>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Tên sản phẩm <span class="text-red-500">*</span></label>
                  <input v-model="formProduct.name" type="text" placeholder="Nhập tên sản phẩm..."
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                  <input v-model="formProduct.slug" type="text" placeholder="ten-san-pham-slug"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-600 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all font-mono" />
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Danh mục <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <select 
                      v-model="formProduct.category_id"
                    class="w-full appearance-none pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all cursor-pointer">
                      <option value="">Chọn danh mục</option>
                      <option
                        v-for="category in categoryStore.categories" 
                        :key="category.id" 
                        :value="category.id"
                      >{{ category.name }}</option>
                    </select>
                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Thương hiệu</label>
                  <input 
                    v-model="formProduct.brand"
                    type="text" placeholder="Nhập thương hiệu"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all" />
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả chi tiết</label>
                <textarea 
                  v-model="formProduct.description"
                  rows="5" placeholder="Nhập mô tả sản phẩm..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-b-xl text-slate-700 placeholder-slate-400 bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all resize-none leading-relaxed rounded-t-none"></textarea>
              </div>
            </div>

            <ProductImageUploader v-model="formProduct.images" />

            <ProductVariantsUploader v-model="formProduct.variants" />

          </div>

          <div class="flex items-center justify-end gap-3 px-7 py-4 border-t border-slate-100 shrink-0">
            <button 
              @click="$emit('closeForm')"
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy</button>
            <button
              @click="handleSave"
              :disabled="isSaving || isUploadingAny"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
              <svg v-if="isSaving || isUploadingAny" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
              {{ isSaving ? 'Đang lưu...' : (isUploadingAny ? 'Đang tải ảnh...' : 'Lưu sản phẩm') }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, reactive, computed } from 'vue'
import { useProductStore } from '@/stores/admin/productStore'
import { useCategoryStore } from '@/stores/admin/categoryStore'
import ProductImageUploader from '@/components/admin/product/ProductImageUploader.vue'
import ProductVariantsUploader from '@/components/admin/product/ProductVariantsUploader.vue'
import { useProductValidation } from '@/composables/admin/validation/useProductValidation'

const categoryStore = useCategoryStore()
const productStore = useProductStore()

const emit = defineEmits(['closeForm', 'save'])

const props = defineProps({
  action: { 
    type: String, 
    default: '' 
  },
  // product: object sản phẩm hiện tại (null khi thêm mới, object khi sửa)
  product: {
    type: Object,
    default: null
  }
})

// ─── State ────────────────────────────────────────────────────────────────────
const isSaving = ref(false)
const { apiErrors, validate, clearErrors, applyBackendErrors } = useProductValidation()

const formProduct = reactive({
  name: '',
  category_id: '',
  slug: '',
  description: '',
  brand: '',
  thumbnail: '',
  user_manual: null,
  is_active: true,
  images: [],
  variants: []
})

// ─── Khi mở modal edit: nạp dữ liệu sản phẩm hiện tại vào form ──────────────
watch(() => props.product, (newProduct) => {
  clearErrors()
  if (newProduct && props.action === 'edit') {
    formProduct.name        = newProduct.name        ?? ''
    formProduct.category_id = newProduct.category_id ?? ''
    formProduct.slug        = newProduct.slug        ?? ''
    formProduct.description = newProduct.description ?? ''
    formProduct.brand       = newProduct.brand       ?? ''
    formProduct.thumbnail   = newProduct.thumbnail   ?? ''
    formProduct.user_manual = newProduct.user_manual ?? null
    formProduct.is_active   = newProduct.is_active   ?? true

    // Nạp images từ API — giữ đủ cả image_url (URL thật) và preview_url (hiện UI)
    formProduct.images = (newProduct.images ?? []).map(img => ({
      id:             img.id,
      file:           null,
      image_url:      img.image_url,          // URL thật từ storage (gửi lên backend)
      preview_url:    img.image_url,          // Dùng luôn URL thật để hiển thị (không phải blob)
      _storage_path:  null,                   // Đường dẫn tương đối (chỉ có khi upload mới)
      alt_text:       img.alt_text      ?? '',
      display_order:  img.display_order ?? 1,
      is_thumbnail:   img.is_thumbnail  ?? false,
      _uploading:     false,
      _error:         null,
    }))

    // Nạp variants từ API — map về đúng field name frontend dùng nội bộ
    formProduct.variants = (newProduct.variants ?? []).map(v => ({
      id:              v.id,
      key:             [...(v.attribute_values ?? [])].sort((a, b) => a.attribute_id - b.attribute_id).map(av => av.value).join('|||') || String(v.id), // Sắp xếp theo attribute_id để tránh lệch thứ tự key
      name:            (v.attribute_values ?? []).map(av => av.value).join(' - ') || v.sku,
      sku:             v.sku,
      cost_price:      v.cost_price !== null ? Number(v.cost_price) : null,
      price:           v.price !== null ? Number(v.price) : null,
      sale_price:      v.sale_price !== null ? Number(v.sale_price) : null,
      stock_quantity:  v.stock_quantity  ?? 0,
      is_active:       v.is_active       ?? true,
      thumbnail:       v.thumbnail       ?? null,
      _image_file:     null,
      _image_preview:  v.thumbnail       ?? null,  // Hiển ảnh cũ từ URL storage
      _storage_path:   null,
      _uploading:      false,
      _upload_error:   null,
      attribute_values: (v.attribute_values ?? []).map(av => ({
        id:           av.id,
        attribute_id: av.attribute_id,
        value:        av.value,
      })),
    }))
  } else {
    // Reset form khi thêm mới
    Object.assign(formProduct, {
      name: '', category_id: '', slug: '', description: '',
      brand: '', thumbnail: '', user_manual: null, is_active: true,
      images: [], variants: []
    })
  }
}, { immediate: true })

// Kiểm tra xem có ảnh sản phẩm hoặc ảnh biến thể nào đang upload hay không
const isUploadingAny = computed(() => {
  return formProduct.images.some(img => img._uploading) ||
         formProduct.variants.some(v => v._uploading)
})

// ─── Build payload đúng cấu trúc backend ─────────────────────────────────────
// Quy tắc:
//   - images: chỉ gửi khi image_url là URL hợp lệ (http/https), bỏ qua blob:// 
//   - variants: chỉ giữ đúng field backend yêu cầu, loại bỏ key/name/_image_* nội bộ
//   - attribute_values: chỉ gửi khi attribute_id !== null
const buildPayload = () => {
  // Helper: kiểm tra URL hợp lệ (http/https)
  const isValidUrl = (url) => {
    if (!url) return false
    return url.startsWith('http://') || url.startsWith('https://')
  }

  // Lấy ảnh được chọn làm đại diện
  const thumbnailImg = formProduct.images.find(img => img.is_thumbnail)
  const productThumbnail = thumbnailImg ? thumbnailImg.image_url : null

  return {
    // 1. Sản phẩm chính
    name:        formProduct.name,
    category_id: formProduct.category_id,
    slug:        formProduct.slug,
    description: formProduct.description || null,
    brand:       formProduct.brand       || null,
    thumbnail:   isValidUrl(productThumbnail) ? productThumbnail : null,
    is_active:   formProduct.is_active,

    // 2. Hình ảnh — lọc bằng image_url thật thay vì preview_url
    images: formProduct.images
      .filter(img => isValidUrl(img.image_url))
      .map(img => ({
        ...(img.id ? { id: img.id } : {}),
        image_url:     img.image_url,
        alt_text:      img.alt_text      || null,
        display_order: img.display_order || 1,
        is_thumbnail:  img.is_thumbnail  || false,
      })),

    // 3. Biến thể — chỉ gửi đúng field backend, bỏ key/name/_image_file/_image_preview
    variants: formProduct.variants.map(v => ({
      ...(v.id ? { id: v.id } : {}),
      sku:            v.sku,
      cost_price:     v.cost_price     ?? null,
      price:          v.price          ?? 0,
      sale_price:     v.sale_price     ?? null,
      stock_quantity: v.stock_quantity ?? 0,
      is_active:      v.is_active      ?? true,
      thumbnail:      isValidUrl(v.thumbnail) ? v.thumbnail : null,

      // Chỉ gửi attribute_values có attribute_id hợp lệ (người dùng đã chọn từ gợi ý)
      attribute_values: (v.attribute_values ?? [])
        .filter(av => av.attribute_id !== null && av.attribute_id !== undefined)
        .map(av => ({
          ...(av.id ? { id: av.id } : {}),
          attribute_id: av.attribute_id,
          value:        av.value,
        })),
    })),
  }
}

// ─── Lưu sản phẩm ─────────────────────────────────────────────────────────────
const handleSave = async () => {
  // Client-side validation qua composable
  if (!validate(formProduct)) return

  isSaving.value = true

  try {
    const payload = buildPayload()
    console.log('[ProductFormModal] Payload gửi lên backend:', JSON.stringify(payload, null, 2))

    if (props.action === 'add') {
      await productStore.createProduct(payload)
    } else if (props.action === 'edit' && props.product?.id) {
      await productStore.updateProduct(props.product.id, payload)
    }

    emit('save')
  } catch (error) {
    applyBackendErrors(error)
    console.error('[ProductFormModal] Lỗi khi lưu:', error.response?.data ?? error)
  } finally {
    isSaving.value = false
  }
}
</script>

<style scoped>
/* Animation cho Modal */
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(12px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>