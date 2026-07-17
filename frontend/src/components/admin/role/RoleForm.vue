<template>
  <div v-if="show" class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="close"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[90vh]">

      <!-- Header -->
      <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100 shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg v-if="isAdding" class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <svg v-else class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <h2 class="text-base font-bold text-slate-800">{{ isAdding ? 'Thêm vai trò mới' : 'Chỉnh sửa vai trò' }}</h2>
        </div>
        <button @click="close" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-7 py-6 overflow-y-auto space-y-6 flex-1">

        <!-- Name input -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Tên vai trò <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.name"
            name="name"
            type="text"
            placeholder="VD: Quản lý kho"
            class="w-full max-w-sm px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
            :class="{ 'border-red-500 bg-red-50/50': fieldError('name') }"
            @input="formErrors.name = null"
          />
          <p v-if="fieldError('name')" class="text-red-500 text-xs mt-1.5 font-medium">{{ fieldError('name') }}</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả</label>
          <textarea
            v-model="form.description"
            name="description"
            rows="2"
            placeholder="Mô tả ngắn về vai trò này..."
            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all resize-none"
            :class="{ 'border-red-500 bg-red-50/50': fieldError('description') }"
            @input="formErrors.description = null"
          ></textarea>
          <p v-if="fieldError('description')" class="text-red-500 text-xs mt-1.5 font-medium">{{ fieldError('description') }}</p>
        </div>

        <div class="border-t border-slate-100"></div>

        <!-- Permission Matrix -->
        <div>
          <h3 class="text-sm font-bold text-slate-800 mb-1">Phân quyền Module</h3>
          <p class="text-xs text-slate-500 mb-4">Chọn các quyền truy cập tương ứng cho từng phân hệ.</p>

          <div class="border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
              <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-[40%]">
                    <div class="flex items-center gap-2">
                      <input 
                        type="checkbox" 
                        @change="toggleAll" 
                        :checked="isAllSelected" 
                        class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" 
                        title="Chọn tất cả"
                      />
                      <span>Chức năng</span>
                    </div>
                  </th>
                  <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Read</th>
                  <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Create</th>
                  <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Write</th>
                  <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Delete</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(perms, moduleName) in permissionMatrix" :key="moduleName" class="hover:bg-blue-50/20 transition-colors">
                  <td class="py-3 px-4 text-slate-700 font-medium text-xs border-r border-slate-50 uppercase">
                    <div class="flex items-center gap-2">
                      <input 
                        type="checkbox" 
                        @change="toggleRow(perms, $event)" 
                        :checked="isRowSelected(perms)" 
                        class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" 
                        :title="'Chọn tất cả ' + moduleName"
                      />
                      <span>{{ moduleName }}</span>
                    </div>
                  </td>
                  
                  <td v-for="action in ['read', 'create', 'update', 'delete']" :key="action" class="py-3 px-4 text-center">
                    <input 
                      v-if="getPermissionId(perms, action)"
                      type="checkbox" 
                      :value="getPermissionId(perms, action)"
                      v-model="form.permission_ids"
                      class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" 
                    />
                    <span v-else class="text-slate-300">-</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100 shrink-0">
        <button @click="close" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy bỏ</button>
        <button @click="handleSubmit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all active:scale-[0.98]">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          {{ isAdding ? 'Thêm vai trò' : 'Lưu thay đổi' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed, nextTick } from 'vue'
import { useRoleValidation } from '@/composables/admin/validation/useRoleValidation'

const props = defineProps({
  show: Boolean,
  isAdding: Boolean,
  initialData: Object,
  permissionMatrix: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save'])

const { formErrors, validate, clearErrors, fieldError, applyBackendErrors } = useRoleValidation()

const form = ref({
  name: '',
  description: '',
  permission_ids: []
})

watch(() => props.show, (newVal) => {
  if (newVal) {
    clearErrors()
    if (props.isAdding) {
      form.value = { name: '', description: '', permission_ids: [] }
    } else {
      form.value = { 
        name: props.initialData?.name || '', 
        description: props.initialData?.description || '',
        permission_ids: props.initialData?.permissions?.map(p => p.id) || []
      }
    }
  }
})

const close = () => {
  emit('close')
}

const focusFirstError = async () => {
  await nextTick()
  const errorFields = Object.keys(formErrors)
  if (errorFields.length > 0) {
    const firstField = errorFields[0]
    const el = document.querySelector(`[name="${firstField}"]`)
    if (el) el.focus()
  }
}

const handleSubmit = () => {
  if (!validate(form.value)) {
    focusFirstError()
    return
  }
  emit('save', {
    formData: { ...form.value },
    applyBackendErrors,
    focusFirstError
  })
}

const getPermissionId = (perms, action) => {
  const p = perms.find(x => x.action === action);
  return p ? p.id : null;
};

// --- Check All / Row Logic ---
const allPermissionIds = computed(() => {
  const ids = [];
  for (const moduleName in props.permissionMatrix) {
    const perms = props.permissionMatrix[moduleName];
    perms.forEach(p => ids.push(p.id));
  }
  return ids;
});

const isAllSelected = computed(() => {
  return allPermissionIds.value.length > 0 && allPermissionIds.value.every(id => form.value.permission_ids.includes(id));
});

const toggleAll = (e) => {
  if (e.target.checked) {
    const current = new Set(form.value.permission_ids);
    allPermissionIds.value.forEach(id => current.add(id));
    form.value.permission_ids = Array.from(current);
  } else {
    const allIds = allPermissionIds.value;
    form.value.permission_ids = form.value.permission_ids.filter(id => !allIds.includes(id));
  }
};

const getRowIds = (perms) => perms.map(p => p.id);

const isRowSelected = (perms) => {
  const rowIds = getRowIds(perms);
  return rowIds.length > 0 && rowIds.every(id => form.value.permission_ids.includes(id));
};

const toggleRow = (perms, e) => {
  const rowIds = getRowIds(perms);
  if (e.target.checked) {
    const current = new Set(form.value.permission_ids);
    rowIds.forEach(id => current.add(id));
    form.value.permission_ids = Array.from(current);
  } else {
    form.value.permission_ids = form.value.permission_ids.filter(id => !rowIds.includes(id));
  }
};
</script>

<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
