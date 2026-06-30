import { supplierService } from "@/services/admin/supplierService";
import { defineStore } from "pinia";
import { ref } from "vue";
export const useSupplierStore = defineStore('supplier', () => {
    const suppliers = ref([]);
    const meta = ref({
        current_page: 1,
        per_page: 4,
        total: 0,
        last_page: 1,
    });
    const loading = ref(false);
    const error = ref(null);
    
    async function initialFetch(params = {}) {
        if (suppliers.value.length > 0) return;
        await fetchSuppliers(params);
    }
    // Action
    async function fetchSuppliers(params = {}) {
        loading.value = true;
        error.value = null;
        try {
            const res = await supplierService.getAll({
                per_page: meta.value.per_page,
                ...params
            });
            suppliers.value = res.data.data;
            meta.value = res.data.meta;
        } catch (e) {
            error.value = e.response?.data?.message || e.message;
        } finally {
            loading.value = false; 
        }
    }

    async function createSupplier(data) {
        const res = await supplierService.create(data);
        await fetchSuppliers({page: 1});
        return res.data;
    }

    async function updateSupplier(id, data) {
        const res = await supplierService.update(id, data)
        await fetchSuppliers({ page: meta.value.current_page})
        return res.data;
    }

    async function deleteSupplier(id) {
        const res = await supplierService.delete(id);
        const newPage = suppliers.value.length === 1 && meta.value.current_page > 1
        ? meta.value.current_page - 1
        : meta.value.current_page
        await fetchSuppliers({ page: newPage })
        return res.data
    }

    return {
        suppliers,
        meta,
        loading,
        error,

        initialFetch,
        fetchSuppliers,
        createSupplier,
        updateSupplier,
        deleteSupplier,
    }
})