<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel - Phòng & Giá</title>

    <!-- Thư viện & Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!-- Bổ sung thư viện Swiper CSS để đồng bộ với file JS dùng chung -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@300;400;500;700&family=Cormorant+Garamond:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/base/global.css">
    <link rel="stylesheet" href="./assets/css/components/slider.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/components/footer.css">
    <link rel="stylesheet" href="./assets/css/components/room-card.css">
    <link rel="stylesheet" href="./assets/css/components/chatbox.css">

    <style>
        /* Tối ưu hiệu ứng mượt mà khi load nội dung */
        #featuredRoomContainer, #roomListContainer {
            transition: all 0.5s ease-in-out;
            min-height: 400px; /* Chống giật trang khi chờ load API */
        }
    </style>
</head>

<body>
    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. HEADER PHỤ (HERO BANNER) -->
    <header class="header-sub"
        style="background: linear-gradient(to right, rgba(19, 26, 34, 0.95) 0%, rgba(26, 37, 47, 0.8) 40%, rgba(26, 37, 47, 0.1) 100%), url('https://images.unsplash.com/photo-1584132905271-512c958d674a?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;">
        <div class="container relative z-index-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb luxury-breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Phòng & Giá</li>
                </ol>
            </nav>
            <h1 class="main-title">Khám Phá Các<br>Loại Phòng</h1>
            <div class="divider"></div>
            <p class="desc">Tận hưởng không gian nghỉ dưỡng sang trọng, được thiết kế tinh tế với tầm nhìn tuyệt đẹp và
                tiện nghi đẳng cấp 5 sao.</p>
        </div>
    </header>

    <!-- 3. NỘI DUNG CHÍNH (DANH SÁCH PHÒNG) -->
    <main class="py-5 bg-light">

        <!-- Vùng hiển thị thông tin Phòng Nổi Bật (Hoặc Phòng được chọn từ URL) -->
        <section class="container mb-5 pb-4" id="featuredRoomContainer">
            <div class="text-center w-100 py-5">
                <div class="spinner-border text-primary-theme" style="width: 3rem; height: 3rem;" role="status"></div>
                <p class="mt-3 text-muted" style="letter-spacing: 1px;">Đang tải thông tin phòng...</p>
            </div>
        </section>

        <!-- Vùng hiển thị Lưới Danh sách các phòng khác -->
        <section class="container py-5 border-top border-secondary-subtle">
            <!-- BỔ SUNG ID 'otherRoomsTitle' ĐỂ JS ĐIỀU KHIỂN -->
            <h3 id="otherRoomsTitle" class="mb-5 luxury-title text-center" style="font-size: 2rem;">Danh Sách Các Hạng Phòng</h3>
            
            <div class="row g-5" id="roomListContainer">
                <!-- Dữ liệu phòng sẽ được JS render vào đây -->
            </div>
        </section>

    </main>

    <!-- 4. FOOTER (Đã bao gồm Chat Box bên trong) -->
    <?php include './includes/footer.php'; ?>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    
    <!-- Bổ sung thư viện Swiper JS để đồng bộ với file JS dùng chung -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/components/chatbox.js"></script>
    <script src="./assets/js/pages/room-info.js"></script>

</body>

</html>