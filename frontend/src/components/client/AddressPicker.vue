<template>
  <div class="relative w-full text-left font-text" v-click-outside="closeDropdown">
    <div class="relative">
      <input
        ref="inputRef"
        type="text"
        v-model="inputValue"
        @input="onInput"
        @focus="onFocus"
        :placeholder="placeholder || 'Nhập địa chỉ giao hàng...'"
        :class="inputClass || 'w-full border-b border-neutral-200 py-2 outline-none focus:border-black transition-colors bg-transparent text-sm text-neutral-800'"
        :required="required"
        autocomplete="off"
      />
      <div v-if="loading" class="absolute right-2 top-1/2 -translate-y-1/2">
        <div class="w-4 h-4 border-2 border-neutral-200 border-t-black rounded-full animate-spin"></div>
      </div>
      <div v-else class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
          <circle cx="12" cy="9" r="2.5"/>
        </svg>
      </div>
    </div>

    <!-- Suggestions Dropdown (Nominatim Fallback) -->
    <div 
      v-if="showDropdown && suggestions.length > 0"
      class="absolute z-[999] left-0 right-0 mt-1 bg-white border border-neutral-200 shadow-lg max-h-60 overflow-y-auto"
    >
      <ul>
        <li
          v-for="(item, index) in suggestions"
          :key="index"
          @click="selectSuggestion(item)"
          class="px-4 py-3 hover:bg-neutral-50 cursor-pointer border-b border-neutral-100 last:border-none text-xs text-neutral-700 leading-normal"
        >
          <div class="font-bold text-neutral-900 mb-0.5">
            {{ item.title }}
          </div>
          <div class="text-neutral-400">
            {{ item.subtitle }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  inputClass: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'address-selected'])

const inputRef = ref(null)
const inputValue = ref(props.modelValue || '')
const suggestions = ref([])
const showDropdown = ref(false)
const loading = ref(false)

let googleAutocomplete = null
let searchTimeout = null
let usingGoogleMaps = false

watch(() => props.modelValue, (newVal) => {
  if (newVal !== inputValue.value) {
    inputValue.value = newVal || ''
  }
})

const onFocus = () => {
  if (!usingGoogleMaps && suggestions.value.length > 0) {
    showDropdown.value = true
  }
}

const closeDropdown = () => {
  showDropdown.value = false
}

// Click outside directive helper
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event)
      }
    }
    document.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent)
  }
}

// Nominatim Search (OSM Open API for addresses - no key required)
const fetchNominatimSuggestions = (query) => {
  if (!query || query.length < 3) {
    suggestions.value = []
    showDropdown.value = false
    return
  }

  loading.value = true
  if (searchTimeout) clearTimeout(searchTimeout)

  searchTimeout = setTimeout(async () => {
    try {
      const response = await fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&countrycodes=vn&limit=6`
      )
      const data = await response.json()
      
      suggestions.value = data.map((item) => {
        const addr = item.address || {}
        
        // Extract relevant parts
        const province = addr.province || addr.city || addr.state || ''
        const district = addr.district || addr.suburb || addr.city_district || addr.county || ''
        const ward = addr.suburb || addr.quarter || addr.subdistrict || addr.town || addr.village || ''
        
        // Display texts
        const namePart = item.display_name.split(',')[0]
        const remainingParts = item.display_name.split(',').slice(1).join(',').trim()

        return {
          title: namePart,
          subtitle: remainingParts || item.display_name,
          full_address: item.display_name,
          raw: item,
          parsed: {
            detail_address: namePart,
            ward,
            district,
            province
          }
        }
      })
      showDropdown.value = suggestions.value.length > 0
    } catch (e) {
      console.error('Nominatim suggestions error:', e)
    } finally {
      loading.value = false
    }
  }, 500)
}

const onInput = () => {
  emit('update:modelValue', inputValue.value)
  if (!usingGoogleMaps) {
    fetchNominatimSuggestions(inputValue.value)
  }
}

const selectSuggestion = (item) => {
  inputValue.value = item.full_address
  emit('update:modelValue', item.full_address)
  
  // Format details for Vietnamese standard address hierarchy
  emit('address-selected', {
    full_address: item.full_address,
    detail_address: item.parsed.detail_address,
    ward: item.parsed.ward,
    district: item.parsed.district,
    province: item.parsed.province
  })
  
  showDropdown.value = false
}

// Google Maps Integration helper
const loadGoogleScript = () => {
  const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY
  if (!apiKey) return Promise.reject()

  if (window.google && window.google.maps && window.google.maps.places) {
    return Promise.resolve(window.google)
  }

  return new Promise((resolve, reject) => {
    const script = document.createElement('script')
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&language=vi`
    script.async = true
    script.defer = true
    script.onload = () => resolve(window.google)
    script.onerror = () => reject()
    document.head.appendChild(script)
  })
}

const parseGooglePlace = (place) => {
  if (!place || !place.address_components) return

  let streetNumber = ''
  let route = ''
  let ward = ''
  let district = ''
  let province = ''

  place.address_components.forEach((component) => {
    const types = component.types
    if (types.includes('street_number')) {
      streetNumber = component.long_name
    }
    if (types.includes('route')) {
      route = component.long_name
    }
    if (types.includes('sublocality_level_1') || types.includes('sublocality') || types.includes('ward')) {
      ward = component.long_name
    }
    if (types.includes('administrative_area_level_2') || types.includes('locality') || types.includes('district')) {
      district = component.long_name
    }
    if (types.includes('administrative_area_level_1') || types.includes('province')) {
      province = component.long_name
    }
  })

  const detail = [streetNumber, route].filter(Boolean).join(' ') || place.name || ''
  const full = place.formatted_address || inputValue.value

  inputValue.value = full
  emit('update:modelValue', full)
  emit('address-selected', {
    full_address: full,
    detail_address: detail,
    ward,
    district,
    province
  })
}

onMounted(() => {
  loadGoogleScript()
    .then((google) => {
      if (!inputRef.value) return
      usingGoogleMaps = true
      googleAutocomplete = new google.maps.places.Autocomplete(inputRef.value, {
        componentRestrictions: { country: 'vn' },
        fields: ['address_components', 'formatted_address', 'name', 'geometry']
      })
      googleAutocomplete.addListener('place_changed', () => {
        const place = googleAutocomplete.getPlace()
        parseGooglePlace(place)
      })
    })
    .catch(() => {
      // API Key missing or blocked, gracefully fallback to Nominatim
      usingGoogleMaps = false
    })
})
</script>
