import { reactive } from 'vue'

/**
 * Validation rules theo pattern objectValidation.
 * Mỗi key là tên field, value là mảng các hàm validate.
 * Mỗi hàm nhận (value, formData?) và trả về string lỗi hoặc null.
 */
const categoryRules = {
  name: [
    (v) => (!v || !v.trim() ? 'Tên danh mục không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Tên danh mục không được vượt quá 255 ký tự.' : null),
  ],
  slug: [
    (v) => (!v || !v.trim() ? 'Slug không được để trống.' : null),
    (v) => (v && !/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(v.trim())
      ? 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang (-).'
      : null),
  ],
  description: [
    (v) => (v && v.length > 255 ? 'Mô tả không được vượt quá 255 ký tự.' : null),
  ],
}

function validateField(field, value, formData = {}) {
  const rules = categoryRules[field]
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
  for (const field of Object.keys(categoryRules)) {
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
export function useCategoryValidation() {
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
