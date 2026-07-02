import { reactive } from 'vue'

/**
 * Validation rules theo pattern objectValidation.
 * Mỗi key là tên field, value là mảng các hàm validate.
 * Mỗi hàm nhận (value, formData?) và trả về string lỗi hoặc null.
 */
const attributeRules = {
  name: [
    (v) => (!v || !v.trim() ? 'Tên thuộc tính không được để trống.' : null),
    (v) => (v && v.trim().length > 100 ? 'Tên thuộc tính không được vượt quá 100 ký tự.' : null),
  ],
}

function validateField(field, value, formData = {}) {
  const rules = attributeRules[field]
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
  for (const field of Object.keys(attributeRules)) {
    const msg = validateField(field, formData[field], formData)
    if (msg) {
      errors[field] = msg
      isValid = false
    }
  }
  return { errors, isValid }
}

/**
 * Composable useAttributeValidation
 * Dùng cho form Add và Edit của AttributeProduct.
 *
 * @example
 * const { errors, validate, clearErrors, applyBackendErrors } = useAttributeValidation()
 *
 * // Trước khi submit:
 * if (!validate(addForm)) return
 *
 * // Khi catch lỗi từ backend:
 * const serverMsg = applyBackendErrors(e)
 */
export function useAttributeValidation() {
  const errors = reactive({ name: '' })

  const clearErrors = () => {
    errors.name = ''
  }

  /**
   * Validate client-side.
   * @param {{ name: string }} formData
   * @returns {boolean} true nếu hợp lệ
   */
  const validate = (formData) => {
    clearErrors()
    const { errors: validationErrors, isValid } = validateAll(formData)
    if (!isValid) Object.assign(errors, validationErrors)
    return isValid
  }

  /**
   * Áp dụng lỗi trả về từ backend (Laravel 422).
   * @param {Error} e - axios error
   * @returns {string} server error message nếu không map được vào field nào
   */
  const applyBackendErrors = (e) => {
    const backendErrors = e.response?.data?.errors
    if (backendErrors?.name) {
      errors.name = backendErrors.name[0]
      return ''
    }
    return e.response?.data?.message || 'Có lỗi xảy ra.'
  }

  return { errors, validate, clearErrors, applyBackendErrors }
}
