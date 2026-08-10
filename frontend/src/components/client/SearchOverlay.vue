<template>
  <!-- Overlay backdrop -->
  <Teleport to="body">
    <Transition name="search-fade">
      <div
        v-if="isOpen"
        class="search-overlay"
        @click.self="close"
      >
        <div class="search-container" @click.stop>
          <!-- Close button -->
          <button class="search-close" @click="close" aria-label="Đóng tìm kiếm">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>

          <!-- Search Input -->
          <div class="search-input-wrap">
            <svg class="search-icon-input" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/>
              <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input
              ref="inputRef"
              v-model="query"
              type="text"
              class="search-input"
              placeholder="Tìm kiếm sản phẩm, danh mục, thương hiệu..."
              @keydown.esc="close"
              @keydown.enter="goToSearch"
              autocomplete="off"
            />
            <button v-if="query" class="search-clear" @click="query = ''" aria-label="Xóa">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Results / Categories Body -->
          <div class="search-body">
            <!-- Loading State -->
            <div v-if="loading" class="search-state">
              <div class="search-spinner"></div>
              <span>Đang tìm kiếm...</span>
            </div>

            <!-- Results list -->
            <template v-else-if="results.length > 0">
              <p class="search-result-count">Tìm thấy <strong>{{ total }}</strong> sản phẩm</p>
              <div class="search-results">
                <RouterLink
                  v-for="product in results"
                  :key="product.id"
                  :to="`/products/${product.id}`"
                  class="search-result-item"
                  @click="close"
                >
                  <div class="result-img">
                    <img
                      :src="productThumbnail(product)"
                      :alt="product.name"
                    />
                  </div>
                  <div class="result-info">
                    <p class="result-brand">{{ product.brand || product.category?.name }}</p>
                    <h4 class="result-name">{{ product.name }}</h4>
                    <p class="result-price">{{ formatPrice(product) }}</p>
                  </div>
                  <svg class="result-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                  </svg>
                </RouterLink>
              </div>
              <button v-if="results.length < total" class="search-view-all" @click="goToSearch">
                Xem tất cả {{ total }} kết quả →
              </button>
            </template>

            <!-- No result -->
            <div v-else-if="query.trim() && !loading" class="search-state">
              <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="opacity-40">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
              <p>Không tìm thấy sản phẩm nào cho <strong>"{{ query }}"</strong></p>
            </div>

            <!-- Category Suggestions khi ô tìm kiếm trống -->
            <div v-else-if="!query.trim()" class="search-suggestions">
              <p class="suggestion-label">Danh mục nổi bật</p>
              <div class="suggestion-tags">
                <button
                  v-for="cat in categories"
                  :key="cat.id"
                  class="suggestion-tag"
                  @click="selectCategory(cat)"
                >
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="opacity-60">
                    <path d="M4 6h16M4 12h16M4 18h7"/>
                  </svg>
                  <span>{{ cat.name }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { productService } from '@/services/client/productService'

const props = defineProps({
  isOpen: { type: Boolean, default: false }
})
const emit = defineEmits(['close'])

const router = useRouter()
const query = ref('')
const results = ref([])
const total = ref(0)
const loading = ref(false)
const inputRef = ref(null)

const categories = ref([])
const searchCache = new Map()

let debounceTimer = null

const close = () => emit('close')

const goToSearch = () => {
  if (!query.value.trim()) return
  router.push({ path: '/category', query: { search: query.value.trim() } })
  close()
}

const selectCategory = (cat) => {
  router.push({ path: `/category/${cat.slug || cat.id}` })
  close()
}

const loadCategories = async () => {
  try {
    const res = await productService.getCategories()
    if (res.data && res.data.success) {
      categories.value = res.data.data
    }
  } catch (err) {
    console.error('Lỗi tải danh mục:', err)
  }
}

const productThumbnail = (p) => {
  if (p.thumbnail) return p.thumbnail.startsWith('http') ? p.thumbnail : `http://localhost:8000/storage/${p.thumbnail}`
  if (p.productImages?.length) {
    const img = p.productImages[0].image_url || p.productImages[0].url
    return img?.startsWith('http') ? img : `http://localhost:8000/storage/${img}`
  }
  return 'https://placehold.co/80x80/f5f5f5/999?text=No+Image'
}

const formatPrice = (product) => {
  const variants = product.productVariants || []
  if (!variants.length) return ''
  const prices = variants.map(v => v.sale_price || v.price).filter(Boolean)
  if (!prices.length) return ''
  const min = Math.min(...prices)
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(min)
}

const doSearch = async (q) => {
  if (!q) {
    results.value = []
    total.value = 0
    loading.value = false
    return
  }

  if (searchCache.has(q)) {
    const cached = searchCache.get(q)
    results.value = cached.results
    total.value = cached.total
    loading.value = false
    return
  }

  loading.value = true
  try {
    const res = await productService.getProducts({ search: q, per_page: 8 })
    const resData = res.data?.data || []
    const resTotal = res.data?.meta?.total || resData.length

    results.value = resData
    total.value = resTotal

    searchCache.set(q, { results: resData, total: resTotal })
  } catch {
    results.value = []
    total.value = 0
  } finally {
    loading.value = false
  }
}

watch(query, (val) => {
  clearTimeout(debounceTimer)
  const trimmed = val.trim()
  if (!trimmed) {
    results.value = []
    total.value = 0
    loading.value = false
    return
  }
  
  if (searchCache.has(trimmed)) {
    doSearch(trimmed)
  } else {
    debounceTimer = setTimeout(() => doSearch(trimmed), 100)
  }
})

watch(() => props.isOpen, async (val) => {
  if (val) {
    query.value = ''
    results.value = []
    loading.value = false
    if (categories.value.length === 0) {
      loadCategories()
    }
    await nextTick()
    inputRef.value?.focus()
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})

onMounted(() => {
  loadCategories()
})
</script>

<style scoped>
/* ── Overlay Trong Suốt ── */
.search-overlay {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 70px;
}

/* ── Container Kính Mờ Trong Suốt Khớp Theme Luxury ── */
.search-container {
  position: relative;
  width: 100%;
  max-width: 740px;
  margin: 0 16px;
  background: rgba(255, 255, 255, 0.35);
  backdrop-filter: blur(40px) saturate(190%);
  -webkit-backdrop-filter: blur(40px) saturate(190%);
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 24px;
  box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.4) inset;
  overflow: hidden;
  max-height: calc(100vh - 110px);
  display: flex;
  flex-direction: column;
}

/* ── Close button ── */
.search-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.4);
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #111;
  transition: all 0.2s;
  z-index: 2;
}
.search-close:hover { background: rgba(0, 0, 0, 0.16); transform: rotate(90deg); }

