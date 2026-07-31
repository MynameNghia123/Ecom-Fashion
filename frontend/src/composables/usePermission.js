import { useAuthStore } from '@/stores/admin/authStore'

/**
 * Composable tiện ích để kiểm tra quyền hạn trong template và script.
 *
 * Cách dùng trong <script setup>:
 *
 *   const { can, canAny, canAll } = usePermission()
 *
 *   // Trong template:
 *   <button v-if="can('products', 'create')">Thêm sản phẩm</button>
 *
 *   // Trong script:
 *   if (can('orders', 'delete')) { ... }
 */
export function usePermission() {
  const authStore = useAuthStore()

  return {
    /**
     * Kiểm tra một quyền cụ thể.
     * @param {string} module - VD: 'products'
     * @param {string} action - VD: 'create'
     */
    can: (module, action) => authStore.can(module, action),

    /**
     * Kiểm tra có ít nhất một quyền trong danh sách.
     * @param  {...string} perms - VD: 'products:create', 'orders:read'
     */
    canAny: (...perms) => authStore.canAny(...perms),

    /**
     * Kiểm tra có tất cả quyền trong danh sách.
     * @param  {...string} perms
     */
    canAll: (...perms) => authStore.canAll(...perms),
  }
}
