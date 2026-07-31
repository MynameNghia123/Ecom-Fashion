import { defineStore } from "pinia";
import { goodsReceiptService } from "@/services/admin/goodsReceiptService";
import { ref } from "vue";

export const useGoodsReceiptStore = defineStore('goods-receipt', () => {
  // ---------------- STATE ----------------------------
  const goodsReceipts = ref([]);
  const meta = ref({
    current_page: 1,
    per_page: 4,
    total: 0,
    last_page:1,
  })
  const stats = ref({
    total: 0,
    total_import_value: 0,
    pending: 0,
  });
  const errors = ref(null);
  const loading = ref(false);

  // ---------------- ACTIONS ------------------------------

  async function fetchGoodsReceipt(params = {}) {
    loading.value = true;
    errors.value = null;
    try {
      const res = await goodsReceiptService.getAll({
        per_page: meta.value.per_page,
        ...params
      });
      goodsReceipts.value = res.data.data;
      meta.value = res.data.meta;
      if (res.data.stats) {
        stats.value = res.data.stats;
      }
    } catch (e) {
      errors.value = e.message; 
    } finally {
      loading.value = false;
    }
  }

  async function initialFetch(params = {}) {
    if (goodsReceipts.value.length > 0) return;
    return fetchGoodsReceipt(params);
  }

  async function createGoodsReceipt(data) {
    const res = await goodsReceiptService.create(data)
    await fetchGoodsReceipt({ page: 1 })
    return res.data
  }

  async function updateGoodsReceipt(data, id) {
    const res = await goodsReceiptService.update(id, data)
    await fetchGoodsReceipt({ page: meta.value.current_page })
    return res.data
  }

  async function deleteGoodsReceipt(id) {
    const res = await goodsReceiptService.delete(id)
    const newPage = goodsReceipts.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchGoodsReceipt({ page: newPage })
    return res.data;
  }
  
    return {
    // State
    goodsReceipts,
    meta,
    stats,
    loading,
    errors,
    // Actions
    fetchGoodsReceipt,
    initialFetch,
    createGoodsReceipt,
    updateGoodsReceipt,
    deleteGoodsReceipt,
  }
})