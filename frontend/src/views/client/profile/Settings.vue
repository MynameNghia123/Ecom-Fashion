<template>
  <div class="space-y-10 animate-fade-in">

    <!-- Header -->
    <div>
      <h1 class="text-[32px] font-bold tracking-tight text-neutral-900 uppercase font-title">Cài đặt</h1>
      <p class="text-sm text-neutral-400 mt-2 font-text">Quản lý bảo mật và cài đặt tài khoản của bạn.</p>
    </div>

    <!-- Alert -->
    <transition name="fade">
      <div v-if="alert.show"
        :class="['px-4 py-3 text-sm font-medium border', alert.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700']"
      >
        {{ alert.message }}
      </div>
    </transition>

    <!-- ─── Bảo mật ──────────────────────────────────────────────────── -->
    <section class="space-y-6">
      <div class="border-b border-neutral-100 pb-3">
        <h2 class="text-[13px] font-bold uppercase tracking-widest text-neutral-700">Đổi mật khẩu</h2>
      </div>

      <form @submit.prevent="handleChangePassword" class="space-y-5">
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Mật khẩu hiện tại</label>
          <input v-model="pw.current" type="password" placeholder="••••••••"
            class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Mật khẩu mới</label>
            <input v-model="pw.new" type="password" placeholder="••••••••"
              class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">Xác nhận mật khẩu</label>
            <input v-model="pw.confirm" type="password" placeholder="••••••••"
              class="w-full border border-neutral-200 px-4 py-3 text-sm focus:border-neutral-900 focus:outline-none transition-colors" />
          </div>
        </div>

        <!-- Password strength -->
        <div v-if="pw.new" class="space-y-1">
          <div class="flex gap-1">
            <div v-for="n in 4" :key="n" :class="['h-1 flex-1 rounded-full transition-colors', strength >= n ? strengthColor : 'bg-neutral-100']"></div>
          </div>
          <p :class="['text-[11px] font-medium', strength <= 1 ? 'text-rose-500' : strength <= 2 ? 'text-amber-500' : strength <= 3 ? 'text-blue-500' : 'text-emerald-600']">
            {{ strengthLabel }}
          </p>
        </div>

        <button type="submit" :disabled="pwLoading"
          class="bg-neutral-900 text-white text-[11px] font-bold uppercase tracking-widest px-8 py-3.5 hover:bg-neutral-700 transition-colors disabled:opacity-50">
          {{ pwLoading ? 'Đang đổi...' : 'Đổi mật khẩu' }}
        </button>
      </form>
    </section>

    <!-- ─── Tuỳ chọn thông báo ─────────────────────────────────────── -->
    <section class="space-y-5">
      <div class="border-b border-neutral-100 pb-3">
        <h2 class="text-[13px] font-bold uppercase tracking-widest text-neutral-700">Tùy chọn thông báo</h2>
      </div>

      <div class="space-y-4">
        <div v-for="pref in prefs" :key="pref.key" class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-neutral-800">{{ pref.label }}</p>
            <p class="text-xs text-neutral-400">{{ pref.desc }}</p>
          </div>
          <!-- Toggle -->
          <button
            @click="pref.enabled = !pref.enabled"
            :class="['relative inline-flex w-10 h-5 rounded-full transition-colors duration-300 focus:outline-none', pref.enabled ? 'bg-neutral-900' : 'bg-neutral-200']"
          >
            <span :class="['absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-300', pref.enabled ? 'translate-x-5' : 'translate-x-0']"></span>
          </button>
        </div>
      </div>
    </section>

    <!-- ─── Vùng nguy hiểm ────────────────────────────────────────── -->
    <section class="space-y-5">
      <div class="border-b border-rose-100 pb-3">
        <h2 class="text-[13px] font-bold uppercase tracking-widest text-rose-500">Vùng nguy hiểm</h2>
      </div>

      <div class="border border-rose-100 bg-rose-50/30 p-5 flex items-center justify-between gap-4">
        <div>
          <p class="text-sm font-semibold text-neutral-900">Đăng xuất khỏi tất cả thiết bị</p>
          <p class="text-xs text-neutral-500 mt-0.5">Đăng xuất tài khoản khỏi máy bạn và xóa token đăng nhập.</p>
        </div>
        <button @click="handleLogout"
          class="flex-shrink-0 border border-rose-400 text-rose-600 text-[11px] font-bold uppercase tracking-widest px-5 py-2.5 hover:bg-rose-600 hover:text-white transition-colors bg-transparent cursor-pointer">
          Đăng xuất
        </button>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useClientAuthStore } from '@/stores/client/authStore'
import { profileService } from '@/services/client/profileService'

const authStore = useClientAuthStore()
const router    = useRouter()

// ─── Alert ────────────────────────────────────────────────
const alert = ref({ show: false, type: 'success', message: '' })
const showAlert = (type, message) => {
  alert.value = { show: true, type, message }
  setTimeout(() => { alert.value.show = false }, 4000)
}

// ─── Đổi mật khẩu ───────────────────────────────────────
const pw        = ref({ current: '', new: '', confirm: '' })
const pwLoading = ref(false)

const strength = computed(() => {
  const p = pw.value.new
  if (!p) return 0
  let s = 0
  if (p.length >= 8)              s++
  if (/[A-Z]/.test(p))            s++
  if (/[0-9]/.test(p))            s++
  if (/[^A-Za-z0-9]/.test(p))    s++
  return s
})

const strengthColor = computed(() => {
  if (strength.value <= 1) return 'bg-rose-400'
  if (strength.value <= 2) return 'bg-amber-400'
  if (strength.value <= 3) return 'bg-blue-400'
  return 'bg-emerald-500'
})

const strengthLabel = computed(() => {
  return ['', 'Yếu', 'Trung bình', 'Mạnh', 'Rất mạnh'][strength.value] || ''
})

const handleChangePassword = async () => {
  if (pw.value.new !== pw.value.confirm) {
    return showAlert('error', 'Xác nhận mật khẩu không khớp.')
  }
  pwLoading.value = true
  try {
    const res = await profileService.changePassword({
      current_password:      pw.value.current,
      password:              pw.value.new,
      password_confirmation: pw.value.confirm,
    })
    if (res.data?.success) {
      showAlert('success', res.data.message)
      pw.value = { current: '', new: '', confirm: '' }
    }
  } catch (e) {
    showAlert('error', e.response?.data?.message || 'Lỗi khi đổi mật khẩu.')
  } finally {
    pwLoading.value = false
  }
}

// ─── Tùy chọn thông báo ───────────────────────────────────
const prefs = ref([
  { key: 'order_updates', label: 'Cập nhật đơn hàng', desc: 'Nhận thông báo khi trạng thái đơn hàng thay đổi.', enabled: true },
  { key: 'promotions',    label: 'Khuyến mãi & ưu đãi', desc: 'Thông báo về deal mới và sale event.', enabled: false },
  { key: 'newsletter',    label: 'Bản tin hàng tuần', desc: 'Email tổng hợp xu hướng thời trang.', enabled: false },
])

// ─── Đăng xuất ─────────────────────────────────────────────
const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
}
</script>

<style scoped>
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>