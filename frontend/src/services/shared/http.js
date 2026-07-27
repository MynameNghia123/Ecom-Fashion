import axios from 'axios'

const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 30000, // 30s mặc định
})

// ─── Request Interceptor: Đính kèm Bearer token ───────────────────────────
http.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('admin_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// ─── Response Interceptor: Xử lý lỗi tập trung ───────────────────────────
// Backend luôn trả về { success: false, message, errors? } khi thất bại
http.interceptors.response.use(
  // Thành công: trả về response nguyên vẹn (data.success === true)
  (response) => response,

  // Thất bại: chuẩn hóa lỗi trước khi throw
  (error) => {
    const status  = error.response?.status
    const data    = error.response?.data  // { success: false, message, errors? }

    // Gắn message chuẩn từ backend vào error object để store/view dùng trực tiếp
    error.message = data?.message || getDefaultMessage(status)
    // Gắn luôn errors (validation) nếu có
    error.errors  = data?.errors  || null

    // 401 → Xóa token & redirect về trang đăng nhập
    if (status === 401) {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
      window.location.href = '/admin/signin'
    }

    return Promise.reject(error)
  }
)

/**
 * Trả về thông báo mặc định theo mã HTTP khi backend không gửi message
 * @param {number|undefined} status
 * @returns {string}
 */
function getDefaultMessage(status) {
  const map = {
    400: 'Yêu cầu không hợp lệ.',
    401: 'Bạn chưa đăng nhập hoặc phiên đăng nhập đã hết hạn.',
    403: 'Bạn không có quyền thực hiện hành động này.',
    404: 'Dữ liệu không tìm thấy hoặc đã bị xóa.',
    405: 'Phương thức không được hỗ trợ.',
    422: 'Dữ liệu đầu vào không hợp lệ.',
    500: 'Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.',
  }
  return map[status] ?? 'Đã xảy ra lỗi không xác định.'
}

export default http

