<template>
  <div class="border border-slate-200 rounded-xl p-5 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-bold text-slate-700">Phân loại hàng (Biến thể)</h3>
      <button
        @click="addInputAttributeValue"    
        type="button" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl border border-slate-200 transition-all">
        + Thêm nhóm thuộc tính
      </button>
    </div>

    <div class="border border-slate-200 rounded-xl p-4 space-y-3" v-if="InputAttributeGroup.length > 0">
      <div v-for="(group, index) in InputAttributeGroup" :key="index" class="flex items-start gap-2">
        <div class="relative w-40 shrink-0">
          <input 
            v-model="group.name"
            @input="group.attribute_id = null" 
            @focus="group.showSuggestions = true"
            @blur="hideSuggestionsDelay(group)"
            type="text" placeholder="VD: Màu sắc, Size..." 
            class="w-full px-3 py-1.5 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-black focus:ring-2 focus:ring-black/10 focus:outline-none transition-all font-semibold text-slate-700" 
          />
          <ul v-if="group.showSuggestions && filterAttributeGroup(group.name).length > 0" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-40 overflow-y-auto">
            <li v-for="attr in filterAttributeGroup(group.name)" :key="attr.id" @mousedown="selectAttribute(group, attr)" class="px-3 py-2 text-sm text-slate-600 hover:bg-neutral-100 hover:text-black cursor-pointer transition-colors">
              {{ attr.name }}
            </li>
          </ul>
        </div>

        <div class="flex flex-wrap items-center gap-1.5 flex-1 p-1 border border-transparent rounded-xl focus-within:border-slate-200 focus-within:bg-slate-50 transition-colors min-h-[36px]">
          <span v-for="(val, valIndex) in group.values" :key="valIndex" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-black border border-blue-200">
            {{ val }}
            <button @click="removeValueFromGroup(group, valIndex)" type="button" class="hover:text-red-500 transition-colors">×</button>
          </span>
          <input type="text" placeholder="+ Thêm giá trị (Nhấn Enter)" @keydown.enter.prevent="addValueToGroup(group, $event)" class="flex-1 min-w-[150px] bg-transparent text-sm focus:outline-none text-slate-600 placeholder-slate-400 py-1 px-2" />
        </div>

        <button @click="deleteInputAttributeGroup(index)" type="button" class="p-1.5 mt-1 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <input 
        type="file" 
        ref="variantFileInputRef" 
        @change="handleVariantImageChange" 
        accept="image/jpeg, image/png, image/webp" 
        class="hidden" 
      />

      <div class="flex items-center justify-end mb-3" v-if="variants.length > 0">
        <button 
          @click="generateSKUsForVariants"
          type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
          Tự động tạo SKU
        </button>
      </div>

      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-y border-slate-200">
            <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Ảnh</th>
            <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
            <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Tên biến thể</th>
            <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá vốn</th>
            <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá bán</th>
            <th class="py-2.5 px-3 text-left text-xs font-bold text-slate-500 uppercase">Giá KM</th>
            <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Tồn kho</th>
            <th class="py-2.5 px-3 text-center text-xs font-bold text-slate-500 uppercase">Kích hoạt</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="(v, vIdx) in variants" :key="v.key" class="hover:bg-slate-50">
            
            <td class="py-3 px-3">
              <div class="relative w-10 h-10 mx-auto group">
                <div 
                  @click="triggerVariantImage(vIdx)" 
                  class="w-full h-full rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden cursor-pointer group-hover:border-black transition-colors relative"
                  title="Nhấp để tải ảnh lên"
                >
                  <img v-if="v._image_preview" :src="v._image_preview" class="w-full h-full object-cover" />
                  <svg v-else class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>
                  
                  <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  </div>
                </div>
                <button 
                  v-if="v._image_preview" 
                  @click="removeVariantImage(vIdx)" 
                  type="button" 
                  class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-sm"
                >
                  <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
            </td>

            <td class="py-3 px-3">
              <input v-model="v.sku" type="text" class="w-24 px-2 py-1.5 text-xs font-mono border border-slate-200 rounded-lg focus:border-black focus:outline-none bg-white transition-colors" />
            </td>

            <td class="py-3 px-3">
              <input v-model="v.name" type="text" class="w-32 px-2 py-1.5 text-xs font-medium text-black border border-transparent hover:border-slate-200 focus:border-black rounded-lg focus:outline-none bg-transparent focus:bg-white transition-colors" />
            </td>

            <td class="py-3 px-3">
              <!-- cost_price: giá vốn -->
              <input v-model.number="v.cost_price" type="number" min="0" placeholder="0" class="w-20 px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:border-black focus:outline-none text-right bg-white transition-colors" />
            </td>
            <td class="py-3 px-3">
              <!-- price: giá bán chính (backend field) -->
              <input v-model.number="v.price" type="number" min="0" placeholder="0" class="w-20 px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:border-black focus:outline-none font-semibold text-right bg-white transition-colors" />
            </td>
            <td class="py-3 px-3">
              <!-- sale_price: giá khuyến mãi (backend field) -->
              <input v-model.number="v.sale_price" type="number" min="0" placeholder="Không" class="w-20 px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:border-black focus:outline-none text-pink-600 font-semibold text-right bg-white transition-colors" />
            </td>
            <td class="py-3 px-3 text-center">
              <!-- stock_quantity: tồn kho (backend field) -->
              <input v-model.number="v.stock_quantity" type="number" min="0" placeholder="0" class="w-16 px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:border-black focus:outline-none text-center font-bold bg-white transition-colors" />
            </td>
            <td class="py-3 px-3 text-center">
              <!-- is_active: kích hoạt (backend field) -->
              <button 
                @click="v.is_active = !v.is_active"
                type="button"
                :class="v.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200'"
                class="inline-block text-[10px] font-bold px-2 py-0.5 rounded border transition-colors cursor-pointer"
              >
                {{ v.is_active ? 'BẬT' : 'TẮT' }}
              </button>
            </td>
          </tr>

          <tr v-if="variants.length === 0">
            <td colspan="8" class="py-8 text-center text-sm text-slate-400">
              Vui lòng thêm nhóm thuộc tính và giá trị để sinh biến thể sản phẩm.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineModel, nextTick } from 'vue'
