import { reactive } from 'vue'

/**
 * Validation rules cho Staff form.
 * password: bắt buộc khi 'add', optional khi 'edit'
 */
const baseRules = {
  full_name: [
    (v) => (!v || !v.trim() ? 'Họ và tên không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Họ và tên không được vượt quá 255 ký tự.' : null),
  ],
  email: [
    (v) => (!v || !v.trim() ? 'Email không được để trống.' : null),
    (v) =>
      v && v.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())
        ? 'Email không hợp lệ (vd: abc@gmail.com).'
        : null,
    (v) => (v && v.trim().length > 255 ? 'Email không được vượt quá 255 ký tự.' : null),
  ],
  phone_number: [
    (v) => (!v || !v.trim() ? 'Số điện thoại không được để trống.' : null),
    (v) =>
      v && !/^[0-9+\-\s()]{7,20}$/.test(v.trim()) ? 'Số điện thoại không hợp lệ.' : null,
  ],
}

// Rule password khi ADD (bắt buộc)
const passwordAddRules = [
  (v) => (!v || !v.trim() ? 'Mật khẩu không được để trống.' : null),
  (v) => (v && v.trim().length < 6 ? 'Mật khẩu phải có ít nhất 6 ký tự.' : null),
  (v) => (v && v.length > 500 ? 'Mật khẩu quá dài.' : null),
]

// Rule password khi EDIT (không bắt buộc, chỉ kiểm tra nếu có nhập)
const passwordEditRules = [
  (v) => (v && v.trim() && v.trim().length < 6 ? 'Mật khẩu phải có ít nhất 6 ký tự.' : null),
]

function validateField(field, value, rules) {
  if (!rules) return ''
  for (const rule of rules) {
    const msg = rule(value)
    if (msg) return msg
  }
  return ''
}

function validateAll(formData, mode = 'add') {
  const errors = {}
  let isValid = true

  // Validate base fields
  for (const field of Object.keys(baseRules)) {
    const msg = validateField(field, formData[field], baseRules[field])
    if (msg) {
      errors[field] = [msg]
      isValid = false
    }
  }

  // Validate password theo mode
  const pwdRules = mode === 'add' ? passwordAddRules : passwordEditRules
  const pwdMsg = validateField('password', formData.password, pwdRules)
  if (pwdMsg) {
    errors['password'] = [pwdMsg]
    isValid = false
  }

  return { errors, isValid }
}

/**
 * Composable useStaffValidation
 *
 * @example
 * const { formErrors, validate, clearErrors, fieldError, applyBackendErrors } = useStaffValidation()
 * if (!validate(form, 'add')) return
 */
export function useStaffValidation() {
  const formErrors = reactive({})

  const clearErrors = () => {
    Object.keys(formErrors).forEach((k) => delete formErrors[k])
  }

  const fieldError = (field) => formErrors[field]?.[0] ?? ''

  /**
   * @param {object} formData
   * @param {'add'|'edit'} mode
   */
  const validate = (formData, mode = 'add') => {
    clearErrors()
    const { errors, isValid } = validateAll(formData, mode)
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
