import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '../views/client/home/Homepage.vue'
import ProductDetail from '../views/client/products/ProductDetail.vue'
import BlogList from '../views/client/blog/BlogList.vue'
import Contact from '../views/client/contact/Contact.vue'
import AboutUs from '../views/client/about/AboutUs.vue'
import Profile from '../views/client/profile/Profile.vue'
import Address from '../views/client/profile/Address.vue'
import Information from '../views/client/profile/Informations.vue'
import Notification from '../views/client/profile/Notifications.vue'
import OrderHistory from '../views/client/profile/OrderHistory.vue'
import Reviews from '../views/client/profile/Reviews.vue'
import Vouchers from '../views/client/profile/Vouchers.vue'
import WishList from '../views/client/profile/WishList.vue'
import Settings from '../views/client/profile/Settings.vue'
import CheckoutSuccess from '../views/client/checkout/CheckoutSuccess.vue'
import Checkout from '../views/client/checkout/Checkout.vue'
import VNPayReturn from '../views/client/checkout/VNPayReturn.vue'
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
    component: Profile,
    children: [
      {
        path: 'address',
        name: 'Address',
        component: Address
      },
      {
        path: 'information',
        name: 'Informations',
        component: Information
      },
      {
        path: 'notification',
        name: 'Notifications',
        component: Notification
      },
      {
        path: 'order-history',
        name: 'OrderHistory',
        component: OrderHistory
      },
      {
        path: 'reviews',
        name: 'Reviews',
        component: Reviews
      },
      {
        path: 'vouchers',
        name: 'Vouchers',
        component: Vouchers
      },
      {
        path: 'wishlist',
        name: 'WishLists',
        component: WishList
      },
      {
        path: 'settings',
        name: 'Settings',
        component: Settings
      }
    ]
  },
 
  {
    path: '/checkout/success',
    name: 'CheckoutSuccess',
    component: CheckoutSuccess
  },
  {
    path: '/checkout/vnpay-return',
    name: 'VNPayReturn',
    component: VNPayReturn
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: Checkout
  },

  ...adminRoutes
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

