import http from "../shared/http";

const BASE = '/admin/suppliers'

export const supplierService = {
    getAll(params = {}){
        return http.get(BASE, {params});
    },
    getById(id){
        return http.get(`${BASE}/${id}`);
    },
    create(data){
        return http.post(BASE, data);
    },
    update(id,data){
        return http.put(`${BASE}/${id}`, data);
    },
    delete(id){
        return http.delete(`${BASE}/${id}`);
    }
}