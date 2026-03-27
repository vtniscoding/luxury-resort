<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel - Dịch vụ & Tiện ích</title>

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
    <link rel="stylesheet" href="./assets/css/components/chatbox.css">
    <link rel="stylesheet" href="./assets/css/pages/services.css">
</head>

<body>
    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. HEADER PHỤ -->
    <header class="header-sub"
        style="background: linear-gradient(to right, rgba(19, 26, 34, 0.95) 0%, rgba(26, 37, 47, 0.8) 40%, rgba(26, 37, 47, 0.3) 100%), url('https://images.unsplash.com/photo-1544148103-0773bf10d330?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;">
        <div class="container position-relative" style="z-index: 2;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb luxury-breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dịch vụ & Tiện ích</li>
                </ol>
            </nav>
            <h1 class="main-title">Trải Nghiệm Hoàn Mỹ</h1>
            <div class="divider"></div>
            <p class="desc">Đánh thức mọi giác quan của bạn với hệ thống nhà hàng đẳng cấp, dịch vụ spa trị liệu chuyên
                sâu và hồ bơi vô cực ngắm nhìn đại dương.</p>
        </div>
    </header>

    <!-- 3. NỘI DUNG CHÍNH (CÁC DỊCH VỤ) -->
    <main class="luxury-service-section bg-light py-5">
        <div class="container py-5">
            <!-- DỊCH VỤ 1: NHÀ HÀNG -->
            <div class="row service-item align-items-center mb-5 pb-5">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="service-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1667295805729-e2378f6ec0ef?auto=format&fit=crop&w=1200&q=80"
                            alt="Nhà hàng Luxury" class="service-img">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="service-content-box ms-lg-n5">
                        <div class="service-pre-title">Tinh hoa ẩm thực</div>
                        <h3 class="service-title">Nhà Hàng & Bar Thượng Hạng</h3>
                        <p class="service-desc">Thưởng thức tinh hoa ẩm thực Á - Âu được chuẩn bị bởi các đầu bếp danh
                            tiếng. Không gian lãng mạn cùng quầy bar sân thượng mang đến tầm nhìn toàn cảnh thành phố
                            rực rỡ về đêm, lý tưởng cho những buổi tối hẹn hò hay gặp gỡ đối tác.</p>
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-clock-history text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Giờ phục vụ</h6>
                                    <p class="text-muted small mb-0">6:00 Sáng - 10:30 Tối</p>
                                </div>
                            </div>
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-geo-alt text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Vị trí</h6>
                                    <p class="text-muted small mb-0">Tầng trệt, hướng biển</p>
                                </div>
                            </div>
                        </div>
                        <a href="menu.php" class="btn-custom btn-primary-theme rounded-pill px-4 py-2 mt-2">Xem thực
                            đơn <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <!-- DỊCH VỤ 2: SPA -->
            <div class="row service-item align-items-center mb-5 pb-5 flex-lg-row-reverse">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="service-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=80"
                            alt="Spa & Massage" class="service-img">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="service-content-box me-lg-n5">
                        <div class="service-pre-title">Thư giãn tuyệt đối</div>
                        <h3 class="service-title">Lotus Spa & Wellness</h3>
                        <p class="service-desc">Thả lỏng cơ thể và tâm trí với các liệu trình massage thảo dược truyền
                            thống. Không gian yên tĩnh, âm nhạc du dương và tinh dầu dịu nhẹ sẽ đánh thức mọi giác quan,
                            trả lại cho bạn nguồn năng lượng tươi mới nhất.</p>
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-clock-history text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Giờ phục vụ</h6>
                                    <p class="text-muted small mb-0">9:00 Sáng - 9:00 Tối</p>
                                </div>
                            </div>
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-geo-alt text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Vị trí</h6>
                                    <p class="text-muted small mb-0">Tầng 3 (Khu vực yên tĩnh)</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn-custom btn-primary-theme rounded-pill px-4 py-2 mt-2">Đặt lịch Spa <i
                                class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <!-- DỊCH VỤ 3: HỒ BƠI -->
            <div class="row service-item align-items-center mb-5">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="service-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1586611292717-f828b167408c?auto=format&fit=crop&w=1200&q=80"
                            alt="Hồ bơi vô cực" class="service-img">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="service-content-box ms-lg-n5">
                        <div class="service-pre-title">Khu vực ngoài trời</div>
                        <h3 class="service-title">Hồ Bơi Vô Cực Panorama</h3>
                        <p class="service-desc">Đắm mình trong làn nước trong xanh tại hồ bơi vô cực lớn nhất khu vực.
                            Đây là điểm check-in hoàn hảo để bạn đón bình minh rực rỡ và thưởng thức cocktail nhiệt đới
                            ngay trên những chiếc ghế dài tắm nắng sang chảnh.</p>
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-clock-history text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Giờ phục vụ</h6>
                                    <p class="text-muted small mb-0">6:00 Sáng - 8:00 Tối</p>
                                </div>
                            </div>
                            <div class="info-item d-flex gap-3">
                                <i class="bi bi-geo-alt text-primary-theme fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-secondary">Vị trí</h6>
                                    <p class="text-muted small mb-0">Tầng thượng (Rooftop)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 5. FOOTER -->
    <?php include './includes/footer.php'; ?>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/components/chatbox.js"></script>
</body>

</html>