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
    meta: { layout: 'BlankLayout' }
  },
  {
    path: '/admin',
    redirect: '/admin/dashboard'
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: DashBoard,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/categories',
    name: 'AdminCategory',
    component: Category,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/products',
    name: 'AdminProduct',
    component: Product,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/product-attributes',
    name: 'AdminProductAttribute',
    component: AttributeProduct,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/orders',
    name: 'AdminOrder',
    component: Order,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/return-requests',
    name: 'AdminReturnRequire',
    component: ReturnRequire,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/suppliers',
    name: 'AdminSupplier',
    component: Supplier,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/warehouse-receipts',
    name: 'AdminWarehouseReceipt',
    component: WarehouseReceipt,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/customers',
    name: 'AdminCustomer',
    component: Customer,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/reviews',
    name: 'AdminReviewManagement',
    component: ReviewManagement,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/discounts',
    name: 'AdminDiscountCode',
    component: DiscountCode,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/banners',
    name: 'AdminAdvertisementBanner',
    component: AdvertisementBanner,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/blog',
    name: 'AdminBlog',
    component: Blog,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/staff-accounts',
    name: 'AdminStaff',
    component: Staff,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/roles-permissions',
    name: 'AdminRole',
    component: Role,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/settings',
    name: 'AdminConfig',
    component: Config,
    meta: { layout: 'AdminLayout' }
  }
]

export default adminRoutes
