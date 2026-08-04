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
import Statistics from '../views/admin/Statistics.vue'

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
    path: '/admin/statistics',
    name: 'AdminStatistics',
    component: Statistics,
    meta: { layout: 'AdminLayout' }
  },
  {
    path: '/admin/categories',
    name: 'AdminCategory',
    component: Category,
    meta: { layout: 'AdminLayout', permission: 'categories.view' }
  },
  {
    path: '/admin/products',
    name: 'AdminProduct',
    component: Product,
    meta: { layout: 'AdminLayout', permission: 'products.view' }
  },
  {
    path: '/admin/product-attributes',
    name: 'AdminProductAttribute',
    component: AttributeProduct,
    meta: { layout: 'AdminLayout', permission: 'attributes.view' }
  },
  {
    path: '/admin/orders',
    name: 'AdminOrder',
    component: Order,
    meta: { layout: 'AdminLayout', permission: 'orders.view' }
  },
  {
    path: '/admin/return-requests',
    name: 'AdminReturnRequire',
    component: ReturnRequire,
    meta: { layout: 'AdminLayout', permission: 'orders.view' }
  },
  {
    path: '/admin/suppliers',
    name: 'AdminSupplier',
    component: Supplier,
    meta: { layout: 'AdminLayout', permission: 'suppliers.view' }
  },
  {
    path: '/admin/warehouse-receipts',
    name: 'AdminWarehouseReceipt',
    component: WarehouseReceipt,
    meta: { layout: 'AdminLayout', permission: 'goods_receipts.view' }
  },
  {
    path: '/admin/customers',
    name: 'AdminCustomer',
    component: Customer,
    meta: { layout: 'AdminLayout', permission: 'customers.view' }
  },
  {
    path: '/admin/reviews',
    name: 'AdminReviewManagement',
    component: ReviewManagement,
    meta: { layout: 'AdminLayout', permission: 'reviews.view' }
  },
  {
    path: '/admin/discounts',
    name: 'AdminDiscountCode',
    component: DiscountCode,
    meta: { layout: 'AdminLayout', permission: 'coupons.view' }
  },
  {
    path: '/admin/banners',
    name: 'AdminAdvertisementBanner',
    component: AdvertisementBanner,
    meta: { layout: 'AdminLayout', permission: 'banners.view' }
  },
  {
    path: '/admin/blog',
    name: 'AdminBlog',
    component: Blog,
    meta: { layout: 'AdminLayout', permission: 'blogs.view' }
  },
  {
    path: '/admin/staff-accounts',
    name: 'AdminStaff',
    component: Staff,
    meta: { layout: 'AdminLayout', permission: 'staff.view' }
  },
  {
    path: '/admin/roles-permissions',
    name: 'AdminRole',
    component: Role,
    meta: { layout: 'AdminLayout', permission: 'roles.view' }
  },
  {
    path: '/admin/settings',
    name: 'AdminConfig',
    component: Config,
    meta: { layout: 'AdminLayout', permission: 'system_settings.view' }
  }
]

export default adminRoutes
