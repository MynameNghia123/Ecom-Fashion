import { reactive } from 'vue'

/**
 * Validation rules cho Role form.
 */
const rules = {
  name: [
    (v) => (!v || !v.trim() ? 'Tên vai trò không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Tên vai trò không được vượt quá 255 ký tự.' : null),
  ],
  description: [
    (v) => (v && v.trim().length > 500 ? 'Mô tả không được vượt quá 500 ký tự.' : null),
  ],
}

function validateField(field, value, rules) {
  if (!rules) return ''
  for (const rule of rules) {
    const msg = rule(value)
    if (msg) return msg
  }
  return ''
}

function validateAll(formData) {
  const errors = {}
  let isValid = true

  for (const field of Object.keys(rules)) {
    const msg = validateField(field, formData[field], rules[field])
    if (msg) {
      errors[field] = [msg]
      isValid = false
    }
  }

  return { errors, isValid }
}

export function useRoleValidation() {
  const formErrors = reactive({})

  const clearErrors = () => {
    Object.keys(formErrors).forEach((k) => delete formErrors[k])
  }

  const fieldError = (field) => formErrors[field]?.[0] ?? ''

  const validate = (formData) => {
    clearErrors()
    const { errors, isValid } = validateAll(formData)
    if (!isValid) Object.assign(formErrors, errors)
    return isValid
  }

  const applyBackendErrors = (e) => {
    if (e.errors) {
      Object.assign(formErrors, e.errors)
      return ''
    }
    return e.message || 'Có lỗi xảy ra.'
  }

  return { formErrors, validate, clearErrors, fieldError, applyBackendErrors }
}
