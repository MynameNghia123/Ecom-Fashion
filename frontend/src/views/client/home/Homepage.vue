<template>
  <div class="homepage-container">
    <!-- Hero Section -->
    <div class="hero-section">
      <!-- Background placeholder since user will add banner later -->
      <div class="hero-background"></div>
      <!-- Hero Content -->
      <div class="hero-content">
        <h2 class="subtitle">Xu Hướng Mùa Hè Này</h2>
        <h1 class="giant-title">NURFIA</h1>
        <p class="description">
          Được thiết kế cho những ai trân trọng sự tối giản và mạnh mẽ. Hình dáng đương đại, chi tiết cân đối
          <br>và phong thái tự tin trong mùa hè.
        </p>
        <button class="cta-button">XEM BỘ SƯU TẬP</button>
      </div>

      <!-- Slider Controls (Mockup) -->
      <div class="slider-controls">
        <button class="slider-arrow left">
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="slider-arrow right">
           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
      
      <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
      </div>
    </div>

    <!-- Redefine Your Wardrobe Section -->
    <div class="product-section">
      <div class="section-header">
        <h2 class="section-title">Định Hình Lại Tủ Đồ Của Bạn</h2>
        <div class="category-filters">
          <a href="#" class="active">Váy</a>
          <a href="#">Nam</a>
          <a href="#">Áo thun</a>
          <a href="#">Nữ</a>
        </div>
      </div>

      <div class="product-grid">
        <!-- Product 1 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <span class="discount-badge">40%</span>
            <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop" alt="Váy midi tulle bất đối xứng" class="product-image">
            <div class="product-actions-top-right">
              <button class="icon-action-btn" aria-label="Yêu thích">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
              <button class="icon-action-btn" aria-label="Xem nhanh">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
            </div>
            <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
          </div>
          <div class="product-info">
            <h3 class="product-name">Váy midi tulle bất đối xứng</h3>
            <div class="product-price">
              <span class="current-price">$30.00</span>
              <span class="original-price">$50.00</span>
            </div>
          </div>
        </div>

        <!-- Product 2 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?q=80&w=800&auto=format&fit=crop" alt="Váy midi hiệu ứng nhăn" class="product-image">
            <div class="product-actions-top-right">
              <button class="icon-action-btn" aria-label="Yêu thích">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
              <button class="icon-action-btn" aria-label="Xem nhanh">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
            </div>
            <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
          </div>
          <div class="product-info">
            <h3 class="product-name">Váy midi hiệu ứng nhăn</h3>
            <div class="product-price">
              <span class="current-price">$34.11</span>
            </div>
          </div>
        </div>

        <!-- Product 3 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <span class="discount-badge">19%</span>
            <img src="https://images.unsplash.com/photo-1515347619253-122e1b1d403f?q=80&w=800&auto=format&fit=crop" alt="Váy quây xếp nếp" class="product-image">
            <div class="product-actions-top-right">
              <button class="icon-action-btn" aria-label="Yêu thích">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
              <button class="icon-action-btn" aria-label="Xem nhanh">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
            </div>
            <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
          </div>
          <div class="product-info">
            <h3 class="product-name">Váy quây xếp nếp</h3>
            <div class="product-price">
              <span class="current-price">$54.33</span>
              <span class="original-price">$66.99</span>
            </div>
          </div>
        </div>

        <!-- Product 4 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <span class="discount-badge">22%</span>
            <img src="https://images.unsplash.com/photo-1612336307429-8a898d10e223?q=80&w=800&auto=format&fit=crop" alt="Váy midi crepe họa tiết hoa" class="product-image">
            <div class="product-actions-top-right">
              <button class="icon-action-btn" aria-label="Yêu thích">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
              <button class="icon-action-btn" aria-label="Xem nhanh">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
            </div>
            <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
          </div>
          <div class="product-info">
            <h3 class="product-name">Váy midi crepe họa tiết hoa</h3>
            <div class="product-price">
              <span class="current-price">$61.32</span>
              <span class="original-price">$78.33</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Banners Section -->
    <div class="category-banners">
      <div class="banner women-banner">
        <div class="banner-content">
          <h3>NỮ</h3>
          <p>Sức mạnh mềm mại, chi tiết tinh tế, và hình dáng tự tin được định hình cho phong cách đương đại.</p>
        </div>
      </div>
      <div class="banner men-banner">
        <div class="banner-content">
          <h3>NAM</h3>
          <p>Cấu trúc rõ ràng, phong thái hiện đại, và những thiết kế thoải mái dành cho sự tự tin mỗi ngày.</p>
        </div>
      </div>
    </div>

    <!-- Elegant Quote Section -->
    <div class="quote-section">
      <div class="quote-content">
        <h2 class="elegant-quote">
          "Nâng tầm phong cách của bạn với vẻ thanh lịch vượt thời gian trong bộ sưu tập mới."
        </h2>
        <div class="quote-decoration"></div>
      </div>
    </div>

    </div>

    <!-- Featured Products Section -->
    <div class="product-section featured-products-section">
      <div class="section-header">
        <h2 class="section-title">Sản Phẩm Nổi Bật</h2>
        <div class="category-filters">
          <a href="#" class="active">Áo khoác</a>
          <a href="#">Nam</a>
          <a href="#">Áo</a>
          <a href="#">Nữ</a>
        </div>
      </div>

      <div class="carousel-wrapper">
        <button class="carousel-nav-btn left" aria-label="Trước">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        
        <div class="product-grid carousel-grid">
          <!-- Product 1 -->
          <div class="product-card">
            <div class="product-image-wrapper">
              <span class="discount-badge">26%</span>
              <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=800&auto=format&fit=crop" alt="Áo hoodie Run & Brunch" class="product-image">
              <div class="product-actions-top-right">
                <button class="icon-action-btn" aria-label="Yêu thích">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
                <button class="icon-action-btn" aria-label="Xem nhanh">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
              </div>
              <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
            </div>
            <div class="product-info">
              <h3 class="product-name">Áo hoodie 'Run & Brunch'</h3>
              <div class="product-price">
                <span class="current-price">$59.99</span>
                <span class="original-price">$79.99</span>
              </div>
              <div class="product-rating">
                <div class="stars">
                  <span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star half-filled">★</span>
                </div>
                <span class="rating-value">4.67 (3)</span>
              </div>
            </div>
          </div>

          <!-- Product 2 -->
          <div class="product-card">
            <div class="product-image-wrapper">
              <span class="discount-badge">28%</span>
              <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop" alt="Áo khoác cotton siêu nhẹ" class="product-image">
              <div class="product-actions-top-right">
                <button class="icon-action-btn" aria-label="Yêu thích">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
                <button class="icon-action-btn" aria-label="Xem nhanh">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
              </div>
              <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
            </div>
            <div class="product-info">
              <h3 class="product-name">Áo khoác cotton siêu nhẹ</h3>
              <div class="product-price">
                <span class="current-price">$24.90</span>
                <span class="original-price">$34.33</span>
              </div>
              <div class="product-rating">
                <div class="stars">
                  <span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star half-filled">★</span>
                </div>
                <span class="rating-value">4.33 (3)</span>
              </div>
            </div>
          </div>

          <!-- Product 3 -->
          <div class="product-card">
            <div class="product-image-wrapper">
              <span class="discount-badge">40%</span>
              <img src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop" alt="Áo khoác denim" class="product-image">
              <div class="product-actions-top-right">
                <button class="icon-action-btn" aria-label="Yêu thích">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
                <button class="icon-action-btn" aria-label="Xem nhanh">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
              </div>
              <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
            </div>
            <div class="product-info">
              <h3 class="product-name">Áo khoác denim</h3>
              <div class="product-price">
                <span class="current-price">$44.96</span>
                <span class="original-price">$74.93</span>
              </div>
              <div class="product-rating">
                <div class="stars">
                  <span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star half-filled">★</span><span class="star empty">★</span>
                </div>
                <span class="rating-value">3.67 (5)</span>
              </div>
            </div>
          </div>

          <!-- Product 4 -->
          <div class="product-card">
            <div class="product-image-wrapper">
              <img src="https://images.unsplash.com/photo-1599305090598-fe179d501227?q=80&w=800&auto=format&fit=crop" alt="Áo khoác bomber 100% linen" class="product-image">
              <div class="product-actions-top-right">
                <button class="icon-action-btn" aria-label="Yêu thích">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
                <button class="icon-action-btn" aria-label="Xem nhanh">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
              </div>
              <button class="add-to-cart-btn">THÊM VÀO GIỎ HÀNG</button>
            </div>
            <div class="product-info">
              <h3 class="product-name">Áo khoác bomber 100% linen</h3>
              <div class="product-price">
                <span class="current-price">$34.76 - $19.66</span>
              </div>
              <div class="product-rating">
                <div class="stars">
                  <span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star filled">★</span><span class="star half-filled">★</span>
                </div>
                <span class="rating-value">4.33 (6)</span>
              </div>
            </div>
          </div>

        </div>
        
        <button class="carousel-nav-btn right" aria-label="Tiếp theo">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>

      <div class="carousel-dots-bottom">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
      </div>
    </div>

    <!-- Eco Fashion Section -->
    <div class="eco-fashion-section">
      <div class="eco-content">
        <h2 class="eco-title">THỜI TRANG YÊU<br>THƯƠNG TRÁI ĐẤT</h2>
        <p class="eco-desc">Được thiết kế có trách nhiệm cho cuộc sống hàng ngày. Những đường nét tinh tế, chất liệu tự nhiên, và phong cách trường tồn vượt thời gian.</p>
        <button class="eco-btn">KHÁM PHÁ NGAY</button>
      </div>
      <div class="eco-images">
        <div class="eco-img-main">
          <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=800&auto=format&fit=crop" alt="Áo khoác thời trang">
        </div>
        <div class="eco-img-secondary">
          <img src="https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?q=80&w=800&auto=format&fit=crop" alt="Áo thun cơ bản">
        </div>
      </div>
    </div>

    <!-- Blog Section -->
    <div class="blog-section">
      <div class="blog-grid">
        <!-- Post 1 -->
        <article class="blog-card">
          <div class="blog-image-wrapper">
            <img src="https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=600&auto=format&fit=crop" alt="Đồng hồ trên tay">
          </div>
          <div class="blog-content">
            <span class="blog-category">MẸO THỜI TRANG</span>
            <h3 class="blog-title"><a href="#">Trò Chuyện Về Thời Trang Bền Vững Với Một Influencer Thân Thiện Môi Trường</a></h3>
            <span class="blog-meta">bởi Simam, 9 tháng 7, 2025</span>
          </div>
        </article>

        <!-- Post 2 -->
        <article class="blog-card">
          <div class="blog-image-wrapper">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600&auto=format&fit=crop" alt="Người phụ nữ đội nón">
          </div>
          <div class="blog-content">
            <span class="blog-category">MẸO THỜI TRANG</span>
            <h3 class="blog-title"><a href="#">Những Trang Phục Đáng Nhớ Nhất Từ Thảm Đỏ Oscar</a></h3>
            <span class="blog-meta">bởi Simam, 9 tháng 7, 2025</span>
          </div>
        </article>

        <!-- Post 3 -->
        <article class="blog-card">
          <div class="blog-image-wrapper">
            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?q=80&w=600&auto=format&fit=crop" alt="Cô gái tươi cười">
          </div>
          <div class="blog-content">
            <span class="blog-category">XU HƯỚNG MỚI</span>
            <h3 class="blog-title"><a href="#">Những Phong Cách Street Style Đẹp Nhất Từ Tuần Lễ Thời Trang London</a></h3>
            <span class="blog-meta">bởi Simam, 9 tháng 7, 2025</span>
          </div>
        </article>

        <!-- Post 4 -->
        <article class="blog-card">
          <div class="blog-image-wrapper">
            <img src="https://images.unsplash.com/photo-1492447166138-50c3889f1b1a?q=80&w=600&auto=format&fit=crop" alt="Chân dung cô gái">
          </div>
          <div class="blog-content">
            <span class="blog-category">MẸO THỜI TRANG</span>
            <h3 class="blog-title"><a href="#">Nhìn Lại Những Lễ Trao Giải Thời Trang Có Ảnh Hưởng Nhất</a></h3>
            <span class="blog-meta">bởi Simam, 9 tháng 7, 2025</span>
          </div>
        </article>
      </div>
    </div>
  
</template>

<script setup>
// Khai báo logic (nếu có) sau này
</script>
