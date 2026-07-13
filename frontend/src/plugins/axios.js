import axios from 'axios'
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' }
})
// Request interceptor: tự gắn Bearer token vào mọi request
api.interceptors.request.use(config => {
  const token = localStorage.getItem('customer_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})
export default api