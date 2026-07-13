import { defineStore } from "pinia";
import { ref } from "vue";
import { productVariantService } from "@/services/admin/productVariantService";

export const useProductVariantStore = defineStore('product-variant', () => {
    const searchResults = ref([]);
    const loading = ref(false);
    const error = ref(null);

    async function searchVariants(search) {
        if (!search) {
            searchResults.value = [];
            return;
        }
        loading.value = true;
        error.value = null;
        try {
            const res = await productVariantService.search(search);
            searchResults.value = res.data.data;
        } catch (e) {
            error.value = e.response?.data?.message || e.message;
            searchResults.value = [];
        } finally {
            loading.value = false;
        }
    }

    function clearSearch() {
        searchResults.value = [];
    }

    return {
        searchResults,
        loading,
        error,
        
        searchVariants,
        clearSearch
    }
});
