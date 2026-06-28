import { ref } from 'vue'

/**
 * Validation rules theo pattern objectValidation.
 * Mỗi key là tên field, value là mảng các hàm validate.
 * Mỗi hàm nhận (value, formData?) và trả về string lỗi hoặc null.
 *
 * Lưu ý: `variants` là mảng nên rule nhận toàn bộ mảng và kiểm tra theo logic riêng.
 */
const productRules = {
  name: [
    (v) => (!v || !v.trim() ? 'Tên sản phẩm không được để trống.' : null),
  ],
  slug: [
    (v) => (!v || !v.trim() ? 'Slug không được để trống.' : null),
  ],
  category_id: [
    (v) => (!v ? 'Vui lòng chọn danh mục.' : null),
  ],
  variants: [
    (v) => (!v || v.length === 0 ? 'Sản phẩm phải có ít nhất 1 biến thể.' : null),
    (v) => {
      if (!v || v.length === 0) return null
      const hasMissing = v.some(
        (variant) => !variant.sku?.trim() || variant.price === null || variant.price === undefined
      )
      return hasMissing ? 'Mỗi biến thể phải có SKU và Giá bán.' : null
    },
  ],
}

/**
 * Chạy validate toàn bộ form.
 * Dừng lại ở lỗi đầu tiên của từng field (fail-fast per field).
 * @param {object} formData
 * @returns {{ errors: string[], isValid: boolean }}
 */
function validateAll(formData) {
  const errors = []
  for (const field of Object.keys(productRules)) {
    const rules = productRules[field]
    for (const rule of rules) {
      const msg = rule(formData[field], formData)
      if (msg) {
        errors.push(msg)
        break // chỉ lấy lỗi đầu tiên của field đó
      }
    }
  }
  return { errors, isValid: errors.length === 0 }
}

/**
 * Composable useProductValidation
 * Dùng cho ProductFormModal — hiển thị lỗi dưới dạng danh sách (apiErrors[]).
 *
 * @example
 * const { apiErrors, validate, clearErrors, applyBackendErrors } = useProductValidation()
 *
 * // Trước khi gọi API:
 * if (!validate(formProduct)) return
 *
 * // Khi catch lỗi backend:
 * applyBackendErrors(error)
 */
export function useProductValidation() {
  // Dùng ref([]) vì template hiện tại dùng v-for trên apiErrors
  const apiErrors = ref([])

  const clearErrors = () => {
    apiErrors.value = []
  }

  /**
   * Validate client-side.
   * @param {object} formData - reactive formProduct
   * @returns {boolean} true nếu hợp lệ
   */
  const validate = (formData) => {
    clearErrors()
    const { errors, isValid } = validateAll(formData)
    if (!isValid) apiErrors.value = errors
    return isValid
  }

  /**
   * Áp dụng lỗi trả về từ backend (Laravel 422).
   * Backend trả về: { errors: { name: [...], slug: [...], ... } }
   * @param {Error} error - axios error
   */
  const applyBackendErrors = (error) => {
    const responseErrors = error.response?.data?.errors
    if (responseErrors) {
      apiErrors.value = Object.values(responseErrors).flat()
    } else {
      apiErrors.value = [
        error.response?.data?.message || error.message || 'Có lỗi xảy ra khi lưu sản phẩm.',
      ]
    }
  }

  return { apiErrors, validate, clearErrors, applyBackendErrors }
}
