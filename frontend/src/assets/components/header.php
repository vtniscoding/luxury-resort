<?php
// Lấy tên file hiện tại để set ative menu tương ứng
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="bi bi-flower1 fs-3 me-2"></i>
            <span class="d-flex flex-column justify-content-center" style="line-height: 1;">
                <span class="fw-bold"
                    style="font-size: 1.4rem; letter-spacing: 3.5px; font-family: 'Cinzel', serif;">LUXURY</span>
                <span class="fw-light text-white"
                    style="font-size: 0.6rem; letter-spacing: 4px; font-family: 'Montserrat';">RESORT & SPA</span>
            </span>
        </a>
        <!-- Nút menu cho mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Điều hướng -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-lg-3">
                <li class="nav-item"><a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>"
                        href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current_page == 'rooms.php') ? 'active' : '' ?>"
                        href="rooms.php">Phòng & Giá</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current_page == 'booking.php') ? 'active' : '' ?>"
                        href="booking.php">Đặt Phòng</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current_page == 'services.php') ? 'active' : '' ?>"
                        href="services.php">Dịch vụ</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0" id="authSection">
                    <!-- JS sẽ điền nút đăng nhập / dropdown vào đây -->
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: rgba(26, 37, 47, 0.98) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .navbar-brand {
        color: var(--primary-color) !important;
    }

    .nav-link {
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.7) !important;
        transition: 0.3s;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--primary-color) !important;
    }

    /* Định dạng chữ Logo */
    .brand-text {
        letter-spacing: 2px;
        font-size: 1.5rem;
    }

    .brand-text .light {
        font-weight: 300;
    }

    .brand-text .bold {
        font-weight: 700;
    }

    /* Nút Toggle menu trên Mobile */
    .navbar-toggler {
        border: none;
        padding: 0;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }


    /* --- 2. HEADER PHỤ (Dùng cho các trang con) --- */
    .header-sub {
        position: relative;
        background: linear-gradient(to right, rgba(19, 26, 34, 0.95) 0%, rgba(26, 37, 47, 0.8) 40%, rgba(26, 37, 47, 0.1) 100%);
        padding: 180px 0 100px 0;
        color: white;
        border-bottom: 3px solid var(--primary-color);
    }

    .header-sub .main-title {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .header-sub .divider {
        width: 80px;
        height: 3px;
        background-color: var(--primary-color);
        margin-bottom: 20px;
    }

    .header-sub .desc {
        font-size: 1rem;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
    }



    /* --- 3. BREADCRUMB (Thanh điều hướng phân cấp trang) --- */
    .luxury-breadcrumb {
        letter-spacing: 1.5px;
        font-size: small;
    }

    .luxury-breadcrumb .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: 0.3s;
    }

    .luxury-breadcrumb .breadcrumb-item a:hover {
        color: var(--primary-color);
    }

    .luxury-breadcrumb .breadcrumb-item.active {
        color: var(--primary-color);
    }

    .luxury-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        color: rgba(255, 255, 255, 0.3);
        font-size: 1.4rem;
        vertical-align: middle;
        line-height: 0.5;
    }


    /* --- 4. RESPONSIVE (Cho điện thoại) --- */
    @media (max-width: 991px) {

        /* Khung menu đổ xuống trên mobile */
        .navbar-collapse {
            background-color: var(--secondary-color);
            padding: 20px;
            border-radius: var(--radius-md);
            margin-top: 15px;
            box-shadow: var(--shadow-sm);
        }

        /* Thu nhỏ Header phụ trên Mobile */
        .header-sub {
            padding: 140px 0 60px 0;
            background: rgba(26, 37, 47, 0.95);
        }

        .header-sub .main-title {
            font-size: 2.5rem;
        }

        .header-sub .desc {
            font-size: 0.9rem;
        }

        /* Thu nhỏ avatar và ẩn dropdown */
        .navbar .dropdown-toggle img.rounded-circle {
            width: 35px !important;
            height: 35px !important;
            object-fit: cover;
        }

        .navbar .dropdown-toggle::after {
            display: none !important;
        }

    }

    @media (max-width: 576px) {

        /* Thu nhỏ logo */
        .navbar-brand i {
            font-size: 1.1rem !important;
            line-height: 1;
        }

        .navbar-brand .fw-bold {
            font-size: 1.1rem !important;
            letter-spacing: 2.5px;
        }

        .navbar-brand .fw-light {
            font-size: 0.55rem !important;
        }

    }
</style>