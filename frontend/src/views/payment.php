<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Thanh toán - Luxury Hotel</title>

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
    <link rel="stylesheet" href="./assets/css/pages/checkout.css">
</head>

<body>
    <!-- OVERLAY PROCESSING (GIẢ LẬP XỬ LÝ THANH TOÁN) -->
    <div id="paymentProcessingOverlay"
        class="position-fixed top-0 start-0 w-100 h-100 flex-column justify-content-center align-items-center"
        style="background: rgba(26, 37, 47, 0.85); backdrop-filter: blur(5px); z-index: 9999; display: none;">
        <div class="spinner-border" style="width: 4rem; height: 4rem; color: var(--primary-color);" role="status">
        </div>
        <h4 class="mt-4 fw-bold text-white" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;">Đang xử
            lý giao dịch...</h4>
        <p class="text-white-50">Vui lòng không đóng trình duyệt trong lúc này</p>
    </div>

    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. NỘI DUNG CHÍNH -->
    <main class="container checkout-wrapper" style="margin-top: 120px; margin-bottom: 80px;">
        <!-- Nút quay lại -->
        <a href="booking.html"
            class="text-decoration-none text-muted mb-4 d-inline-flex align-items-center transition-hover">
            <i class="bi bi-arrow-left fs-5 me-2"></i> Trở về trang chọn phòng
        </a>
        <!-- Khung Checkout chính -->
        <div class="checkout-box row g-0">
            <!-- CỘT TRÁI: FORM NHẬP LIỆU -->
            <div class="col-lg-7 checkout-form-col p-4 p-md-5">
                <h3 class="fw-bold mb-4" style="color: var(--secondary-color);">Hoàn tất đặt phòng</h3>
                <form id="checkoutForm" class="needs-validation" novalidate>
                    <!-- Thông tin khách hàng -->
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="step-badge">1</span> Thông tin người đặt
                    </h5>
                    <div class="row g-3 mb-4 pb-4 border-bottom">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fullName" placeholder="Họ và tên" required>
                                <label for="fullName">Họ và tên</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" placeholder="Email" required>
                                <label for="email">Địa chỉ Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" placeholder="Số điện thoại" required>
                                <label for="phone">Số điện thoại</label>
                            </div>
                        </div>
                    </div>
                    <!-- Ngày lưu trú -->
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="step-badge">2</span> Thời gian lưu trú
                    </h5>
                    <div class="row g-3 mb-4 pb-4 border-bottom">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Nhận phòng (từ 7:00)</label>
                            <input type="date" class="form-control form-control-lg form-input-theme" id="checkinInput"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Trả phòng (trước 23:00)</label>
                            <input type="date" class="form-control form-control-lg form-input-theme" id="checkoutInput"
                                required>
                        </div>
                    </div>
                    <!-- Phương thức thanh toán -->
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="step-badge">3</span> Phương thức thanh toán
                    </h5>
                    <div class="row g-3 mb-4">
                        <!-- Lựa chọn Thẻ tín dụng -->
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="paymentMethod" id="payCard" value="card" checked
                                required>
                            <label
                                class="btn btn-outline-light w-100 payment-method-card text-center p-3 text-dark border rounded-3"
                                for="payCard">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-credit-card-2-front fs-3 text-primary-theme mb-2"></i>
                                    <span class="fw-bold small">Thẻ tín dụng</span>
                                </div>
                            </label>
                        </div>
                        <!-- Lựa chọn Ví MoMo -->
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="paymentMethod" id="payMomo" value="momo"
                                required>
                            <label
                                class="btn btn-outline-light w-100 payment-method-card text-center p-3 text-dark border rounded-3"
                                for="payMomo">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-wallet2 fs-3 mb-2" style="color: #a50064;"></i>
                                    <span class="fw-bold small" style="color: #a50064;">Ví MoMo</span>
                                </div>
                            </label>
                        </div>
                        <!-- Lựa chọn PayPal -->
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="paymentMethod" id="payPaypal" value="paypal"
                                required>
                            <label
                                class="btn btn-outline-light w-100 payment-method-card text-center p-3 text-dark border rounded-3"
                                for="payPaypal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-paypal fs-3 mb-2" style="color: #003087;"></i>
                                    <span class="fw-bold small" style="color: #003087;">PayPal</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <!-- Khu vực nhập chi tiết thẻ (Ẩn đi nếu chọn MoMo/PayPal) -->
                    <div class="cc-box mb-4" id="cardDetails">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ccNumber" placeholder="Số thẻ" required>
                                    <label for="ccNumber">Số thẻ (16 số)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ccExpiry" placeholder="MM/YY" required>
                                    <label for="ccExpiry">Ngày hết hạn (MM/YY)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ccCVV" placeholder="CVV" required>
                                    <label for="ccCVV">Mã bảo mật (CVV)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-custom btn-primary-theme w-100 py-3 mt-2" id="payButton"
                        style="font-size: 1.1rem;">
                        <i class="bi bi-lock-fill me-2"></i> Xác Nhận & Thanh Toán
                    </button>
                    <p class="text-center text-muted small mt-3"><i class="bi bi-shield-check text-success me-1"></i>
                        Thông tin của bạn được bảo mật tuyệt đối an toàn qua hệ thống mã hóa SSL.</p>
                </form>
            </div>
            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG -->
            <div class="col-lg-5 checkout-summary-col">
                <div class="sticky-summary">
                    <h4 class="fw-bold mb-4">Tóm tắt đơn đặt phòng</h4>

                    <div class="summary-img-wrap position-relative">
                        <img id="summaryImg"
                            src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80"
                            class="w-100 h-100 object-fit-cover" alt="Room Image">
                        <div class="position-absolute bottom-0 start-0 w-100 p-3"
                            style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <h5 id="summaryName" class="text-white mb-0">Phòng Deluxe Hướng Biển</h5>
                        </div>
                    </div>

                    <div
                        class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary border-opacity-50">
                        <div>
                            <p class="text-white-50 mb-1 small">Nhận phòng</p>
                            <h6 class="fw-bold text-white" id="summaryCheckin">--/--/----</h6>
                            <p class="text-white-50 small mb-0">Từ 7:00</p>
                        </div>
                        <div class="text-end">
                            <p class="text-white-50 mb-1 small">Trả phòng</p>
                            <h6 class="fw-bold text-white" id="summaryCheckout">--/--/----</h6>
                            <p class="text-white-50 small mb-0">Trước 23:00</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">Tiền phòng <span id="summaryDaysText">(-- đêm)</span></span>
                        <span class="fw-bold text-white" id="summaryPrice">0₫</span>
                    </div>
                    <div
                        class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary border-opacity-50">
                        <span class="text-white-50">Thuế & Phí (10%)</span>
                        <span class="fw-bold text-white" id="summaryTax">0₫</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fs-5 text-white-50">Tổng thanh toán</span>
                        <span class="fs-3 fw-bold text-primary-theme" id="summaryTotal">0₫</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 3. FOOTER -->
    <?php include './includes/footer.php'; ?>

    <!-- 4. MODALS (POPUP) -->
    <!-- Modal QR MoMo -->
    <div class="modal fade" id="momoQrModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg" style="overflow: hidden;">
                <!-- Header -->
                <div class="modal-header border-0 d-flex justify-content-between align-items-center px-4 pt-4 pb-3"
                    style="background-color: #A50064; color: white;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 fs-4 me-2"></i>
                        <h5 class="modal-title fw-bold mb-0" style="letter-spacing: 0.5px;">Thanh toán qua MoMo</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <!-- Body -->
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <p class="text-muted fw-bold small mb-1 text-uppercase">Số tiền cần thanh toán</p>
                        <h2 class="fw-bold mb-0" id="momoTotalAmount" style="color: #A50064; font-size: 2.2rem;">0₫</h2>
                    </div>

                    <!-- QR Code Container -->
                    <div class="position-relative d-inline-block p-2 bg-white rounded-4 shadow-sm border mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MoMoPaymentForLuxuryHotel&margin=10"
                            alt="QR Code MoMo" class="img-fluid rounded-3" style="width: 220px; height: 220px;">

                        <!-- Giả lập Logo MoMo chen giữa QR Code -->
                        <div class="position-absolute top-50 start-50 translate-middle bg-white p-1 rounded-circle shadow-sm"
                            style="width: 45px; height: 45px;">
                            <div class="w-100 h-100 rounded-circle d-flex justify-content-center align-items-center"
                                style="background-color: #A50064; color: white; font-size: 1.2rem;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="text-start bg-light p-3 rounded-3 mb-4 border"
                        style="border-color: #f1f5f9 !important;">
                        <p class="fw-bold mb-2 small" style="color: var(--secondary-color);"><i
                                class="bi bi-info-circle me-1"></i> Hướng dẫn thanh toán:</p>
                        <ol class="small text-muted mb-0 ps-3" style="line-height: 1.8;">
                            <li>Mở ứng dụng <strong>MoMo</strong> trên điện thoại.</li>
                            <li>Chọn <strong>Quét mã QR</strong> tại màn hình chính.</li>
                            <li>Quét mã QR ở trên để hoàn tất thủ tục.</li>
                        </ol>
                    </div>

                    <!-- Loading Indicator -->
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-2 mb-2">
                        <div class="spinner-grow spinner-grow-sm" style="color: #A50064;" role="status"></div>
                        <span class="small fw-medium text-muted">Hệ thống đang chờ xác nhận từ MoMo...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Thanh toán Thành công-->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-5 rounded-4 border-0 shadow-lg">
                <div class="modal-body">
                    <i class="bi bi-check-circle-fill mb-3" style="font-size: 4rem; color: #10b981;"></i>
                    <h3 class="fw-bold mb-3" style="color: var(--secondary-color);">Thanh toán thành công!</h3>
                    <p class="text-muted mb-4 fs-6">Mã xác nhận đặt phòng của bạn là:<br>
                        <strong id="bookingCodeDisplay" class="d-block mt-2 py-3 bg-light rounded-3 border"
                            style="color: var(--primary-color); font-size: 1.8rem; letter-spacing: 3px;"></strong>
                    </p>

                    <!-- Vùng thông báo Email cho TẤT CẢ mọi người -->
                    <div id="emailNotificationArea" class="alert alert-success small text-start mb-4"
                        style="display: none;">
                        <i class="bi bi-envelope-check-fill me-1"></i> <strong>Hóa đơn điện tử</strong> và chi tiết đặt
                        phòng đã được gửi về email của bạn. Vui lòng kiểm tra hộp thư!
                    </div>

                    <a href="index.php" class="btn-custom btn-primary-theme w-100" style="padding: 14px;">Về Trang
                        Chủ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/pages/checkout.js"></script>
</body>

</html>