<template>
  <div class="space-y-10 animate-fade-in text-[#111111] font-text">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title leading-tight">Sổ địa chỉ</h1>
        <p class="text-sm text-neutral-400 mt-2">Quản lý các địa chỉ nhận hàng của bạn.</p>
      </div>
      <button
        v-if="!showForm"
        @click="openAddForm"
        class="bg-black text-white hover:bg-neutral-800 text-[11px] font-bold uppercase tracking-widest px-6 py-3 transition-colors flex-shrink-0 cursor-pointer border-none"
      >
        Thêm địa chỉ mới
      </button>
    </div>

    <!-- Alert / Message -->
    <transition name="fade">
      <div v-if="alert.show"
        :class="['px-4 py-3 text-sm font-medium border', alert.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700']"
      >
        {{ alert.message }}
      </div>
    </transition>

    <!-- Address Add/Edit Form -->
    <div v-if="showForm" class="border border-neutral-200 p-6 bg-[#FBFBFB] space-y-6">
      <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-700">
        {{ editingAddress ? 'Cập nhật địa chỉ' : 'Thêm địa chỉ mới' }}
      </h3>

      <form @submit.prevent="handleSave" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- Name -->
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Tên người nhận</label>
            <input
              v-model="form.receiver_name"
              type="text"
              required
              placeholder="Nguyễn Văn A"
              class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
            />
          </div>
          <!-- Phone -->
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Số điện thoại</label>
            <input
              v-model="form.receiver_phone"
              type="tel"
              required
              placeholder="0901234567"
              class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <!-- Province -->
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Tỉnh / Thành phố</label>
            <input
              v-model="form.province"
              type="text"
              required
              placeholder="Hà Nội / TP.HCM"
              class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
            />
          </div>
          <!-- District -->
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Quận / Huyện</label>
            <input
              v-model="form.district"
              type="text"
              required
              placeholder="Quận 1"
              class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
            />
          </div>
          <!-- Ward -->
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Phường / Xã</label>
            <input
              v-model="form.ward"
              type="text"
              required
              placeholder="Bến Nghé"
              class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
            />
          </div>
        </div>

        <!-- Detail Address -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Địa chỉ chi tiết (Số nhà, đường...)</label>
          <input
            v-model="form.detail_address"
            type="text"
            required
            placeholder="123 Đường Lê Lợi"
            class="w-full border border-neutral-200 px-4 py-3 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors"
          />
        </div>

        <!-- Default Checkbox -->
        <div class="flex items-center gap-3">
          <input
            id="is_default"
            v-model="form.is_default"
            type="checkbox"
            class="w-4 h-4 accent-black"
          />
          <label for="is_default" class="text-xs text-neutral-700 cursor-pointer select-none">Đặt làm địa chỉ mặc định</label>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4 pt-2">
          <button
            type="submit"
            :disabled="submitting"
            class="bg-neutral-900 text-white text-[11px] font-bold uppercase tracking-widest px-8 py-3.5 hover:bg-neutral-700 transition-colors disabled:opacity-50"
          >
            {{ submitting ? 'Đang lưu...' : 'Lưu địa chỉ' }}
          </button>
          <button
            type="button"
            @click="closeForm"
            class="text-[11px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-700 transition-colors bg-transparent border-none cursor-pointer"
          >
            Hủy
          </button>
        </div>
      </form>
    </div>

    <!-- Address List -->
    <div v-else-if="loading" class="py-16 text-center">
      <svg class="animate-spin w-6 h-6 mx-auto text-neutral-400" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
      </svg>
    </div>

    <div v-else-if="addresses.length > 0" class="space-y-4">
      <div
        v-for="addr in addresses"
        :key="addr.id"
        class="border p-6 flex flex-col sm:flex-row justify-between sm:items-start gap-4 transition-all duration-300 bg-white"
        :class="addr.is_default ? 'border-neutral-900 bg-[#FBFBFB]' : 'border-neutral-200'"
      >
        <div class="space-y-1.5">
          <div class="flex items-center gap-3">
            <span v-if="addr.is_default" class="inline-block text-[9px] font-bold uppercase tracking-widest border border-neutral-900 bg-neutral-900 text-white px-2 py-0.5">
              Mặc định
            </span>
            <p class="text-sm font-bold text-neutral-900">{{ addr.receiver_name }}</p>
          </div>
          <p class="text-sm text-neutral-600 font-mono">{{ addr.receiver_phone }}</p>
          <p class="text-sm text-neutral-600 max-w-lg">
            {{ addr.detail_address }}, {{ addr.ward }}, {{ addr.district }}, {{ addr.province }}
          </p>
        </div>

        <div class="flex flex-row sm:flex-col items-end gap-3 flex-shrink-0">
          <div class="flex items-center gap-4">
            <button
              @click="openEditForm(addr)"
              class="text-[10px] font-bold uppercase tracking-widest text-neutral-600 hover:text-neutral-900 transition-colors bg-transparent border-none cursor-pointer underline"
            >
              Chỉnh sửa
            </button>
            <button
              @click="handleDelete(addr.id)"
              class="text-[10px] font-bold uppercase tracking-widest text-rose-500 hover:text-rose-700 transition-colors bg-transparent border-none cursor-pointer underline"
            >
              Xóa
            </button>
          </div>
          <button
            v-if="!addr.is_default"
            @click="handleSetDefault(addr.id)"
            class="text-[9px] font-bold uppercase tracking-widest border border-neutral-300 hover:border-neutral-900 px-3 py-1.5 text-neutral-700 hover:bg-neutral-900 hover:text-white transition-all bg-white cursor-pointer"
          >
            Đặt mặc định
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="py-16 text-center border border-dashed border-neutral-200">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-neutral-300 mb-4">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
        <circle cx="12" cy="9" r="2.5"/>
      </svg>
      <p class="text-sm text-neutral-400 mb-4">Bạn chưa có địa chỉ nào trong sổ địa chỉ.</p>
      <button
        @click="openAddForm"
        class="bg-black text-white hover:bg-neutral-800 text-[11px] font-bold uppercase tracking-widest px-6 py-3 transition-colors cursor-pointer border-none"
      >
        Thêm địa chỉ mới
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services/client/profileService'