import { useAttributeStore } from '@/stores/admin/attributeStore'
import { uploadService } from '@/services/admin/uploadService'
import { useCategoryStore } from '@/stores/admin/categoryStore'

const props = defineProps({
  categoryId: { type: [String, Number], default: '' },
  brand: { type: String, default: '' },
  productName: { type: String, default: '' },
})

const variants = defineModel({ default: () => [] })
const InputAttributeGroup = ref([])
const attributeStore = useAttributeStore()
const categoryStore = useCategoryStore()
attributeStore.initialFetch()

// ==========================================
// LOGIC KHU VỰC 1: THUỘC TÍNH (ATTRIBUTES)
// ==========================================
const filterAttributeGroup = (keyword) => {
    const attributeGroup = attributeStore.attributes || [];
    if (!keyword) return attributeGroup;
    return attributeGroup.filter(group => group.name.toLowerCase().includes(keyword.toLowerCase()));
}

const selectAttribute = (group, selectedAttr) => {
    group.name = selectedAttr.name;
    group.attribute_id = selectedAttr.id; 
    group.showSuggestions = false;
}

const hideSuggestionsDelay = (group) => {
    setTimeout(() => { group.showSuggestions = false; }, 200);
}

const addInputAttributeValue = () => {
    InputAttributeGroup.value.push({
        attribute_id: null,
        name: '',
        values: [],
        showSuggestions: false
    })
}

const deleteInputAttributeGroup = (index) => {
    InputAttributeGroup.value.splice(index, 1);
}

const addValueToGroup = (group, event) => {
    const value = event.target.value.trim();
    if (value && !group.values.includes(value)) {
        group.values.push(value);
    }
    event.target.value = '';
}

const removeValueFromGroup = (group, valIndex) => {
    group.values.splice(valIndex, 1);
}

// ==========================================
// LOGIC: ẢNH BIẾN THỂ (VARIANT IMAGES)
// ==========================================
const variantFileInputRef = ref(null);
const currentVariantEditingIndex = ref(null);

const triggerVariantImage = (index) => {
  currentVariantEditingIndex.value = index;
  variantFileInputRef.value.click();
}

/**
 * Upload ảnh biến thể ngay khi chọn:
 * 1. Hiện preview bằng blob URL + spinner
 * 2. Upload lên server
 * 3. Lưu URL storage vào v.thumbnail (field backend dùng)
 */
