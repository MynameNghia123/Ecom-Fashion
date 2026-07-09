import { reactive } from 'vue'

const goodsReceiptRules = {
  receipt_code: [
    (v) => (!v || !v.trim() ? 'Mã phiếu nhập không được để trống.' : null),
    (v) => (v && v.trim().length > 255 ? 'Mã phiếu nhập không được vượt quá 255 ký tự.' : null),
  ],
  supplier_id: [
    (v) => (!v ? 'Vui lòng chọn nhà cung cấp.' : null),
  ],
  status: [
    (v) => (!v ? 'Trạng thái không được để trống.' : null),
  ],
  good_receipt_details: [
    (v) => (!v || !Array.isArray(v) || v.length === 0 ? 'Vui lòng thêm ít nhất 1 sản phẩm hợp lệ.' : null),
  ]
}

function validateField(field, value, formData = {}) {
  const rules = goodsReceiptRules[field]
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
  for (const field of Object.keys(goodsReceiptRules)) {
    const msg = validateField(field, formData[field], formData)
    if (msg) {
      errors[field] = msg
      isValid = false
    }
  }
  return { errors, isValid }
}

export function useGoodsReceiptValidation() {
  const errors = reactive({
    receipt_code: '',
    supplier_id: '',
    status: '',
    good_receipt_details: ''
  })

  const clearErrors = () => {
    errors.receipt_code = ''
    errors.supplier_id = ''
    errors.status = ''
    errors.good_receipt_details = ''
  }

  const validate = (formData) => {
    clearErrors()
    const { errors: validationErrors, isValid } = validateAll(formData)
    if (!isValid) Object.assign(errors, validationErrors)
    return isValid
  }

  const applyBackendErrors = (e) => {
    const backendErrors = e.response?.data?.errors
    if (backendErrors) {
      if (backendErrors.receipt_code) errors.receipt_code = backendErrors.receipt_code[0]
      if (backendErrors.supplier_id) errors.supplier_id = backendErrors.supplier_id[0]
      if (backendErrors.status) errors.status = backendErrors.status[0]
      if (backendErrors.good_receipt_details) errors.good_receipt_details = backendErrors.good_receipt_details[0]
      return ''
    }
    return e.response?.data?.message || 'Có lỗi xảy ra.'
  }

  return { errors, validate, clearErrors, applyBackendErrors }
}