const addresses  = ref([])
const loading    = ref(false)
const submitting = ref(false)
const showForm   = ref(false)
const editingAddress = ref(null)

const alert = ref({ show: false, type: 'success', message: '' })
const showAlert = (type, message) => {
  alert.value = { show: true, type, message }
  setTimeout(() => { alert.value.show = false }, 4000)
}

const form = ref({
  receiver_name: '',
  receiver_phone: '',
  province: '',
  district: '',
  ward: '',
  detail_address: '',
  is_default: false
})

const fetchAddresses = async () => {
  loading.value = true
  try {
    const res = await profileService.getAddresses()
    if (res.data?.success) {
      addresses.value = res.data.data
    }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchAddresses)

const openAddForm = () => {
  editingAddress.value = null
  form.value = {
    receiver_name: '',
    receiver_phone: '',
    province: '',
    district: '',
    ward: '',
    detail_address: '',
    is_default: addresses.value.length === 0 // automatic default if first address
  }
  showForm.value = true
}

const openEditForm = (addr) => {
  editingAddress.value = addr
  form.value = { ...addr }
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
  editingAddress.value = null
}

const handleSave = async () => {
  submitting.value = true
  try {
    let res
    if (editingAddress.value) {
      res = await profileService.updateAddress(editingAddress.value.id, form.value)
    } else {
      res = await profileService.createAddress(form.value)
    }

    if (res.data?.success) {
      showAlert('success', res.data.message)
      showForm.value = false
      await fetchAddresses()
    }
  } catch (e) {
    showAlert('error', e.response?.data?.message || 'Có lỗi xảy ra, vui lòng kiểm tra lại.')
  } finally {
    submitting.value = false
  }
}

const handleDelete = async (id) => {
  if (!confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) return
  try {
    const res = await profileService.deleteAddress(id)
    if (res.data?.success) {
      showAlert('success', res.data.message)
      await fetchAddresses()
    }
  } catch (e) {
    showAlert('error', 'Không thể xóa địa chỉ lúc này.')
  }
}

const handleSetDefault = async (id) => {
  try {
    const res = await profileService.setDefaultAddress(id)
    if (res.data?.success) {
      showAlert('success', res.data.message)
      await fetchAddresses()
    }
  } catch (e) {
    showAlert('error', 'Không thể đặt mặc định.')
  }
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
</style>
