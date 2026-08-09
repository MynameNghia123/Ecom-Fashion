<template>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
    <!-- Province -->
    <div :class="wrapClass">
      <label :class="labelClass">Tỉnh / Thành phố</label>
      <div class="relative">
        <select
          v-model="selectedProvince"
          @change="onProvinceChange"
          :disabled="loadingProvinces"
          :class="selectClass"
          required
        >
          <option value="">-- Chọn tỉnh/thành --</option>
          <option v-for="p in provinces" :key="getPKey(p)" :value="p">{{ getPName(p) }}</option>
        </select>
        <div v-if="loadingProvinces" class="absolute right-3 top-1/2 -translate-y-1/2">
          <div class="w-3.5 h-3.5 border-2 border-neutral-300 border-t-black rounded-full animate-spin"></div>
        </div>
        <svg v-else class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <!-- District -->
    <div :class="wrapClass">
      <label :class="labelClass">Quận / Huyện</label>
      <div class="relative">
        <select
          v-model="selectedDistrict"
          @change="onDistrictChange"
          :disabled="!selectedProvince || loadingDistricts"
          :class="selectClass"
          required
        >
          <option value="">-- Chọn quận/huyện --</option>
          <option v-for="d in districts" :key="getDKey(d)" :value="d">{{ getDName(d) }}</option>
        </select>
        <div v-if="loadingDistricts" class="absolute right-3 top-1/2 -translate-y-1/2">
          <div class="w-3.5 h-3.5 border-2 border-neutral-300 border-t-black rounded-full animate-spin"></div>
        </div>
        <svg v-else class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <!-- Ward -->
    <div :class="wrapClass">
      <label :class="labelClass">Phường / Xã</label>
      <div class="relative">
        <select
          v-model="selectedWard"
          @change="onWardChange"
          :disabled="!selectedDistrict || loadingWards"
          :class="selectClass"
        >
          <option value="">-- Chọn phường/xã --</option>
          <option v-for="w in wards" :key="getWKey(w)" :value="w">{{ getWName(w) }}</option>
        </select>
        <div v-if="loadingWards" class="absolute right-3 top-1/2 -translate-y-1/2">
          <div class="w-3.5 h-3.5 border-2 border-neutral-300 border-t-black rounded-full animate-spin"></div>
        </div>
        <svg v-else class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { shippingService } from '@/services/client/shippingService'

const props = defineProps({
  initialProvince: { type: String, default: '' },
  initialDistrict: { type: String, default: '' },
  initialWard:     { type: String, default: '' },
  variant:         { type: String, default: 'profile' },
  useGhn:          { type: Boolean, default: false },
})

const emit = defineEmits(['change'])

const BASE_URL = import.meta.env.VITE_PROVINCE_API_URL || 'https://provinces.open-api.vn/api/v1'

const provinces        = ref([])
const districts        = ref([])
const wards            = ref([])
const selectedProvince = ref('')
const selectedDistrict = ref('')
const selectedWard     = ref('')
const loadingProvinces = ref(false)
const loadingDistricts = ref(false)
const loadingWards     = ref(false)

const labelClass = computed(() =>
  props.variant === 'checkout'
    ? 'block text-[10px] uppercase tracking-wider text-neutral-400 font-semibold mb-1'
    : 'text-[10px] font-bold uppercase tracking-widest text-neutral-500'
)

const wrapClass = computed(() =>
  props.variant === 'checkout' ? 'relative' : 'space-y-2'
)

const selectClass = computed(() =>
  props.variant === 'checkout'
    ? 'w-full appearance-none border-b border-neutral-200 py-2 pr-7 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed'
    : 'w-full appearance-none border border-neutral-200 px-4 py-3 pr-9 text-sm bg-white focus:border-neutral-950 focus:outline-none transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed'
)

const getPName = (p) => p ? (p.ProvinceName || p.name || '') : ''
const getDName = (d) => d ? (d.DistrictName || d.name || '') : ''
const getWName = (w) => w ? (w.WardName || w.name || '') : ''
const getPKey  = (p) => p.ProvinceID || p.code
const getDKey  = (d) => d.DistrictID || d.code
const getWKey  = (w) => w.WardCode || w.code

const fetchProvinces = async () => {
  loadingProvinces.value = true
  try {
    if (props.useGhn) {
      const res = await shippingService.getProvinces()
      provinces.value = res.data?.data || []
    } else {
      const res = await fetch(`${BASE_URL}/p/`)
      provinces.value = await res.json()
    }
  } catch (e) {
    console.error('ProvincePicker: fetchProvinces failed', e)
  } finally {
    loadingProvinces.value = false
  }
}