/* ── Input ── */
.search-input-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 24px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}
.search-icon-input { color: #222; flex-shrink: 0; }
.search-input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 18px;
  font-weight: 600;
  color: #000;
  background: transparent;
  font-family: inherit;
}
.search-input::placeholder { color: #555; font-weight: 400; }
.search-clear {
  background: rgba(0, 0, 0, 0.08);
  border: none;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #222;
  flex-shrink: 0;
  transition: background 0.2s;
}
.search-clear:hover { background: rgba(0, 0, 0, 0.16); }

/* ── Body ── */
.search-body {
  overflow-y: auto;
  padding: 16px 24px 24px;
  flex: 1;
}

/* ── State ── */
.search-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 40px 0;
  color: #222;
  font-size: 14px;
  text-align: center;
}
.search-state strong { color: #000; }
.search-spinner {
  width: 32px; height: 32px;
  border: 3px solid rgba(0, 0, 0, 0.15);
  border-top-color: #000;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Count ── */
.search-result-count {
  font-size: 12px;
  color: #444;
  margin: 0 0 12px;
}
.search-result-count strong { color: #000; }

/* ── Results List ── */
.search-results { display: flex; flex-direction: column; gap: 6px; }

.search-result-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 10px 14px;
  border-radius: 16px;
  text-decoration: none;
  color: inherit;
  transition: all 0.2s;
  cursor: pointer;
  border: 1px solid transparent;
  background: rgba(255, 255, 255, 0.2);
}
.search-result-item:hover {
  background: rgba(255, 255, 255, 0.75);
  border-color: rgba(255, 255, 255, 0.9);
  box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}

.result-img {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.4);
  flex-shrink: 0;
}
.result-img img { width: 100%; height: 100%; object-fit: cover; }

.result-info { flex: 1; min-width: 0; }
.result-brand { font-size: 11px; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 3px; font-weight: 600; }
.result-name {
  font-size: 14px;
  font-weight: 700;
  color: #000;
  margin: 0 0 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.result-price { font-size: 13px; color: #000; font-weight: 700; margin: 0; }

.result-arrow { color: #444; flex-shrink: 0; transition: color 0.15s, transform 0.15s; }
.search-result-item:hover .result-arrow { color: #000; transform: translateX(4px); }

/* ── View all ── */
.search-view-all {
  width: 100%;
  margin-top: 14px;
  padding: 12px;
  background: #000;
  color: #fff;
  border: none;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.search-view-all:hover { background: #222; }

/* ── Category Suggestions ── */
.search-suggestions { padding: 8px 0; }
.suggestion-label { font-size: 12px; color: #333; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px; }
.suggestion-tags { display: flex; flex-wrap: wrap; gap: 10px; }
.suggestion-tag {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 18px;
  background: rgba(255, 255, 255, 0.45);
  border: 1px solid rgba(255, 255, 255, 0.6);
  border-radius: 999px;
  font-size: 13px;
  font-weight: 600;
  color: #111;
  cursor: pointer;
  transition: all 0.2s;
}
.suggestion-tag:hover {
  background: #000;
  color: #fff;
  border-color: #000;
  box-shadow: 0 4px 14px rgba(0,0,0,0.15);
}

/* ── Transition ── */
.search-fade-enter-active,
.search-fade-leave-active {
  transition: opacity 0.25s ease;
}
.search-fade-enter-active .search-container,
.search-fade-leave-active .search-container {
  transition: opacity 0.25s ease, transform 0.25s cubic-bezier(0.16,1,0.3,1);
}
.search-fade-enter-from,
.search-fade-leave-to {
  opacity: 0;
}
.search-fade-enter-from .search-container {
  opacity: 0;
  transform: translateY(-16px) scale(0.97);
}
.search-fade-leave-to .search-container {
  opacity: 0;
  transform: translateY(-16px) scale(0.97);
}
</style>
