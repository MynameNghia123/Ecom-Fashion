import { defineStore } from "pinia";
import { ref } from "vue";
import { productService } from "@/services/admin/productService";

export const useProductStore = defineStore('product', () => {
    // ─── State ───────────────────────────────────────────────────────────────
    const products = ref([])
    const meta = ref({
        current_page: 1,
        per_page: 4, // Bạn đang để 4 sản phẩm/trang
        total: 0,
        last_page: 1,
    })
    const loading = ref(false)
    const error = ref(null) // SỬA 1: Đổi tên để tránh trùng lặp

    // ─── Actions ─────────────────────────────────────────────────────────────
    async function fetchProducts(params = {}) {
        loading.value = true;
        error.value = null;
        try {
            const res = await productService.getAll({
                per_page: meta.value.per_page,
                ...params,
            })

            products.value = res.data.data;
            meta.value = res.data.meta;
        } catch (err) { // SỬA 2: Đổi tham số thành err
            // Bắt lỗi an toàn hơn
            error.value = err.response?.data?.message || err.message || "Có lỗi xảy ra";
        } finally {
            loading.value = false;
        }
    }

    async function createProduct(data) {
        const res = await productService.create(data);
        // Tự động load lại trang hiện tại sau khi thêm thành công
        await fetchProducts({ page: meta.value.current_page })
        return res.data;
    }

    async function updateProduct(id, data) {
        const res = await productService.update(id, data);
        await fetchProducts({ page: meta.value.current_page })
        return res.data;
    }

    async function deleteProduct(id) {
        const res = await productService.delete(id);
        
        // SỬA 3: Đổi categories thành products
        const newPage = products.value.length === 1 && meta.value.current_page > 1
            ? meta.value.current_page - 1
            : meta.value.current_page;
            
        await fetchProducts({ page: newPage })
        return res.data;
    }

    /**
     * Fetch lần đầu — chỉ gọi API nếu chưa có data.
     * Dùng trong onMounted để tránh re-fetch khi tab qua lại.
     */
    async function initialFetch(params = {}) {
        if (products.value.length > 0) return
        return fetchProducts(params)
    }

    async function searchVariantBySku(sku) {
        try {
            const res = await productService.searchVariantBySku(sku);
            return res.data.data; // trả về mảng variants
        } catch (err) {
            console.error(err);
            return [];
        }
    }

    // SỬA 4: Trả về đầy đủ các state và action
    return {
        products,
        meta,
        loading,
        error, 

        fetchProducts,
        initialFetch,
        createProduct,
        updateProduct,
        deleteProduct,
        searchVariantBySku
    }
})