const fetchDistricts = async (pObj) => {
  if (!pObj) return
  loadingDistricts.value = true
  districts.value = []
  wards.value = []
  selectedDistrict.value = ''
  selectedWard.value = ''
  try {
    if (props.useGhn) {
      const res = await shippingService.getDistricts(pObj.ProvinceID)
      districts.value = res.data?.data || []
    } else {
      const res = await fetch(`${BASE_URL}/p/${pObj.code}?depth=2`)
      const data = await res.json()
      districts.value = data.districts || []
    }
  } catch (e) {
    console.error('ProvincePicker: fetchDistricts failed', e)
  } finally {
    loadingDistricts.value = false
  }
}

const fetchWards = async (dObj) => {
  if (!dObj) return
  loadingWards.value = true
  wards.value = []
  selectedWard.value = ''
  try {
    if (props.useGhn) {
      const res = await shippingService.getWards(dObj.DistrictID)
      wards.value = res.data?.data || []
    } else {
      const res = await fetch(`${BASE_URL}/d/${dObj.code}?depth=2`)
      const data = await res.json()
      wards.value = data.wards || []
    }
  } catch (e) {
    console.error('ProvincePicker: fetchWards failed', e)
  } finally {
    loadingWards.value = false
  }
}

const emitChange = () => {
  emit('change', {
    province:    getPName(selectedProvince.value),
    district:    getDName(selectedDistrict.value),
    ward:        getWName(selectedWard.value),
    district_id: selectedDistrict.value?.DistrictID || null,
    ward_code:   selectedWard.value?.WardCode || null,
  })
}

const onProvinceChange = () => {
  if (selectedProvince.value) {
    fetchDistricts(selectedProvince.value)
  } else {
    districts.value = []
    wards.value = []
    selectedDistrict.value = ''
    selectedWard.value = ''
  }
  emitChange()
}

const onDistrictChange = () => {
  if (selectedDistrict.value) {
    fetchWards(selectedDistrict.value)
  } else {
    wards.value = []
    selectedWard.value = ''
  }
  emitChange()
}

const onWardChange = () => emitChange()

const findByName = (list, name) => {
  if (!name) return null
  const n = name.toLowerCase().trim()
  return (
    list.find(item => getPName(item).toLowerCase() === n || getDName(item).toLowerCase() === n || getWName(item).toLowerCase() === n) ||
    list.find(item => {
      const itemStr = (getPName(item) || getDName(item) || getWName(item)).toLowerCase()
      return itemStr.includes(n) || n.includes(itemStr)
    }) ||
    null
  )
}

const prefill = async () => {
  if (!props.initialProvince || provinces.value.length === 0) return
  const matchP = findByName(provinces.value, props.initialProvince)
  if (!matchP) return
  selectedProvince.value = matchP
  if (!props.initialDistrict) { emitChange(); return }

  loadingDistricts.value = true
  try {
    if (props.useGhn) {
      const res = await shippingService.getDistricts(matchP.ProvinceID)
      districts.value = res.data?.data || []
    } else {
      const res = await fetch(`${BASE_URL}/p/${matchP.code}?depth=2`)
      const data = await res.json()
      districts.value = data.districts || []
    }
  } catch (e) {} finally { loadingDistricts.value = false }

  const matchD = findByName(districts.value, props.initialDistrict)
  if (!matchD) { emitChange(); return }
  selectedDistrict.value = matchD
  if (!props.initialWard) { emitChange(); return }

  loadingWards.value = true
  try {
    if (props.useGhn) {
      const res = await shippingService.getWards(matchD.DistrictID)
      wards.value = res.data?.data || []
    } else {
      const res = await fetch(`${BASE_URL}/d/${matchD.code}?depth=2`)
      const data = await res.json()
      wards.value = data.wards || []
    }
  } catch (e) {} finally { loadingWards.value = false }

  const matchW = findByName(wards.value, props.initialWard)
  if (matchW) selectedWard.value = matchW
  emitChange()
}

onMounted(async () => {
  await fetchProvinces()
  if (props.initialProvince) await prefill()
})

watch(
  () => [props.initialProvince, props.initialDistrict, props.initialWard],
  async ([newP]) => {
    if (newP && newP !== getPName(selectedProvince.value)) {
      selectedProvince.value = ''
      selectedDistrict.value = ''
      selectedWard.value = ''
      await prefill()
    }
  }
)
</script>