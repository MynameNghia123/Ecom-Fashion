import { reactive } from 'vue'

/**
 * Validation rules theo pattern objectValidation.
 * Mỗi key là tên field, value là mảng hàm rule.
 * Mỗi hàm nhận (value) và trả về string lỗi hoặc null.
 */
const supplierRules = {
  name: [
    (v) => (!v || !v.trim() ? 'Tên nhà phân phối không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Tên không được vượt quá 255 ký tự.' : null),
  ],
  phone: [
    (v) => (!v || !v.trim() ? 'Số điện thoại không được để trống.' : null),
    (v) => (v && !/^[0-9+\-\s()]{7,20}$/.test(v.trim()) ? 'Số điện thoại không hợp lệ.' : null),
  ],
  email: [
    (v) => (!v || !v.trim() ? 'Email không được để trống.' : null),
    (v) => (v && v.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())
      ? 'Email không hợp lệ.'
      : null),
  ],
  address: [
    (v) => (!v || !v.trim() ? 'Địa chỉ không được để trống.' : null),
    (v) => (v && v.length > 500 ? 'Địa chỉ không được vượt quá 500 ký tự.' : null),
  ],
}

function validateField(field, value, formData = {}) {
  const rules = supplierRules[field]
  if (!rules) return ''
  for (const rule of rules) {
    const msg = rule(value, formData)
    if (msg) return msg
  }
  return ''
}

function validateAll(formData) {
  const errors = {}
  let isValid = true
  for (const field of Object.keys(supplierRules)) {
    const msg = validateField(field, formData[field], formData)
    if (msg) {
      errors[field] = [msg]   // giữ format mảng để tương thích với fieldError()
      isValid = false
    }
  }
  return { errors, isValid }
}

/**
 * Composable useCategoryValidation
 * Dùng cho form Add / Edit của Category.vue.
 *
 * @example
 * const { formErrors, validate, clearErrors, applyBackendErrors } = useCategoryValidation()
 *
 * // Trước khi submit:
 * if (!validate(form)) return
 *
 * // fieldError helper (đã dùng trong template):
 * const fieldError = (field) => formErrors[field]?.[0] ?? ''
 *
 * // Khi catch lỗi backend:
 * serverError.value = applyBackendErrors(e)
 */
export function useSupplierValidation() {
  // Dùng reactive object rỗng — key được thêm/xoá động như code gốc
  const formErrors = reactive({})

  const clearErrors = () => {
    Object.keys(formErrors).forEach((k) => delete formErrors[k])
  }

  /**
   * Helper dùng trong template: lấy message đầu tiên của field.
   * @param {string} field
   * @returns {string}
   */
  const fieldError = (field) => formErrors[field]?.[0] ?? ''

  /**
   * Validate client-side.
   * @param {object} formData - { name, slug, description, parent_id, ... }
   * @returns {boolean} true nếu hợp lệ
   */
  const validate = (formData) => {
    clearErrors()
    const { errors: validationErrors, isValid } = validateAll(formData)
    if (!isValid) Object.assign(formErrors, validationErrors)
    return isValid
  }

  /**
   * Áp dụng lỗi trả về từ backend (Laravel 422).
   * @param {Error} e - axios error (đã được store throw lại)
   * @returns {string} server error message nếu không map được vào field nào
   */
  const applyBackendErrors = (e) => {
    if (e.errors) {
      Object.assign(formErrors, e.errors)
      return ''
    }
    return e.message || 'Có lỗi xảy ra.'
  }

  return { formErrors, validate, clearErrors, fieldError, applyBackendErrors }
}
