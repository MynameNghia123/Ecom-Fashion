
import api from '@/plugins/axios'
export const authService = {
  register:       (data) => api.post('/client/auth/register',        data),
  login:          (data) => api.post('/client/auth/login',           data),
  logout:         ()     => api.post('/client/auth/logout'),
  me:             ()     => api.get('/client/auth/me'),
  forgotPassword: (data) => api.post('/client/auth/forgot-password', data),
  verifyOtp:      (data) => api.post('/client/auth/verify-otp',      data),
  resetPassword:  (data) => api.post('/client/auth/reset-password',  data),
}