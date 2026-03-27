<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản của tôi - Luxury Hotel</title>

    <!-- Thư viện & Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="./assets/css/base/global.css">
    <link rel="stylesheet" href="./assets/css/components/header.css">
    <link rel="stylesheet" href="./assets/css/pages/user.css">
</head>

<body>
    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. NỘI DUNG CHÍNH -->
    <main class="container account-wrapper">
        <div class="row g-4">
            <!-- Cột trái: Menu -->
            <div class="col-lg-3">
                <div class="card card-custom p-4 text-center mb-4">
                    <img id="userAvatar" src="" class="rounded-circle mx-auto mb-3" width="90" height="90"
                        style="object-fit: cover; border: 3px solid var(--primary-color);">
                    <h5 class="fw-bold mb-1" id="userDisplayName" style="color: var(--secondary-color);">Khách Hàng</h5>
                    <p class="text-muted small mb-0" id="userDisplayRole">Thành viên Luxury</p>
                </div>

                <div class="card card-custom p-2 sticky-top" style="top: 100px; z-index: 10;">
                    <!-- Đã khôi phục lại cấu trúc Tab chuẩn của Bootstrap -->
                    <div class="nav flex-column nav-pills sidebar-menu" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active text-start" id="v-pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-profile" type="button" role="tab">
                            <i class="bi bi-person-lines-fill me-2"></i> Hồ sơ cá nhân
                        </button>
                        <button class="nav-link text-start" id="v-pills-history-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-history" type="button" role="tab">
                            <i class="bi bi-clock-history me-2"></i> Lịch sử đặt phòng
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Nội dung Tabs -->
            <div class="col-lg-9">
                <div class="tab-content card card-custom p-4 p-md-5" id="v-pills-tabContent" style="min-height: 500px;">

                    <!-- TAB 1: HỒ SƠ -->
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                        <h4 class="fw-bold mb-4" style="color: var(--secondary-color);">Hồ Sơ Của Tôi</h4>
                        <p class="text-muted mb-4 pb-3 border-bottom">Quản lý thông tin hồ sơ để bảo mật tài khoản.</p>

                        <form id="profileForm">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Họ và tên</label>
                                    <input type="text" class="form-control py-2" id="profName" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Số điện thoại</label>
                                    <input type="text" class="form-control py-2" id="profPhone" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted small fw-bold">Địa chỉ Email (Tên đăng
                                        nhập)</label>
                                    <input type="email" class="form-control py-2 bg-light text-muted" id="profEmail"
                                        readonly>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-muted small mt-2"><i
                                            class="bi bi-shield-check text-success me-1"></i> Tài khoản được bảo vệ.
                                        Ngày tham gia: <strong id="profJoinDate">--/--/----</strong></p>
                                </div>
                            </div>
                            <button type="submit" class="btn-custom btn-primary-theme px-5 py-2 mt-4"
                                id="btnSaveProfile">Lưu Thay Đổi</button>
                        </form>
                    </div>

                    <!-- TAB 2: LỊCH SỬ ĐẶT PHÒNG -->
                    <div class="tab-pane fade" id="v-pills-history" role="tabpanel">
                        <h4 class="fw-bold mb-4" style="color: var(--secondary-color);">Lịch Sử Đặt Phòng</h4>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã Đơn</th>
                                        <th>Phòng</th>
                                        <th>Ngày Nhận</th>
                                        <th>Ngày Trả</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center py-5"><span
                                                class="spinner-border spinner-border-sm text-secondary"></span> Đang tải
                                            dữ liệu...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- 3. TOAST THÔNG BÁO -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
        <div id="accountToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-info-circle-fill me-2"></i> <span id="accountToastMsg">Thông báo</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/pages/user.js"></script>
</body>

</html>