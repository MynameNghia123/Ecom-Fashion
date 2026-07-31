import SignIn from '../views/admin/SignIn.vue'
import DashBoard from '../views/admin/DashBoard.vue'
import Category from '../views/admin/ProductManagement/Category.vue'
import Product from '../views/admin/ProductManagement/Product.vue'
import AttributeProduct from '../views/admin/ProductManagement/AttributeProduct.vue'
import Order from '../views/admin/SellManagement/Order.vue'
import ReturnRequire from '../views/admin/SellManagement/ReturnRequire.vue'
import Supplier from '../views/admin/StorageManagement/Supplier.vue'
import WarehouseReceipt from '../views/admin/StorageManagement/WarehouseReceipt.vue'
import Customer from '../views/admin/CustomerAndReview/Customer.vue'
import ReviewManagement from '../views/admin/CustomerAndReview/ReviewManagement.vue'
import DiscountCode from '../views/admin/Marketing/DiscountCode.vue'
import AdvertisementBanner from '../views/admin/Marketing/AdvertisementBanner.vue'
import Blog from '../views/admin/Content/Blog.vue'
import Staff from '../views/admin/Staff/Staff.vue'
import Role from '../views/admin/Staff/Role.vue'
import Config from '../views/admin/SystemConfiguration/Config.vue'

const adminRoutes = [
  {
    path: '/admin/signin',
    name: 'AdminSignIn',
    component: SignIn,
    meta: { layout: 'BlankLayout', requiresGuest: true }
  },
  {
    path: '/admin',
    redirect: '/admin/dashboard'
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: DashBoard,
    meta: { layout: 'AdminLayout', requiresAuth: true }
    // Dashboard không yêu cầu permission cụ thể
  },
  {
    path: '/admin/categories',
    name: 'AdminCategory',
    component: Category,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'categories:read' }
  },
  {
    path: '/admin/products',
    name: 'AdminProduct',
    component: Product,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'products:read' }
  },
  {
    path: '/admin/product-attributes',
    name: 'AdminProductAttribute',
    component: AttributeProduct,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'attributes:read' }
  },
  {
    path: '/admin/orders',
    name: 'AdminOrder',
    component: Order,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'orders:read' }
  },
  {
    path: '/admin/return-requests',
    name: 'AdminReturnRequire',
    component: ReturnRequire,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'returns:read' }
  },
  {
    path: '/admin/suppliers',
    name: 'AdminSupplier',
    component: Supplier,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'suppliers:read' }
  },
  {
    path: '/admin/warehouse-receipts',
    name: 'AdminWarehouseReceipt',
    component: WarehouseReceipt,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'goods_receipts:read' }
  },
  {
    path: '/admin/customers',
    name: 'AdminCustomer',
    component: Customer,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'customers:read' }
  },
  {
    path: '/admin/reviews',
    name: 'AdminReviewManagement',
    component: ReviewManagement,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'reviews:read' }
  },
  {
    path: '/admin/discounts',
    name: 'AdminDiscountCode',
    component: DiscountCode,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'coupons:read' }
  },
  {
    path: '/admin/banners',
    name: 'AdminAdvertisementBanner',
    component: AdvertisementBanner,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'banners:read' }
  },
  {
    path: '/admin/blog',
    name: 'AdminBlog',
    component: Blog,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'blogs:read' }
  },
  {
    path: '/admin/staff-accounts',
    name: 'AdminStaff',
    component: Staff,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'staffs:read' }
  },
  {
    path: '/admin/roles-permissions',
    name: 'AdminRole',
    component: Role,
    meta: { layout: 'AdminLayout', requiresAuth: true, permission: 'roles:read' }
  },
  {
    path: '/admin/settings',
    name: 'AdminConfig',
    component: Config,
    meta: { layout: 'AdminLayout', requiresAuth: true }
  },
  {
    path: '/admin/403',
    name: 'AdminForbidden',
    component: () => import('../views/admin/Forbidden.vue'),
    meta: { layout: 'AdminLayout', requiresAuth: true }
  }
]

export default adminRoutes

