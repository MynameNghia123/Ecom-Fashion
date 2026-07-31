import { reactive } from 'vue'

const orderRules = {
  'customer_address.receiver_name': [
    (v) => (!v || !v.trim() ? 'Tên người nhận không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Tên người nhận không được vượt quá 255 ký tự.' : null),
  ],
  'customer_address.receiver_phone': [
    (v) => (!v || !v.trim() ? 'Số điện thoại không được để trống.' : null),
    (v) => (v && !/^[0-9]{10}$/.test(v.trim()) ? 'Số điện thoại phải gồm đúng 10 chữ số.' : null),
  ],
  'customer_address.province': [
    (v) => (!v || !v.trim() ? 'Tỉnh/Thành phố không được để trống.' : null),
  ],
  'customer_address.district': [
    (v) => (!v || !v.trim() ? 'Quận/Huyện không được để trống.' : null),
  ],
  'customer_address.ward': [
    (v) => (!v || !v.trim() ? 'Phường/Xã không được để trống.' : null),
  ],
  'customer_address.detail_address': [
    (v) => (!v || !v.trim() ? 'Địa chỉ chi tiết không được để trống.' : null),
  ],
  'payment_method': [
    (v) => (!v ? 'Vui lòng chọn phương thức thanh toán.' : null),
  ],
}

function getValueByPath(obj, path) {
  return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}

function validateField(field, value, formData = {}) {
  const rules = orderRules[field]
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
  for (const field of Object.keys(orderRules)) {
    const value = getValueByPath(formData, field);
    const msg = validateField(field, value, formData)
    if (msg) {
      errors[field] = [msg]
      isValid = false
    }
  }
  return { errors, isValid }
}

export function useOrderValidation() {
  const formErrors = reactive({})

  const clearErrors = () => {
    Object.keys(formErrors).forEach((k) => delete formErrors[k])
  }

  const fieldError = (field) => formErrors[field]?.[0] ?? ''

  const validate = (formData) => {
    clearErrors()
    const { errors: validationErrors, isValid } = validateAll(formData)
    if (!isValid) Object.assign(formErrors, validationErrors)
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