const handleVariantImageChange = async (event) => {
  const file = event.target.files[0];
  if (!file || !file.type.startsWith('image/')) return;
  if (file.size > 5 * 1024 * 1024) {
    console.warn('Kích thước ảnh vượt quá 5MB.');
    return;
  }

  const variant = variants.value[currentVariantEditingIndex.value];

  // Giải phóng blob URL cũ nếu có
  if (variant._image_preview?.startsWith('blob:')) {
    URL.revokeObjectURL(variant._image_preview);
  }

  // Hiện preview ngay bằng blob URL
  variant._image_preview = URL.createObjectURL(file);
  variant._uploading = true;
  variant._upload_error = null;

  event.target.value = '';

  // Upload ngay lên server
  try {
    const result = await uploadService.uploadImage(file, 'variants');
    // Lưu URL storage vào thumbnail (field backend)
    variant.thumbnail = result.url;
    variant._storage_path = result.path;
  } catch (err) {
    variant._upload_error = err.message || 'Upload thất bại';
    variant.thumbnail = null;
    console.error('[ProductVariantsUploader] Upload ảnh biến thể thất bại:', err);
  } finally {
    variant._uploading = false;
  }
}

const removeVariantImage = (index) => {
  const variant = variants.value[index];
  // Giải phóng blob URL
  if (variant._image_preview?.startsWith('blob:')) {
    URL.revokeObjectURL(variant._image_preview);
  }
  // Xóa file trên storage nếu chưa lưu vào DB
  if (!variant.id && variant._storage_path) {
    uploadService.deleteImage(variant._storage_path).catch(() => {});
  }
  variant._image_file = null;
  variant._image_preview = null;
  variant.thumbnail = null;
  variant._storage_path = null;
  variant._upload_error = null;
}

// ==========================================
// LOGIC KHU VỰC 2: TỰ ĐỘNG SINH BIẾN THỂ
// ==========================================
const generateVariants = () => {
  // Chỉ lấy các nhóm hợp lệ (có tên và có ít nhất 1 giá trị)
  const validGroups = InputAttributeGroup.value.filter(g => g.name.trim() !== "" && g.values.length > 0);
  
  if (validGroups.length === 0) {
    variants.value = [];
    return;
  }

  // Tính tích Descartes (cartesian product) của tất cả các nhóm giá trị
  let combinations = [[]];
  validGroups.forEach(group => {
    const nextCombinations = [];
    combinations.forEach(currentCombo => {
      group.values.forEach(value => {
        nextCombinations.push([...currentCombo, { group, value }]);
      });
    });
    combinations = nextCombinations;
  });

  const oldVariants = [...variants.value];

  // Helper functions for SKU formatting
  const toAcronym = (str) => {
    if (!str) return ''
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D')
              .toUpperCase().split(' ').map(w => w.charAt(0)).filter(Boolean).join('')
  }
  const toCode = (str) => {
    if (!str) return ''
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D')
              .toUpperCase().replace(/\s+/g, '')
  }
  const cat = categoryStore.categories.find(c => c.id == props.categoryId)?.name || ''
  const partCat = toAcronym(cat) 
  const partBrand = toCode(props.brand).substring(0, 3)
  const partStyle = toCode(props.productName).substring(0, 4)
  const skuPrefix = [partCat, partBrand, partStyle].filter(Boolean).join('-')

  variants.value = combinations.map(combo => {
    // KEY ẩn để nhận diện dòng chính xác kể cả khi bị sửa tên (sắp xếp theo attribute_id)
    const variantKey = [...combo].sort((a, b) => (a.group.attribute_id || 0) - (b.group.attribute_id || 0)).map(c => c.value).join("|||"); 
    const defaultName = combo.map(c => c.value).join(" - ");
    
    // Auto generate SKU from formula
    const skuSuffix = combo.map(c => toCode(c.value).substring(0, 3)).join('-')
    const skuSuggestion = [skuPrefix, skuSuffix].filter(Boolean).join('-').toUpperCase()
    
    // Tìm variant cũ theo key để giữ lại dữ liệu người dùng đã nhập
    const existingVariant = oldVariants.find(v => v.key === variantKey);

    if (existingVariant) {
      return existingVariant; 
    } else {
      return {
        // === FIELDS INTERNAL (không gửi API, chỉ dùng UI) ===
        key: variantKey,           // Key ẩn để diff khi re-generate
        name: defaultName,         // Tên hiển thị (input có thể sửa)
        _image_file: null,         // File gốc để upload
        _image_preview: null,      // Blob URL để preview

        // === FIELDS GỬI LÊN BACKEND (đúng tên field) ===
        sku: skuSuggestion,
        cost_price: null,          // Giá vốn
        price: null,               // Giá bán chính (backend: price)
        sale_price: null,          // Giá khuyến mãi (backend: sale_price)
        stock_quantity: 0,         // Số lượng tồn kho (backend: stock_quantity)
        is_active: true,           // Trạng thái (backend: is_active)
        thumbnail: null,           // URL ảnh sau khi upload (backend: thumbnail)

        // attribute_values: mảng { attribute_id, value } đúng cấu trúc backend
        attribute_values: combo.map(c => ({
          attribute_id: c.group.attribute_id, // null nếu người dùng chưa chọn từ gợi ý
          value: c.value,
        })),
      };
    }
  });
}

