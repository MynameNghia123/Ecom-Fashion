// ============================================================
// EcomFashion Database Schema (DBML-style)
// Convention: Laravel snake_case (tables plural, columns snake_case)
// Relationships: *> = many-to-one (FK), > = one-to-one (FK)
// ============================================================

// ===================== STAFF & RBAC =====================

Staff {
	Id integer pk increments unique
	FullName string
	Email string unique
	Password string
	PhoneNumber string null
	Avatar string null
	IsActive boolean default(true)
	LastLoginAt datetime null
	CreatedAt datetime
	UpdatedAt datetime
	DeletedAt datetime null
}

Role {
	Id integer pk increments unique
	Name string unique
	Description string null
	CreatedAt datetime
	UpdatedAt datetime
}

Permission {
	Id integer pk increments unique
	Module string
	Action string
	// unique(['module', 'action']) — composite unique index
}

RolePermission {
	RoleId integer *> Role.Id
	PermissionId integer *> Permission.Id
	// pk(['role_id', 'permission_id']) — composite primary key
}

StaffRole {
	StaffId integer *> Staff.Id
	RoleId integer *> Role.Id
	// pk(['staff_id', 'role_id']) — composite primary key
}

StaffPermission {
	StaffId integer *> Staff.Id
	PermissionId integer *> Permission.Id
	// pk(['staff_id', 'permission_id']) — composite primary key
}

// ===================== CUSTOMER =====================

Customer {
	Id integer pk increments unique
	FirstName string
	LastName string
	Email string unique
	PhoneNumber string null
	Password string
	Status integer default(1)
	CreatedAt datetime
	UpdatedAt datetime
}

