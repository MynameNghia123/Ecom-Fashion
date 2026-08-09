$files = @(
  'src\components\admin\supplier\SupplierView.vue',
  'src\components\admin\supplier\SupplierForm.vue',
  'src\components\admin\staff\StaffViewModal.vue',
  'src\components\admin\staff\StaffFormModal.vue',
  'src\components\admin\product\ProductVariantsUploader.vue',
  'src\components\admin\product\ProductImageUploader.vue',
  'src\components\admin\product\ProductFormModal.vue',
  'src\components\admin\product\ProductDetailModal.vue',
  'src\components\admin\Pagination.vue',
  'src\components\admin\goodsReceipts\GoodsReceiptUpdateModal.vue',
  'src\components\admin\goodsReceipts\GoodsReceiptAddModal.vue',
  'src\components\admin\goodsReceipts\GoodsReceiptDetailModal.vue',
  'src\components\admin\category\CategoryViewModal.vue',
  'src\components\admin\category\CategoryFormModal.vue',
  'src\components\admin\attribute\AttributeFormModal.vue',
  'src\views\admin\Staff\Role.vue',
  'src\views\admin\SignIn.vue',
  'src\views\admin\ProductManagement\Product.vue',
  'src\views\admin\ProductManagement\Category.vue',
  'src\views\admin\Marketing\DiscountCode.vue',
  'src\views\admin\Marketing\AdvertisementBanner.vue',
  'src\views\admin\CustomerAndReview\ReviewManagement.vue',
  'src\views\admin\CustomerAndReview\Customer.vue',
  'src\views\admin\Content\Blog.vue'
)

foreach ($f in $files) {
  $path = "d:\Ecom_Fashion\frontend\" + $f
  if (Test-Path $path) {
    $content = Get-Content $path -Raw -Encoding UTF8
    $orig = $content
    # CTA buttons bg
    $content = $content -replace 'bg-\[#0258cb\]', 'bg-black'
    $content = $content -replace 'hover:bg-\[#004bb3\]', 'hover:bg-neutral-800'
    # shadow blue
    $content = $content -replace 'shadow-blue-200', 'shadow-neutral-200'
    $content = $content -replace 'hover:shadow-blue-300', 'hover:shadow-neutral-300'
    # focus border
    $content = $content -replace 'focus:border-\[#0258cb\]', 'focus:border-black'
    $content = $content -replace 'focus:ring-\[#0258cb\]/10', 'focus:ring-black/10'
    $content = $content -replace 'focus:ring-\[#0258cb\]/20', 'focus:ring-black/20'
    # hover text blue on icon buttons
    $content = $content -replace 'hover:text-\[#0258cb\]', 'hover:text-black'
    $content = $content -replace 'hover:bg-blue-50', 'hover:bg-neutral-100'
    # text blue accent
    $content = $content -replace 'text-\[#0258cb\]', 'text-black'
    # border blue
    $content = $content -replace 'border-\[#0258cb\]', 'border-black'
    # ring blue
    $content = $content -replace 'ring-\[#0258cb\]/10', 'ring-black/10'
    $content = $content -replace 'ring-\[#0258cb\]/20', 'ring-black/20'
    # accent
    $content = $content -replace 'accent-\[#0258cb\]', 'accent-black'
    # toggle checked
    $content = $content -replace 'peer-checked:bg-\[#0258cb\]', 'peer-checked:bg-black'
    # spinner border
    $content = $content -replace 'border-t-\[#0258cb\]', 'border-t-black'
    $content = $content -replace 'border-\[#0258cb\]/20', 'border-black/20'
    # group hover
    $content = $content -replace "group-hover:text-\[#0258cb\]", 'group-hover:text-black'
    # bg-blue-50 active states
    $content = $content -replace "bg-blue-50/50 border-\[#0258cb\]", 'bg-neutral-100 border-black'

    if ($content -ne $orig) {
      [System.IO.File]::WriteAllText($path, $content, [System.Text.Encoding]::UTF8)
      Write-Host "UPDATED: $f"
    } else {
      Write-Host "NO CHANGE: $f"
    }
  } else {
    Write-Host "NOT FOUND: $f"
  }
}
Write-Host "Done."