const generateSKUsForVariants = () => {
  const toAcronym = (str) => {
    if (!str) return ''
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D')
              .toUpperCase().split(' ').map(w => w.charAt(0)).filter(Boolean).join('')
  }
  
  const toCode = (str) => {
    if (!str) return ''
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D')
              .toUpperCase().replace(/\s+/g, '')
  }

  const cat = categoryStore.categories.find(c => c.id == props.categoryId)?.name || ''
  
  const partCat = toAcronym(cat) 
  const partBrand = toCode(props.brand).substring(0, 3)
  const partStyle = toCode(props.productName).substring(0, 4)
  
  const prefix = [partCat, partBrand, partStyle].filter(Boolean).join('-')

  variants.value.forEach(v => {
    const suffix = (v.attribute_values || []).map(av => toCode(av.value).substring(0, 3)).join('-')
    v.sku = [prefix, suffix].filter(Boolean).join('-').toUpperCase()
  })
}

// suppressGenerate: ngăn generateVariants() chạy khi reconstruct InputAttributeGroup
// từ dữ liệu API trong chế độ Edit — giữ nguyên giá / ảnh đã load
const suppressGenerate = ref(false)

watch(InputAttributeGroup, () => {
  if (suppressGenerate.value) return
  generateVariants();
}, { deep: true })

// Tái tạo InputAttributeGroup từ các biến thể hiện tại (chế độ Edit)
const hasInitializedGroups = ref(false)

watch(
  [() => variants.value, () => attributeStore.attributes],
  ([newVariants, newAttributes]) => {
    if (hasInitializedGroups.value) return
    if (!newVariants || newVariants.length === 0) return
    if (!newAttributes || newAttributes.length === 0) return

    // Chỉ tái tạo nếu InputAttributeGroup đang trống
    if (InputAttributeGroup.value.length > 0) return

    const groupsMap = {}
    newVariants.forEach(v => {
      if (!v.attribute_values) return
      v.attribute_values.forEach(av => {
        const attrId = av.attribute_id
        if (!attrId) return

        if (!groupsMap[attrId]) {
          const attrObj = newAttributes.find(a => a.id === attrId)
          groupsMap[attrId] = {
            attribute_id: attrId,
            name: attrObj ? attrObj.name : `Thuộc tính #${attrId}`,
            values: new Set(),
            showSuggestions: false
          }
        }
        if (av.value) {
          groupsMap[attrId].values.add(av.value)
        }
      })
    })

    const reconstructed = Object.values(groupsMap).map(g => ({
      attribute_id: g.attribute_id,
      name: g.name,
      values: Array.from(g.values),
      showSuggestions: false
    }))

    if (reconstructed.length > 0) {
      // Bật cờ suppress TRƯỚC khi set InputAttributeGroup để chặn generateVariants()
      // Dữ liệu variants (giá, ảnh) đã load từ API — không cần regenerate
      suppressGenerate.value = true
      InputAttributeGroup.value = reconstructed
      hasInitializedGroups.value = true
      // Tắt cờ suppress sau khi Vue flush xong (nextTick)
      nextTick(() => {
        suppressGenerate.value = false
      })
    }
  },
  { immediate: true, deep: true }
)
</script>