CustomerAddress {
	Id integer pk increments unique
	CustomerId integer *> Customer.Id
	ReceiverName string
	ReceiverPhone string
	Province string
	District string
	Ward string
	DetailAddress string
	IsDefault boolean default(false)
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== CATALOG =====================

Category {
	Id integer pk increments unique
	Name string
	Slug string unique
	ParentId integer null *> Category.Id
	Description string null
	CreatedAt datetime
	UpdatedAt datetime
}

Product {
	Id integer pk increments unique
	CategoryId integer *> Category.Id
	Name string
	Slug string unique
	Description text null
	Brand string null
	Thumbnail string null
	UserManual string null
	IsActive boolean default(true)
	CreatedByStaffId integer *> Staff.Id
	UpdatedByStaffId integer null *> Staff.Id
	CreatedAt datetime
	UpdatedAt datetime
	DeletedAt datetime null
}

ProductImage {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	ProductVariantId integer null *> ProductVariant.Id
	ImageUrl string
	AltText string null
	DisplayOrder integer default(0)
	IsThumbnail boolean default(false)
	CreatedAt datetime
	UpdatedAt datetime
}

Attribute {
	Id integer pk increments unique
	Name string
	CreatedAt datetime
	UpdatedAt datetime
}

ProductVariant {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	SKU string unique
	Price decimal(12,2)
	SalePrice decimal(12,2) null
	CostPrice decimal(12,2) null
	StockQuantity integer default(0)
	Thumbnail string null
	IsActive boolean default(true)
	CreatedAt datetime
	UpdatedAt datetime
}

AttributeValue {
	Id integer pk increments unique
	AttributeId integer *> Attribute.Id
	ProductVariantId integer *> ProductVariant.Id
	Value string
	// unique(['attribute_id', 'product_variant_id']) — mỗi variant chỉ có 1 giá trị cho 1 attribute
}

// ===================== CART =====================

Cart {
	Id integer pk increments unique
	CustomerId integer unique > Customer.Id
	Status string default('active')
	CreatedAt datetime
	UpdatedAt datetime
}

CartItem {
	Id integer pk increments unique
	CartId integer *> Cart.Id
	ProductVariantId integer *> ProductVariant.Id
	Quantity integer
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== ORDERS & PAYMENT =====================

Order {
	Id integer pk increments unique
	OrderCode string unique
	CustomerId integer *> Customer.Id
	CouponId integer null *> Coupon.Id
	ShippingName string
	ShippingPhone string
	ShippingAddress string
	SubTotalAmount decimal(12,2)
	CouponDiscountAmount decimal(12,2) default(0)
	ShippingFee decimal(12,2) default(0)
	FinalAmount decimal(12,2)
	Status string default('pending')
	PaymentMethod string
	PaymentStatus string default('unpaid')
	TransactionId string null
	CreatedAt datetime
	UpdatedAt datetime
}

OrderDetail {
	Id integer pk increments unique
	OrderId integer *> Order.Id
	ProductVariantId integer *> ProductVariant.Id
	Quantity integer
	UnitPrice decimal(12,2)
	CostPrice decimal(12,2)
	IsReturn boolean default(false)
	ReturnQuantity integer default(0)
	ReturnRequestId integer null *> ReturnRequest.Id
}

ReturnRequest {
	Id integer pk increments unique
	OrderId integer *> Order.Id
	Reason text
	EvidenceImages json null
	Status string default('pending')
	RefundAmount decimal(12,2) null
	ProcessedByStaffId integer null *> Staff.Id
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== COUPON =====================

Coupon {
	Id integer pk increments unique
	Code string unique
	Type string
	DiscountValue decimal(12,2)
	PriceMinOrderValue decimal(12,2) default(0)
	MaxUsage integer
	UsedCount integer default(0)
	IsActive boolean default(true)
	ExpiryDate datetime
	CreatedByStaffId integer *> Staff.Id
	CreatedAt datetime
	UpdatedAt datetime
}

CustomerCoupon {
	Id integer pk increments unique
	CouponId integer *> Coupon.Id
	CustomerId integer *> Customer.Id
	UsedAt datetime
	// unique(['coupon_id', 'customer_id']) — mỗi khách chỉ dùng 1 coupon 1 lần
}

// ===================== REVIEW =====================

Review {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	OrderDetailId integer null *> OrderDetail.Id
	CustomerId integer *> Customer.Id
	Rating integer
	Comment text null
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== WISHLIST =====================

Wishlist {
	Id integer pk increments unique
	CustomerId integer *> Customer.Id
	ProductId integer *> Product.Id
	CreatedAt datetime
	// unique(['customer_id', 'product_id']) — mỗi khách chỉ thích 1 sản phẩm 1 lần
}

// ===================== NOTIFICATION =====================

Notification {
	Id integer pk increments unique
	CustomerId integer *> Customer.Id
	Title string
	Content text
	Type string
	IsRead boolean default(false)
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== BLOG =====================

Blog {
	Id integer pk increments unique
	Name string
	Slug string unique
	Description text null
	Image string null
	Status boolean default(true)
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== BANNER =====================

Banner {
	Id integer pk increments unique
	Title string
	ImageUrl string
	TargetUrl string null
	Position string
	DisplayOrder integer default(0)
	IsActive boolean default(true)
	StartDate datetime null
	EndDate datetime null
	CreatedAt datetime
	UpdatedAt datetime
}

// ===================== SUPPLIER & GOODS RECEIPT =====================

Supplier {
	Id integer pk increments unique
	Name string
	Phone string null
	Email string null unique
	Address string null
	IsActive boolean default(true)
	CreatedAt datetime
	UpdatedAt datetime
}

GoodsReceipt {
	Id integer pk increments unique
	ReceiptCode string unique
	SupplierId integer *> Supplier.Id
	StaffId integer *> Staff.Id
	TotalAmountPrice decimal(12,2)
	Status string default('pending')
	CreatedAt datetime
	UpdatedAt datetime
}

GoodsReceiptDetail {
	Id integer pk increments unique
	GoodsReceiptId integer *> GoodsReceipt.Id
	ProductVariantId integer *> ProductVariant.Id
	Quantity integer
	ImportPrice decimal(12,2)
}

// ===================== SYSTEM =====================

SystemSetting {
	Key string pk unique
	Value text
	DataType string
	Description string null
	UpdatedByStaffId integer null *> Staff.Id
	UpdatedAt datetime
}
