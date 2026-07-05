import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '../views/client/home/Homepage.vue'
import ProductDetail from '../views/client/products/ProductDetail.vue'
import BlogList from '../views/client/blog/BlogList.vue'
import Contact from '../views/client/contact/Contact.vue'
import AboutUs from '../views/client/about/AboutUs.vue'
import Profile from '../views/client/profile/Profile.vue'
import CheckoutSuccess from '../views/client/checkout/CheckoutSuccess.vue'
import Checkout from '../views/client/checkout/Checkout.vue'
import Cart from '../views/client/cart/Cart.vue'
import CategoryPage from '../views/client/category/CategoryPage.vue'
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
  {
    path: '/profile',
    name: 'Profile',
    component: Profile
  },
  {
    path: '/checkout/success',
    name: 'CheckoutSuccess',
    component: CheckoutSuccess
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: Checkout
  },
  {
    path: '/cart',
    name: 'Cart',
    component: Cart
  },
  {
    // :slug để sau này lọc theo từng category qua API
    // Ví dụ: /category/ao-khoac, /category/quan-jean
    path: '/category/:slug?',
    name: 'Category',
    component: CategoryPage
  },
  ...adminRoutes
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// ── Global Navigation Guard for Admin Auth & RBAC ───────────────────────────
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('admin_token')
  const isAdminRoute = to.path.startsWith('/admin')
  const isSignInRoute = to.path === '/admin/signin'

  if (isAdminRoute && !isSignInRoute) {
    if (!token) {
      next('/admin/signin')
    } else {
      // Resolve authStore inside the guard
      const { useAuthStore } = await import('@/stores/admin/authStore')
      const authStore = useAuthStore()

      // Fetch user profile if not loaded
      if (!authStore.user) {
        try {
          await authStore.fetchCurrentUser()
        } catch (e) {
          next('/admin/signin')
          return
        }
      }

      // Enforce RBAC checks based on route permission meta
      if (to.meta && to.meta.permission) {
        const [module, action] = to.meta.permission.split('.')
        if (!authStore.hasPermission(module, action)) {
          alert('Bạn không có quyền truy cập trang này.')
          next('/admin/dashboard')
          return
        }
      }
      next()
    }
  } else if (isSignInRoute && token) {
    next('/admin/dashboard')
  } else {
    next()
  }
})

export default router
