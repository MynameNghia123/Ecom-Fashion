import http from '@/services/shared/http'

const UPLOAD_URL = '/admin/upload-image'

export const uploadService = {
  /**
   * Upload một file ảnh lên server storage.
   * Server lưu file vào storage/public/images/{folder} và trả về URL đầy đủ.
   *
   * @param {File} file - File object từ input
   * @param {string} folder - Thư mục con trong images/ (mặc định: 'products')
   * @returns {Promise<{ url: string, path: string }>}
   */
  async uploadImage(file, folder = 'products') {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('folder', folder)

    const res = await http.post(UPLOAD_URL, formData, {
      headers: {
        // Bỏ Content-Type để axios tự set multipart/form-data + boundary
        'Content-Type': 'multipart/form-data',
      },
    })
    return res.data  // { success: true, url, path }
  },

  /**
   * Xóa file ảnh đã upload (khi user bỏ ảnh trước khi lưu sản phẩm).
   *
   * @param {string} path - Đường dẫn tương đối (ví dụ: images/products/xxx.jpg)
   */
  async deleteImage(path) {
    if (!path) return
    await http.delete(UPLOAD_URL, { data: { path } })
  },
}
