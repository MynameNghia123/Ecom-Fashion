<template>
  <div class="space-y-10 animate-fade-in">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title">Thông tin cá nhân</h1>
      <p class="text-sm text-neutral-400 mt-2 font-text">Quản lý tên, email và số điện thoại của bạn.</p>
    </div>

    <!-- Alert -->
    <transition name="fade">
      <div v-if="alert.show"
        :class="['px-4 py-3 text-sm font-medium border', alert.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700']"
      >
        {{ alert.message }}
      </div>
    </transition>

    <!-- Form -->
    <form @submit.prevent="handleSave" class="space-y-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- First Name -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Họ</label>
          <input
            v-model="form.first_name"
            type="text"
            placeholder="Nguyễn"
            class="w-full border border-neutral-200 px-4 py-3 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none transition-colors bg-white"
          />
          <p v-if="errors.first_name" class="text-xs text-rose-500">{{ errors.first_name[0] }}</p>
        </div>

        <!-- Last Name -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Tên</label>
          <input
            v-model="form.last_name"
            type="text"
            placeholder="Anh Tuấn"
            class="w-full border border-neutral-200 px-4 py-3 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none transition-colors bg-white"
          />
          <p v-if="errors.last_name" class="text-xs text-rose-500">{{ errors.last_name[0] }}</p>
        </div>
      </div>

      <!-- Email (readonly) -->
      <div class="space-y-2">
        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Email</label>
        <div class="flex items-center border border-neutral-100 bg-neutral-50 px-4 py-3 gap-3">
          <input
            :value="authStore.user?.email"
            type="email"
            readonly
            class="flex-1 bg-transparent text-sm text-neutral-400 focus:outline-none cursor-not-allowed"
          />
          <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 border border-neutral-200 px-2 py-0.5">Khóa</span>
        </div>
        <p class="text-[11px] text-neutral-400">Email không thể thay đổi sau khi đăng ký.</p>
      </div>

      <!-- Phone -->
      <div class="space-y-2">
        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Số điện thoại</label>
        <input
          v-model="form.phone_number"
          type="tel"
          placeholder="0912 345 678"
          class="w-full border border-neutral-200 px-4 py-3 text-sm text-neutral-900 focus:border-neutral-900 focus:outline-none transition-colors bg-white"
        />
        <p v-if="errors.phone_number" class="text-xs text-rose-500">{{ errors.phone_number[0] }}</p>
      </div>

      <!-- Submit -->
      <div class="flex items-center gap-4 pt-2">
        <button
          type="submit"
          :disabled="authStore.loading"
          class="bg-neutral-900 text-white text-[11px] font-bold uppercase tracking-widest px-8 py-3.5 hover:bg-neutral-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ authStore.loading ? 'Đang lưu...' : 'Lưu thông tin' }}
        </button>
        <button type="button" @click="resetForm" class="text-[11px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-700 transition-colors bg-transparent border-none cursor-pointer">
          Hủy
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="border-t border-neutral-100 pt-10 space-y-6">
      <div>
        <h2 class="text-[18px] font-bold tracking-tight text-neutral-900 uppercase font-title">Đổi mật khẩu</h2>
        <p class="text-sm text-neutral-400 mt-1">Đảm bảo mật khẩu mới có ít nhất 8 ký tự.</p>
      </div>

      <!-- Password alert -->
      <transition name="fade">
        <div v-if="pwAlert.show"
          :class="['px-4 py-3 text-sm font-medium border', pwAlert.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700']"
        >
          {{ pwAlert.message }}
        </div>
      </transition>

      <form @submit.prevent="handleChangePassword" class="space-y-6">
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Mật khẩu hiện tại</label>
          <input v-model="pwForm.current_password" type="password" placeholder="••••••••"
            class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Mật khẩu mới</label>
            <input v-model="pwForm.password" type="password" placeholder="••••••••"
              class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Xác nhận mật khẩu mới</label>
            <input v-model="pwForm.password_confirmation" type="password" placeholder="••••••••"
              class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
          </div>
        </div>
        <button type="submit" :disabled="pwLoading"
          class="bg-neutral-900 text-white text-[11px] font-bold uppercase tracking-widest px-8 py-3.5 hover:bg-neutral-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          {{ pwLoading ? 'Đang đổi...' : 'Đổi mật khẩu' }}
        </button>
      </form>
    </div>

  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useClientAuthStore } from '@/stores/client/authStore'
import { profileService } from '@/services/client/profileService'

const authStore = useClientAuthStore()

// ── Thông tin cá nhân ───────────────────────────────────────────
const form = ref({ first_name: '', last_name: '', phone_number: '' })
const errors = ref({})
const alert = ref({ show: false, type: 'success', message: '' })

// Populate form khi user load xong
watch(() => authStore.user, (u) => {
  if (u) {
    form.value.first_name   = u.first_name  || ''
    form.value.last_name    = u.last_name   || ''
    form.value.phone_number = u.phone_number || ''
  }
}, { immediate: true })

const resetForm = () => {
  const u = authStore.user
  if (u) {
    form.value = { first_name: u.first_name || '', last_name: u.last_name || '', phone_number: u.phone_number || '' }
  }
  errors.value = {}
}

const showAlert = (type, message, target = 'main') => {
  const ref_ = target === 'pw' ? pwAlert : alert
  ref_.value = { show: true, type, message }
  setTimeout(() => { ref_.value.show = false }, 4000)
}

const handleSave = async () => {
  errors.value = {}
  const result = await authStore.updateProfile(form.value)
  if (result.success) {
    showAlert('success', result.message)
  } else {
    showAlert('error', result.message)
    if (authStore.error) errors.value = {}
  }
}

// ── Đổi mật khẩu ─────────────────────────────────────────────
const pwForm    = ref({ current_password: '', password: '', password_confirmation: '' })
const pwLoading = ref(false)
const pwAlert   = ref({ show: false, type: 'success', message: '' })

const handleChangePassword = async () => {
  pwLoading.value = true
  try {
    const res = await profileService.changePassword(pwForm.value)
    if (res.data?.success) {
      showAlert('success', res.data.message, 'pw')
      pwForm.value = { current_password: '', password: '', password_confirmation: '' }
    }
  } catch (e) {
    showAlert('error', e.response?.data?.message || 'Lỗi khi đổi mật khẩu.', 'pw')
  } finally {
    pwLoading.value = false
  }
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>