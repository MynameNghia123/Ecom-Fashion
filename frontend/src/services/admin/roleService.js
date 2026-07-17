import http from "@/services/shared/http";

const BASE = '/admin/roles';

export const roleService = {
    /**
     * @param {Object} - {search, page, per_page}
     */
    getAll(params = {}){
        return http.get(BASE, {params});
    },

    getAllNoPaginate() {
        return http.get(`${BASE}/all`);
    },

    /**
     * @param {number} id 
     * @returns 
     */
    getById(id){
        return http.get(`${BASE}/${id}`);
    },

    /**
     * @param {{ Object }} data 
     * @returns 
     */
    create(data){
        return http.post(BASE, data);
    },

    /**
     * @param {number} id 
     * @param {object} data 
     * @returns 
     */
    update(id, data){
        return http.put(`${BASE}/${id}`, data)
    },
    
    /**
     * Xóa thuộc tính
     * @param {number} id
     */
    delete(id) {
        return http.delete(`${BASE}/${id}`)
    },
}
