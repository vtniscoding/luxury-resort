<!-- FOOTER -->
<footer class="footer-custom">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <a class="navbar-brand d-flex align-items-center" href="index.html"
                    style="color: var(--primary-color);">
                    <i class="bi bi-flower1 fs-3 me-2"></i>
                    <span class="d-flex flex-column justify-content-center" style="line-height: 1;">
                        <span class="fw-bold"
                            style="font-size: 1.4rem; letter-spacing: 3.5px; font-family: 'Cinzel', serif;">LUXURY</span>
                        <span class="fw-light text-white"
                            style="font-size: 0.6rem; letter-spacing: 4px; font-family: 'Montserrat';">RESORT &
                            SPA</span>
                    </span>
                </a>
                <p class="text-muted small mt-4">Tự hào là điểm đến lý tưởng cho những kỳ nghỉ trọn vẹn. Chúng tôi
                    mang đến không gian kiến trúc độc bản, dịch vụ tận tâm và những trải nghiệm vượt xa sự mong đợi.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-muted fs-5 footer-social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-muted fs-5 footer-social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-muted fs-5 footer-social-icon"><i class="bi bi-globe"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mt-2 text-white text-uppercase">Khám phá</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.html" class="text-muted small">Trang chủ</a></li>
                    <li class="mb-2"><a href="rooms.html" class="text-muted small">Phòng & Giá</a></li>
                    <li class="mb-2"><a href="services.html" class="text-muted small">Dịch vụ</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mt-2 text-white text-uppercase" style="letter-spacing: 1px;">Liên hệ</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt me-3 fs-5" style="color: var(--primary-color);"></i>
                        <span>123 Đường Biển, Quận Sơn Trà,<br>Thành phố Nghỉ Dưỡng, VN</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-telephone me-3 fs-5" style="color: var(--primary-color);"></i>
                        <span>+84 1900 1234</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope me-3 fs-5" style="color: var(--primary-color);"></i>
                        <span>reservation@luxuryhotel.com</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row align-items-center border-top border-secondary mt-5 pt-4">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="text-muted small mb-0">&copy; 2026 Luxury Resort & Spa. Bản quyền thuộc về Luxury Group.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex gap-3 justify-content-center justify-content-md-end text-white opacity-75 fs-4">
                    <i class="bi bi-credit-card" title="Visa / Mastercard"></i>
                    <i class="bi bi-paypal" title="PayPal"></i>
                    <i class="bi bi-wallet2" title="Ví Điện Tử"></i>
                </div>
            </div>
        </div>
    </div>
    </div>
</footer>

<style>
    .footer-custom {
        background-color: var(--secondary-color);
        color: white;
        padding: 80px 0 30px 0;
    }

    /* --- 1. ĐỊNH DẠNG VĂN BẢN --- */
    .footer-custom p,
    .footer-custom span,
    .footer-custom li,
    .footer-custom .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    /* Định dạng Tiêu đề cột */
    .footer-custom h4,
    .footer-custom h5,
    .footer-custom h6 {
        color: #ffffff !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
    }

    /* --- 2. LIÊN KẾT & HIỆU ỨNG HOVER --- */
    .footer-custom a,
    .footer-custom a.text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-custom a:hover,
    .footer-custom a.text-muted:hover {
        color: var(--primary-color) !important;
        transform: translateX(5px);
    }

    /* --- 3. BIỂU TƯỢNG MẠNG XÃ HỘI & HIỆU ỨNG HOVER --- */
    .footer-social-icon {
        display: inline-block;
        transition: all 0.3s ease;
    }

    .footer-social-icon:hover {
        color: var(--primary-color) !important;
        transform: translateY(-4px) !important;
        padding-left: 0 !important;
    }
</style>