
Category {
	Id integer pk increments unique
	Name string
	Slug string unique
	ParentId integer null *> Category.Id
	Description string
}

Product {
	Id integer pk increments unique
	CategoryId integer *> Category.Id
	Name string
	Slug string unique
	Description text
	IsActive boolean
	CreatedAt datetime
	DeletedAt datetime null
	Brand string
	ThumbNail string
	User_Manual string
	CreatedByStaffId integer *> Staff.Id
	UpdatedByStaffId integer null *> Staff.Id
}

CartItem {
	Id integer pk increments unique
	CartId integer *> Cart.Id
	ProductVariantID integer *> ProductVariant.Id
	Quantity integer
	CreatedAt datetime
}

OrderDetail {
	Id integer pk increments unique
	OrderId integer *> Order.Id
	ProductVariantId integer *> ProductVariant.Id
	Quantity integer
	UnitPrice decimal(12,2)
	CostPrice decimal(12,2)
	IsReturn boolean
	ReturnQuantity integer
	ReturnRequestId integer >* ReturnRequest.Id
}

Review {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	OrderDetailId integer null *> OrderDetail.Id
	CustomerId integer *> Customer.Id
	Rating integer
	Comment text
	CreatedAt datetime
}

Coupon {
	Id integer pk increments unique
	Code string unique
	DiscountValue decimal(12,2)
	IsActive boolean
	Type string
	PriceMinOrderValue decimal(12,2)
	MaxUsage integer
	UsedCount integer
	ExpiryDate datetime
	CreatedByStaffId integer *> Staff.Id
}

Customer {
	Id integer pk increments unique
	FirstName string
	LastName string
	Email string unique
	PhoneNumber string
	Password string
	Status integer
}

Order {
	Id integer pk increments unique
	OrderCode string unique
	CustomerId integer *> Customer.Id
	CouponId integer null *> Coupon.Id
	ShippingName string
	ShippingPhone string
	ShippingAddress string
	SubTotalAmount decimal(12,2)
	CouponDiscountAmount decimal(12,2)
	ShippingFee decimal(12,2)
	FinalAmount decimal(12,2)
	Status string
	PaymentStatus string
	CreatedAt datetime
}

CustomerAddress {
	Id integer pk increments unique
	ReceiverName string
	ReceiverPhone string
	Province string
	District string
	Ward string
	DetailAddress string
	IsDefault boolean
	CustomerId integer *> Customer.Id
}

ProductImage {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	ImageUrl string
	AltText string
	DisplayOrder integer
	IsThumbnail boolean
	CreatedAt datetime
}

Staff {
	Id integer pk increments unique
	FullName string
	Email string unique
	Password string
	PhoneNumber string null
	Avatar string null
	IsActive boolean
	LastLoginAt datetime null
	CreatedAt datetime
	DeletedAt datetime null
}

Role {
	Id integer pk increments unique
	Name string unique
	Slug string unique
	Description string null
	CreatedAt datetime
}

RolePermission {
	RoleId integer *> Role.Id
	PermissionId integer *> Permission.Id
}

StaffRole {
	StaffId integer *> Staff.Id
	RoleId integer *> Role.Id
}

StaffPermission {
	StaffId integer *> Staff.Id
	PermissionId integer *> Permission.Id
}

ReturnRequest {
	Id integer pk increments unique
	OrderId integer > Order.Id
	Reason text
	EvidenceImages json null
	Status string
	RefundAmount decimal(12,2) null
	ProcessedByStaffId integer null *> Staff.Id
	CreatedAt datetime
	UpdatedAt datetime
}

Banner {
	Title string
	Id integer pk increments unique
	ImageUrl string
	TargetUrl string null
	Position string
	DisplayOrder integer
	IsActive boolean
	StartDate datetime null
	EndDate datetime null
}

Wishlist {
	Id integer pk increments unique
	CustomerId integer *> Customer.Id
	ProductId integer *> Product.Id
	CreatedAt datetime
}

Notification {
	Id integer pk increments unique
	CustomerId integer *> Customer.Id
	Title string
	Content text
	Type string
	IsRead boolean
	CreatedAt datetime
}

Attribute {
	Id integer pk increments unique
	Name string
}

AttributeValue {
	Id integer pk increments unique
	AttributeId integer *> Attribute.Id
	Value string
	ProductVariantId integer *> ProductVariant.Id
}

ProductVariant {
	Id integer pk increments unique
	ProductId integer *> Product.Id
	Price decimal(12,2)
	SalePrice decimal(12,2)
	CostPrice decimal(12,2) null
	StockQuantity integer
	Thumbnail string
	IsActive boolean
	SKU string unique
}

Cart {
	Id integer pk increments unique
	CustomerId integer unique > Customer.Id
	Status string
	CreatedAt datetime
	UpdatedAt datetime
}

Blog {
	Id integer pk increments unique
	Name string
	Slug string unique
	Description text
	Status boolean
	Image string
	IsActive boolean
	CreatedAt datetime
}

Supplier {
	Id integer pk increments unique
	Name string
	Phone string null
	Email string null unique
	Address string null
	IsActive boolean
	CreatedAt datetime
	UpdatedAt datetime
}

GoodsReceipt {
	Id integer pk increments unique
	ReceiptCode string unique
	SupplierId integer *> Supplier.Id
	StaffId integer *> Staff.Id
	TotalAmountPrice decimal(12,2)
	Note text null
	Status string
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

Permission {
	Id integer pk increments unique
	Module string
	Action string
	Name string unique
	Description string null
}

SystemSetting {
	Key string pk unique
	Value text
	DataType string
	Description string null
	UpdatedByStaffId integer null *> Staff.Id
	UpdatedAt datetime
}

CustomerCoupon {
	Id integer pk increments unique
	CouponId integer *> Coupon.Id
	CustomerId integer *> Customer.Id
	UsedAt datetime
}

