import http from "@/services/shared/http";

const BASE = '/admin/product-variants';

export const productVariantService = {
    search(search) {
        return http.get(`${BASE}/search`, { params: { search } });
    }
};
