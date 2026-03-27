<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel - Nhà Hàng & Bar</title>

    <!-- Thư viện & Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@300;400;500;700&family=Cormorant+Garamond:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/base/global.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/components/footer.css">
    <link rel="stylesheet" href="./assets/css/pages/menu.css">
    <link rel="stylesheet" href="./assets/css/components/chatbox.css">
</head>

<body>
    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. HEADER PHỤ -->
    <header class="header-sub"
        style="background: linear-gradient(to right, rgba(19, 26, 34, 0.95) 0%, rgba(26, 37, 47, 0.8) 40%, rgba(26, 37, 47, 0.1) 100%), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;">
        <div class="container position-relative" style="z-index: 2;">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb luxury-breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="services.html">Dịch vụ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thực đơn</li>
                </ol>
            </nav>
            <h1 class="main-title">Thực Đơn Phục Vụ<br>& Quầy Bar</h1>
            <div class="divider"></div>
            <p class="desc">Trải nghiệm hành trình ẩm thực tinh hoa, đánh thức mọi giác quan trong không gian vô cùng
                hoa lệ và lãng mạn.</p>
        </div>
    </header>

    <!-- 3. GIỚI THIỆU KHÔNG GIAN (ZIC-ZAC LAYOUT) -->
    <main class="py-5 bg-white">
        <div class="container py-5">
            <!-- Khu vực 1: Nhà hàng Royal -->
            <div class="row align-items-center mb-5 pb-5 border-bottom">
                <div class="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
                    <h5 class="text-uppercase mb-2 fw-bold"
                        style="color: var(--primary-color); letter-spacing: 3px; font-size: 0.9rem;">Fine Dining</h5>
                    <h2 class="luxury-title mb-4" style="text-align: left;">Nhà Hàng La Royale</h2>
                    <p class="text-muted mb-4">
                        La Royale mang đến một không gian ẩm thực sang trọng bậc nhất với thực đơn được thiết kế bởi các
                        đầu bếp sao Michelin. Sự kết hợp hoàn hảo giữa nguyên liệu thượng hạng nhập khẩu và nét tinh tế
                        của ẩm thực địa phương sẽ mang lại cho thực khách một bữa tối không thể nào quên.
                    </p>
                    <ul class="list-unstyled text-muted small mb-0">
                        <li class="mb-2"><i class="bi bi-clock text-primary-theme me-2"></i> Giờ mở cửa: 06:00 - 22:30
                        </li>
                        <li class="mb-2"><i class="bi bi-geo-alt text-primary-theme me-2"></i> Vị trí: Tầng trệt, Tòa
                            nhà trung tâm</li>
                        <li class="mb-2"><i class="bi bi-check2-square text-primary-theme me-2"></i> Phong cách: Á - Âu
                            đương đại</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="service-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80"
                            alt="" class="img-fluid w-100">
                    </div>
                </div>
            </div>
            <!-- Khu vực 2: Sky Bar -->
            <div class="row align-items-center pt-3">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="service-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=800&q=80"
                            alt="" class="img-fluid w-100">
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0 ps-lg-5">
                    <h5 class="text-uppercase mb-2 fw-bold"
                        style="color: var(--primary-color); letter-spacing: 3px; font-size: 0.9rem;">Chill & Relax</h5>
                    <h2 class="luxury-title mb-4" style="text-align: left;">Horizon Sky Bar</h2>
                    <p class="text-muted mb-4">
                        Tọa lạc tại tầng cao nhất của khách sạn, Horizon Sky Bar là điểm đến lý tưởng để thưởng thức
                        những ly cocktail trứ danh mang đậm dấu ấn sáng tạo của các Bartender chuyên nghiệp. Hòa mình
                        vào âm nhạc du dương và ngắm trọn vẹn thành phố lung linh về đêm.
                    </p>
                    <ul class="list-unstyled text-muted small mb-0">
                        <li class="mb-2"><i class="bi bi-clock text-primary-theme me-2"></i> Giờ mở cửa: 16:00 - 01:00
                            sáng</li>
                        <li class="mb-2"><i class="bi bi-geo-alt text-primary-theme me-2"></i> Vị trí: Tầng thượng
                            (Rooftop)</li>
                        <li class="mb-2"><i class="bi bi-music-note-beamed text-primary-theme me-2"></i> Live Acoustic:
                            Thứ 6 & Thứ 7 hàng tuần</li>
                    </ul>
                </div>
            </div>

        </div>
    </main>

    <!-- 4. THỰC ĐƠN NỔI BẬT (HIGHLIGHT MENU) -->
    <section class="py-5" style="background-color: #fcfbf9;">
        <div class="container py-4">
            <div class="text-center mb-5 pb-2">
                <h5 class="text-uppercase mb-2 fw-bold"
                    style="color: var(--primary-color); letter-spacing: 3px; font-size: 0.9rem;">Khám Phá</h5>
                <h2 class="luxury-title">Thực Đơn Điển Hình</h2>
                <p class="text-muted mt-3">Những món ăn và thức uống làm nên tên tuổi của Luxury Hotel</p>
            </div>
            <div class="row g-5">
                <!-- Cột Món Chính -->
                <div class="col-lg-6">
                    <h4 class="menu-category-title"><i class="bi bi-star me-2"></i> Món Chính (Main Courses)</h4>
                    <ul class="menu-list">
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Thăn Bò Wagyu A5 (Striploin)</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">2.450.000₫</h5>
                            </div>
                            <p class="menu-desc">Bò Wagyu áp chảo, măng tây, khoai tây nghiền nấm Truffle & sốt tiêu
                                đen.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Tôm Hùm Alaska Bỏ Lò</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">1.850.000₫</h5>
                            </div>
                            <p class="menu-desc">Tôm hùm nướng phô mai Parmesan ăn kèm salad hữu cơ và bánh mì tỏi.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Cá Hồi Na Uy Áp Chảo</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">950.000₫</h5>
                            </div>
                            <p class="menu-desc">Cá hồi dùng kèm xốt kem chanh leo, trứng cá muối caviar và măng tây
                                nướng.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Ức Vịt Pháp Sốt Cam</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">720.000₫</h5>
                            </div>
                            <p class="menu-desc">Ức vịt nướng chín mềm, sốt cam vàng Grand Marnier, khoai lang sấy.</p>
                        </li>
                    </ul>
                </div>
                <!-- Cột Đồ Uống -->
                <div class="col-lg-6">
                    <h4 class="menu-category-title"><i class="bi bi-cup-straw me-2"></i> Cocktails & Rượu Vang</h4>
                    <ul class="menu-list">
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Horizon Signature Cocktail</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">350.000₫</h5>
                            </div>
                            <p class="menu-desc">Gin, Rượu vải, Nước ép dứa, Syrup hoa hồng, Khói sương mù.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Classic Dry Martini</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">280.000₫</h5>
                            </div>
                            <p class="menu-desc">London Dry Gin, Dry Vermouth, Olive xanh nguyên hạt.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Château Margaux 2015 (Chai)</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">28.000.000₫</h5>
                            </div>
                            <p class="menu-desc">Rượu vang đỏ thượng hạng từ vùng Bordeaux, Pháp. Hương vị mạnh mẽ, nồng
                                nàn.</p>
                        </li>
                        <li>
                            <div class="menu-item">
                                <h5 class="menu-title">Moët & Chandon Rosé (Ly)</h5>
                                <div class="menu-dots"></div>
                                <h5 class="menu-price">650.000₫</h5>
                            </div>
                            <p class="menu-desc">Champagne sủi bọt ánh hồng, hương dâu tây và hoa hồng phai.</p>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="text-center mt-5">
                <button class="btn-custom btn-outline-theme px-5 py-3 rounded-pill"
                    onclick="alert('Đang tải thực đơn PDF...')"><i class="bi bi-file-earmark-pdf me-2"></i> Tải Full
                    Thực Đơn</button>
            </div>
        </div>
    </section>

    <!-- 4. FOOTER -->
    <?php include './includes/footer.php'; ?>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/components/chatbox.js"></script>
</body>

</html>