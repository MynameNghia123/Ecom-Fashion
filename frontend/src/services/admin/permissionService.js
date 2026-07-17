import http from "@/services/shared/http";

const BASE = '/admin/permissions';

export const permissionService = {
    /**
     * Lấy tất cả permissions, nhóm theo module.
     * @returns {Promise}
     */
    getAll(){
        return http.get(BASE);
    }
}
