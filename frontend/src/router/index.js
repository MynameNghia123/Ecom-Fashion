import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '../views/client/home/Homepage.vue'
import ProductDetail from '../views/client/products/ProductDetail.vue'
import BlogList from '../views/client/blog/BlogList.vue'
import Contact from '../views/client/contact/Contact.vue'
import AboutUs from '../views/client/about/AboutUs.vue'
import adminRoutes from './adminRoutes'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Homepage
  },
  {
    path: '/product/AB258041NTR26',
    name: 'ProductDetailStatic',
    component: ProductDetail
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail
  },
  {
    path: '/blog',
    name: 'Blog',
    component: BlogList
  },
  {
    path: '/contact',
    name: 'Contact',
    component: Contact
  },
  {
    path: '/about',
    name: 'AboutUs',
    component: AboutUs
  },
  ...adminRoutes
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// ── Navigation Guard ─────────────────────────────────────────────────────────
router.beforeEach((to, _from, next) => {
  const token       = localStorage.getItem('admin_token')
  const permissions = JSON.parse(localStorage.getItem('admin_permissions') || '[]')

  // 1. Chưa đăng nhập → về trang signin
  if (to.meta.requiresAuth && !token) {
    return next({ name: 'AdminSignIn' })
  }

  // 2. Đã đăng nhập → không cho vào trang signin nữa
  if (to.meta.requiresGuest && token) {
    return next({ name: 'AdminDashboard' })
  }

  // 3. Kiểm tra permission nếu route có yêu cầu
  if (to.meta.permission && token) {
    if (!permissions.includes(to.meta.permission)) {
      return next({ name: 'AdminForbidden' })
    }
  }

  next()
})

export default router
