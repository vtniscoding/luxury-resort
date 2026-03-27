<!doctype html>
<html lang="vi">

<head>
    <!-- Thiết lập cơ bản -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel - Trải nghiệm nghỉ dưỡng đẳng cấp</title>

    <!-- Thư viện CSS & Fonts từ CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@300;400;500;700&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- File CSS hệ thống -->
    <link rel="stylesheet" href="./assets/css/base/global.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/components/footer.css">
    <link rel="stylesheet" href="./assets/css/components/hero-slider.css">
    <link rel="stylesheet" href="./assets/css/components/room-swiper.css">
    <link rel="stylesheet" href="./assets/css/components/room-card.css">
    <link rel="stylesheet" href="./assets/css/components/promo-widget.css">
    <link rel="stylesheet" href="./assets/css/components/chatbox.css">
</head>

<body>
    <!-- Import Header dùng chung -->
    <?php include './includes/header.php'; ?>

    <!-- Khu vực Hero Slider tổng -->
    <section class="slider-section">
        <div class="slider-overlay"></div>
        <div id="hotelSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
            
            <!-- Chấm chỉ mục Slider -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#hotelSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#hotelSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#hotelSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            
            <!-- Nội dung các Slide -->
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1445019980597-93fa8acb246c?q=80&w=2000&auto=format&fit=crop" alt="Luxury Hotel">
                    <div class="carousel-caption">
                        <p class="slider-slogan">Chào mừng đến với sự hoàn mỹ</p>
                        <h1 class="slider-title">Định Nghĩa Lại<br>Sự Sang Trọng</h1>
                        <div class="slider-btn-wrap">
                            <a href="#explore" class="btn-slider">Khám Phá Ngay</a>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2000&auto=format&fit=crop" alt="Ocean View Room">
                    <div class="carousel-caption">
                        <p class="slider-slogan">Tầm nhìn hướng biển tuyệt đẹp</p>
                        <h1 class="slider-title">Trải Nghiệm<br>Không Gian Vô Tận</h1>
                        <div class="slider-btn-wrap">
                            <a href="rooms.php" class="btn-slider">Xem Phòng</a>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=2000&q=80" alt="Fine Dining">
                    <div class="carousel-caption">
                        <p class="slider-slogan">Ẩm thực tinh hoa</p>
                        <h1 class="slider-title">Đánh Thức<br>Mọi Giác Quan</h1>
                        <div class="slider-btn-wrap">
                            <a href="services.php" class="btn-slider">Đặt Bàn Ngay</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nút điều khiển Slider (Trái/Phải) -->
            <button class="carousel-control-prev" type="button" data-bs-target="#hotelSlider" data-bs-slide="prev">
                <i class="bi bi-chevron-left control-icon"></i>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#hotelSlider" data-bs-slide="next">
                <i class="bi bi-chevron-right control-icon"></i>
            </button>
        </div>

        <!-- Khối Widget Khuyến mãi nổi trên Slider -->
        <div class="promo-widget-container">
            <div class="promo-widget">
                <div class="promo-content">
                    <div class="promo-badge">
                        <span class="small text-uppercase fw-bold" style="letter-spacing: 2px;">Mùa Hè</span>
                        <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">-20%</h2>
                    </div>
                    <div class="promo-divider d-none d-md-block"></div>
                    <div class="promo-text">
                        <h4 class="mb-1" style="font-family: 'Playfair Display', serif; color: var(--secondary-color);">Kỳ Nghỉ Trọn Vẹn</h4>
                        <p class="mb-0 text-muted small pe-3">Tận hưởng ưu đãi độc quyền khi đặt phòng Suite. Miễn phí Spa và đưa đón sân bay.</p>
                    </div>
                </div>
                <div class="promo-action">
                    <a href="booking.php" class="btn-custom btn-primary-theme px-4 py-3 rounded-pill shadow-sm fs-6 text-nowrap">
                        Đặt phòng ngay <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Khu vực Nội dung chính -->
    <main id="explore">
        
        <!-- Danh sách các Dịch vụ nổi bật -->
        <section class="container pt-5 pb-4 mt-2">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <i class="bi bi-cup-hot amenity-icon"></i>
                    <h5 class="fw-bold" style="color: var(--secondary-color);">Ẩm Thực Tinh Hoa</h5>
                    <p class="text-muted small px-3">Thưởng thức thực đơn đa dạng được chuẩn bị bởi các đầu bếp sao Michelin.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-water amenity-icon"></i>
                    <h5 class="fw-bold" style="color: var(--secondary-color);">Hồ Bơi Vô Cực</h5>
                    <p class="text-muted small px-3">Thư giãn tuyệt đối tại hồ bơi tràn viền với tầm nhìn panorama ôm trọn đại dương.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-leaf amenity-icon"></i>
                    <h5 class="fw-bold" style="color: var(--secondary-color);">Spa & Wellness</h5>
                    <p class="text-muted small px-3">Tái tạo năng lượng với các liệu pháp massage truyền thống và hiện đại.</p>
                </div>
            </div>
        </section>

        <!-- Danh sách Hạng phòng (Swiper render bằng JS) -->
        <section class="py-5">
            <div class="container">
                <div class="text-center">
                    <h2 class="luxury-title">Không gian nghỉ dưỡng cao cấp</h2>
                    <p class="text-muted mt-4">Sự kết hợp hoàn hảo giữa kiến trúc đương đại và nét đẹp bản địa</p>
                </div>

                <!-- Container chờ nạp dữ liệu từ API qua JS -->
                <div id="homeRoomSwiperContainer" class="pb-5">
                    <div class="text-center w-100 py-5">
                        <div class="spinner-border text-primary-theme" role="status"></div>
                        <p class="mt-2 text-muted">Đang tải danh sách phòng...</p>
                    </div>
                </div>

                <!-- Nút chuyển sang trang danh sách phòng -->
                <div class="text-center">
                    <button class="btn-custom btn-outline-theme px-5 py-3 rounded-pill" onclick="window.location.href='rooms.php'">Xem toàn bộ phòng</button>
                </div>
            </div>
        </section>
    </main>


    <!-- Scripts thư viện Bootstrap & Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Scripts chức năng tự viết -->
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/components/chatbox.js"></script>
    <script src="./assets/js/pages/room-info.js"></script>
</body>

</html>