<?php
// Khởi động phiên làm việc để kiểm tra thẻ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra: Nếu chưa đăng nhập HOẶC không có quyền admin/employee -> ĐÁ VĂNG
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    // Lập tức chuyển hướng về trang chủ
    header('Location: ../index.php');
    exit(); // Dừng toàn bộ hệ thống, không render giao diện HTML phía dưới
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel | Admin Workspace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/base/global.css">
    <link rel="stylesheet" href="../assets/css/pages/admin.css">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar" id="sidebar">
        <div class="logo d-flex justify-content-center align-items-center">
            <a href="" class="text-decoration-none d-flex align-items-center" style="color: var(--primary-color);">
                <i class="bi bi-flower1 fs-3 me-2"></i>
                <span class="d-flex flex-column justify-content-center" style="line-height: 1;">
                    <span class="fw-bold"
                        style="font-size: 1.4rem; letter-spacing: 3.5px; font-family: 'Cinzel', serif;">LUXURY</span>
                    <span class="fw-light text-white"
                        style="font-size: 0.6rem; letter-spacing: 4px; font-family: 'Montserrat';">RESORT & SPA</span>
                </span>
            </a>
        </div>

        <div class="nav-menu">
            <a class="nav-item-custom active" data-tab="dashboard"><i class="bi bi-grid"></i> Tổng quan</a>
            <a class="nav-item-custom" data-tab="rooms"><i class="bi bi-door-open"></i> Danh sách phòng</a>
            <a class="nav-item-custom" data-tab="booking"><i class="bi bi-plus-square"></i> Tạo đơn mới</a>
            <a class="nav-item-custom admin-only" data-tab="history"><i class="bi bi-clock-history"></i> Lịch sử đơn
                đặt</a>
            <a class="nav-item-custom admin-only" data-tab="customers"><i class="bi bi-people"></i> Khách hàng</a>
            <a class="nav-item-custom admin-only" data-tab="pricing"><i class="bi bi-tags"></i> Cài đặt giá phòng</a>
        </div>

        <div class="p-4 mt-auto">
            <div
                style="background: rgba(255,255,255,0.05); border-radius: 8px; padding: 20px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                <p class="small text-white-50 mb-3">Truy cập Website</p>
                <a href="../index.php" class="btn btn-outline-light w-100 py-2" style="font-size: 0.9rem;">
                    <i class="bi bi-box-arrow-up-right me-2"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>

    <!-- ================= MAIN APP ================= -->
    <div class="main">
        <div class="navbar-custom">
            <div class="d-flex align-items-center gap-3">
                <button
                    class="btn btn-light d-lg-none rounded-circle d-flex justify-content-center align-items-center shadow-sm"
                    id="sidebarToggleBtn" style="width: 44px; height: 44px; border: 1px solid #e2e8f0;">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h2 class="page-title d-none d-sm-block" id="pageTitle">Tổng quan</h2>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- THÔNG BÁO -->
                <div class="dropdown">
                    <button
                        class="btn btn-light rounded-circle position-relative d-flex justify-content-center align-items-center shadow-sm"
                        style="width: 44px; height: 44px; border: 1px solid #e2e8f0;" data-bs-toggle="dropdown"
                        aria-expanded="false" id="btnNotifications">
                        <i class="bi bi-bell fs-5"></i>
                        <span id="notifBadge"
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                            style="display: none;">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2"
                        style="width: 320px; border-radius: 12px; overflow: hidden;">
                        <li class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">Thông báo</h6>
                            <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" id="markReadBtn"
                                style="font-size: 0.8rem;">Đánh dấu đã đọc</button>
                        </li>
                        <div id="notificationList" style="max-height: 300px; overflow-y: auto;"></div>
                        <li class="p-2 border-top text-center bg-light">
                            <a href="#" class="text-decoration-none small text-secondary fw-bold" data-bs-toggle="modal"
                                data-bs-target="#allNotifModal">Xem tất cả</a>
                        </li>
                    </ul>
                </div>

                <!-- TÀI KHOẢN (Lấy tên từ PHP Session) -->
                <div class="dropdown">
                    <div class="user-info d-flex align-items-center gap-2" style="cursor: pointer;"
                        data-bs-toggle="dropdown">
                        <div class="text-end d-none d-sm-block">
                            <span class="fw-bold ms-1" style="font-size: 0.95rem;" id="adminNameDisplay">
                                <?= htmlspecialchars($_SESSION['name']); ?>
                            </span>
                        </div>
                        <i class="bi bi-person-circle fs-3 text-secondary"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3">
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="#" id="btnLogoutAdminMenu"><i
                                    class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-area">

            <!-- TAB 1: DASHBOARD -->
            <div id="tab-dashboard" class="section-tab active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="card-icon icon-blue"><i class="bi bi-bag-check-fill"></i></div>
                        <div>
                            <p class="card-label">Tổng đơn đặt</p>
                            <h3 class="card-val" id="stat-bookings">0</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="card-icon icon-green"><i class="bi bi-key-fill"></i></div>
                        <div>
                            <p class="card-label">Phòng trống</p>
                            <h3 class="card-val" id="stat-rooms">0</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="card-icon icon-gold"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <p class="card-label">Doanh thu</p>
                            <h3 class="card-val" id="stat-revenue">0đ</h3>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-header">
                        <h3>Đơn Đặt Phòng Gần Đây</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Phòng</th>
                                    <th>Loại Phòng</th>
                                    <th>Nhận - Trả</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody id="recentBookingsBody">
                                <tr>
                                    <td colspan="6" class="text-center py-5">Đang tải...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: QUẢN LÝ PHÒNG -->
            <div id="tab-rooms" class="section-tab">
                <div class="filter-section">
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-md-4">
                            <label class="form-label small text-muted fw-bold">Từ ngày</label>
                            <input type="date" class="form-control" id="filterRoomIn">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted fw-bold">Đến ngày</label>
                            <input type="date" class="form-control" id="filterRoomOut">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-secondary w-100" id="btnFilterRoomsTime">Kiểm tra</button>
                        </div>
                    </div>
                    <div class="search-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" id="roomSearch" placeholder="Tìm kiếm phòng (VD: 101, 202)...">
                    </div>
                    <div class="filter-tabs">
                        <div class="filter-chip active" data-filter="All">Tất cả</div>
                        <div class="filter-chip" data-filter="Available"><span
                                style="display:inline-block; width:8px; height:8px; background:#22c55e; border-radius:50%;"></span>
                            Trống</div>
                        <div class="filter-chip" data-filter="Occupied"><span
                                style="display:inline-block; width:8px; height:8px; background:#ef4444; border-radius:50%;"></span>
                            Đang Thuê</div>
                        <div class="filter-chip" data-filter="Maintenance"><span
                                style="display:inline-block; width:8px; height:8px; background:#f59e0b; border-radius:50%;"></span>
                            Bảo Trì</div>
                    </div>
                </div>
                <div class="room-grid" id="roomsContainer"></div>
            </div>

            <!-- TAB 3: ĐẶT PHÒNG -->
            <div id="tab-booking" class="section-tab">
                <div class="form-card">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Tạo Đơn Cho Khách Hàng</h3>
                    </div>
                    <form id="adminBookingForm">
                        <div class="row g-4 mb-4">
                            <div class="col-md-4"><label class="form-label">Tên Khách Hàng</label><input type="text"
                                    class="form-control" id="cName" required></div>
                            <div class="col-md-4"><label class="form-label">Số Điện Thoại</label><input type="text"
                                    class="form-control" id="cPhone" required></div>
                            <div class="col-md-4"><label class="form-label">Email</label><input type="email"
                                    class="form-control" id="cEmail" required></div>
                        </div>
                        <div style="background: #f8f9fa; border-radius: 8px; padding: 25px; border: 1px solid #e9ecef;">
                            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2"></i> Chi tiết lưu trú</h5>
                            <div class="row g-4">
                                <div class="col-md-4"><label class="form-label">Ngày Nhận</label><input type="date"
                                        class="form-control" id="cCheckIn" required></div>
                                <div class="col-md-4"><label class="form-label">Ngày Trả</label><input type="date"
                                        class="form-control" id="cCheckOut" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Trạng Thái Thanh Toán</label>
                                    <select class="form-select" id="cPaymentStatus">
                                        <option value="Pending">Chưa thanh toán</option>
                                        <option value="Completed">Đã thu tiền tại quầy</option>
                                    </select>
                                </div>
                                <div class="col-md-8"><label class="form-label">Phòng trống</label><select
                                        class="form-select" id="cRoom" required>
                                        <option value="">Chọn ngày...</option>
                                    </select></div>
                                <div class="col-md-4"><label class="form-label">Tổng Tiền (VNĐ)</label><input
                                        type="number" class="form-control fw-bold text-success" id="cTotal" readonly
                                        required></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary w-100 py-3 mt-4 text-white fw-bold">Xác Nhận Tạo
                            Đơn</button>
                    </form>
                </div>
            </div>

            <!-- TAB 4: LỊCH SỬ ĐƠN (ADMIN) -->
            <div id="tab-history" class="section-tab admin-only">
                <div class="table-card">
                    <div class="table-header">
                        <h3>Toàn Bộ Lịch Sử Đặt Phòng</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Phòng</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="historyBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: KHÁCH HÀNG (ADMIN) -->
            <div id="tab-customers" class="section-tab admin-only">
                <div class="table-card">
                    <div class="table-header">
                        <h3>Danh sách Khách Hàng</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Họ & Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngày tham gia</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="customersBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: CÀI ĐẶT GIÁ PHÒNG (ADMIN) -->
            <div id="tab-pricing" class="section-tab admin-only">
                <div class="table-card">
                    <div class="table-header">
                        <h3>Điều Chỉnh Giá Hạng Phòng</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Hạng Phòng</th>
                                    <th>Sức chứa</th>
                                    <th>Giá cơ bản (VNĐ)</th>
                                    <th class="text-end">Cập nhật</th>
                                </tr>
                            </thead>
                            <tbody id="pricingBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Modal Tất cả thông báo -->
    <div class="modal fade" id="allNotifModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tất cả thông báo</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <ul class="list-group list-group-flush" id="modalNotifList"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật trạng thái đơn -->
    <div class="modal fade" id="editStatusModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Cập nhật Trạng thái</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editStatusForm">
                        <input type="hidden" id="esId">
                        <div class="mb-3"><label class="fw-bold text-secondary" id="esCodeLabel"></label></div>
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold">Trạng thái mới</label>
                            <select class="form-select" id="esStatus" required>
                                <option value="Pending">Chưa thanh toán</option>
                                <option value="Completed">Đã thanh toán / Tại quầy</option>
                                <option value="Cancelled">Huỷ bỏ</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-custom btn-primary-theme w-100 py-2">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa User -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Sửa Thông Tin Khách</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="euId">
                        <div class="mb-3"><label>Họ tên</label><input type="text" class="form-control" id="euName"
                                required></div>
                        <div class="mb-3"><label>Số điện thoại</label><input type="text" class="form-control"
                                id="euPhone" required></div>
                        <div class="mb-3"><label>Email (Không thể sửa)</label><input type="text"
                                class="form-control bg-light" id="euEmail" readonly></div>
                        <button type="submit" class="btn btn-secondary w-100">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật giá phòng -->
    <div class="modal fade" id="editPriceModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Cập nhật Giá</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPriceForm">
                        <input type="hidden" id="epId">
                        <div class="mb-3"><label class="fw-bold" id="epNameLabel"></label></div>
                        <div class="mb-4"><label>Giá mới (VNĐ)</label><input type="number" class="form-control"
                                id="epPrice" required></div>
                        <button type="submit" class="btn btn-secondary w-100">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow text-center">
                <div class="modal-body pt-5 pb-4 px-4">
                    <i class="bi bi-exclamation-circle text-warning mb-3 d-block" style="font-size: 3.5rem;"></i>
                    <h5 class="fw-bold mb-3" id="confirmModalTitle">Xác nhận</h5>
                    <p id="confirmModalMessage" class="text-muted mb-4"></p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger px-4" id="confirmModalBtn">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../assets/js/pages/admin.js"></script>

</body>

</html>