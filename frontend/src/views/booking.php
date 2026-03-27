<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Luxury Hotel - Đặt Phòng Trực Tuyến</title>

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
    <link rel="stylesheet" href="./assets/css/components/search-bar.css">
    <link rel="stylesheet" href="./assets/css/components/room-card.css">
    <link rel="stylesheet" href="./assets/css/components/chatbox.css">
</head>

<body>
    <!-- OVERLAY LOADING (GIẢ LẬP TẢI TRANG) -->
    <div id="pageLoadingOverlay"
        class="position-fixed top-0 start-0 w-100 h-100 flex-column justify-content-center align-items-center"
        style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px); z-index: 9999; display: none;">
        <div class="spinner-border" style="width: 3.5rem; height: 3.5rem; color: var(--primary-color);" role="status">
        </div>
        <h4 class="mt-4 fw-bold" style="color: var(--secondary-color); font-family: 'Playfair Display', serif;">Đang xử
            lý...</h4>
        <p class="text-muted">Hệ thống đang tìm phòng cho bạn</p>
    </div>

    <!-- 1. HEADER -->
    <?php include './includes/header.php'; ?>

    <!-- 2. HEADER PHỤ -->
    <header class="header-sub"
        style="background: linear-gradient(to right, rgba(19, 26, 34, 0.95) 0%, rgba(26, 37, 47, 0.8) 40%, rgba(26, 37, 47, 0.3) 100%), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;">
        <div class="container position-relative" style="z-index: 2;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb luxury-breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đặt phòng</li>
                </ol>
            </nav>
            <h1 class="main-title">Tìm Phòng Phù Hợp</h1>
            <div class="divider"></div>
            <p class="desc">Hệ thống sẽ tự động cập nhật và hiển thị các phòng còn trống dựa trên khoảng thời gian mà
                bạn lựa chọn.</p>
        </div>
    </header>

    <!-- 3. THANH TÌM KIẾM -->
    <section class="container" style="margin-top: -60px; position: relative; z-index: 10;">
        <div class="minimal-search-bar">
            <form class="row g-3 align-items-end" id="bookSearchForm">
                <div class="col-lg-2 col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-check me-2"></i>Nhận phòng</label>
                    <input type="date" class="form-control" id="searchIn" required>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-x me-2"></i>Trả phòng</label>
                    <input type="date" class="form-control" id="searchOut" required>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-people me-2"></i>Số khách</label>
                    <select class="form-select" id="searchCap">
                        <option value="1">1 Người lớn</option>
                        <option value="2" selected>2 Người lớn</option>
                        <option value="3">3 Người lớn</option>
                        <option value="4">4 Người lớn (Gia đình)</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-door-open me-2"></i>Loại phòng</label>
                    <select class="form-select" id="searchRoomType">
                        <option value="all">Tất cả các phòng</option>
                        <option value="Phòng Deluxe Hướng Biển">Phòng Deluxe Hướng Biển</option>
                        <option value="Phòng Suite Cao Cấp">Phòng Suite Cao Cấp</option>
                        <option value="Phòng Family Suite">Phòng Family Suite</option>
                        <option value="Phòng Standard Hướng Phố">Phòng Standard Hướng Phố</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-12">
                    <!-- Nút tìm kiếm -->
                    <button type="button" class="btn-custom btn-primary-theme w-100"
                        style="padding: 14px; border-radius: 10px;" onclick="searchRooms()">
                        <i class="bi bi-search me-1"></i> TÌM PHÒNG
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- 4. KẾT QUẢ TÌM KIẾM (HIỂN THỊ DANH SÁCH PHÒNG) -->
    <main class="container py-5 mt-4">
        <div id="searchResultHeader" class="text-center mb-5">
            <h3 class="luxury-title" style="font-size: 1.5rem;" id="dynamicTitle">Tất cả các phòng</h3>
        </div>
        <div class="row g-4" id="roomListContainer">
            <!-- PHÒNG 1: DELUXE -->
            <div class="col-lg-6">
                <div class="card room-card flex-md-row overflow-hidden" style="height: 100%;">
                    <div class="position-relative col-md-5">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80"
                            class="room-card-img" style="height: 100%; border-radius: 0; object-fit: cover;"
                            alt="Deluxe Room">
                    </div>
                    <div class="card-body room-card-body col-md-7 p-4 d-flex flex-column justify-content-center">
                        <span class="badge-theme badge-available mb-2">Đang kiểm tra...</span>
                        <h4 class="room-title mt-0">Phòng Deluxe Hướng Biển</h4>
                        <p class="text-muted small mb-3">Tận hưởng bình minh trên biển ngay từ ban công riêng.</p>
                        <ul class="text-muted small list-unstyled mb-4">
                            <li class="mb-1"><i class="bi bi-arrows-fullscreen text-primary-theme me-2"></i> Diện tích:
                                45m²</li>
                            <li class="mb-1"><i class="bi bi-people text-primary-theme me-2"></i> Sức chứa: 2 người</li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <!-- Đã cập nhật Spinner chờ giá động từ CSDL -->
                            <div class="room-price"><span
                                    class="spinner-border spinner-border-sm text-secondary"></span></div>
                            <button class="btn-custom btn-primary-theme px-4 py-2 book-action-btn"
                                style="font-size: 0.85rem;">Đặt ngay</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PHÒNG 2: SUITE -->
            <div class="col-lg-6">
                <div class="card room-card flex-md-row overflow-hidden" style="height: 100%;">
                    <div class="position-relative col-md-5">
                        <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=800&q=80"
                            class="room-card-img" style="height: 100%; border-radius: 0; object-fit: cover;"
                            alt="Suite Room">
                    </div>
                    <div class="card-body room-card-body col-md-7 p-4 d-flex flex-column justify-content-center">
                        <span class="badge-theme badge-available mb-2">Đang kiểm tra...</span>
                        <h4 class="room-title mt-0">Phòng Suite Cao Cấp</h4>
                        <p class="text-muted small mb-3">Không gian rộng rãi, phòng tắm cẩm thạch bồn jacuzzi.</p>
                        <ul class="text-muted small list-unstyled mb-4">
                            <li class="mb-1"><i class="bi bi-arrows-fullscreen text-primary-theme me-2"></i> Diện tích:
                                70m²</li>
                            <li class="mb-1"><i class="bi bi-people text-primary-theme me-2"></i> Sức chứa: 2 người</li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="room-price"><span
                                    class="spinner-border spinner-border-sm text-secondary"></span></div>
                            <button class="btn-custom btn-primary-theme px-4 py-2 book-action-btn"
                                style="font-size: 0.85rem;">Đặt ngay</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PHÒNG 3: FAMILY SUITE -->
            <div class="col-lg-6">
                <div class="card room-card flex-md-row overflow-hidden" style="height: 100%;">
                    <div class="position-relative col-md-5">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80"
                            class="room-card-img" style="height: 100%; border-radius: 0; object-fit: cover;"
                            alt="Family Room">
                    </div>
                    <div class="card-body room-card-body col-md-7 p-4 d-flex flex-column justify-content-center">
                        <span class="badge-theme badge-available mb-2">Đang kiểm tra...</span>
                        <h4 class="room-title mt-0">Phòng Family Suite</h4>
                        <p class="text-muted small mb-3">Hai phòng ngủ thông nhau, không gian chung ấm cúng.</p>
                        <ul class="text-muted small list-unstyled mb-4">
                            <li class="mb-1"><i class="bi bi-arrows-fullscreen text-primary-theme me-2"></i> Diện tích:
                                90m²</li>
                            <li class="mb-1"><i class="bi bi-people text-primary-theme me-2"></i> Sức chứa: 4 người</li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="room-price"><span
                                    class="spinner-border spinner-border-sm text-secondary"></span></div>
                            <button class="btn-custom btn-primary-theme px-4 py-2 book-action-btn"
                                style="font-size: 0.85rem;">Đặt ngay</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PHÒNG 4: STANDARD -->
            <div class="col-lg-6">
                <div class="card room-card flex-md-row overflow-hidden" style="height: 100%;">
                    <div class="position-relative col-md-5">
                        <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80"
                            class="room-card-img" style="height: 100%; border-radius: 0; object-fit: cover;"
                            alt="Standard Room">
                    </div>
                    <div class="card-body room-card-body col-md-7 p-4 d-flex flex-column justify-content-center">
                        <span class="badge-theme badge-available mb-2">Đang kiểm tra...</span>
                        <h4 class="room-title mt-0">Phòng Standard Hướng Phố</h4>
                        <p class="text-muted small mb-3">Gọn gàng, tiện nghi, phù hợp cho công tác ngắn ngày.</p>
                        <ul class="text-muted small list-unstyled mb-4">
                            <li class="mb-1"><i class="bi bi-arrows-fullscreen text-primary-theme me-2"></i> Diện tích:
                                30m²</li>
                            <li class="mb-1"><i class="bi bi-people text-primary-theme me-2"></i> Sức chứa: 2 người</li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="room-price"><span
                                    class="spinner-border spinner-border-sm text-secondary"></span></div>
                            <button class="btn-custom btn-primary-theme px-4 py-2 book-action-btn"
                                style="font-size: 0.85rem;">Đặt ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 5. FOOTER -->
    <?php include './includes/footer.php'; ?>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchCap = document.getElementById('searchCap');
            const searchRoomType = document.getElementById('searchRoomType');

            function updateRoomOptions() {
                if (!searchCap || !searchRoomType) return;

                const cap = parseInt(searchCap.value);
                const options = searchRoomType.options;

                for (let i = 0; i < options.length; i++) {
                    const val = options[i].value;

                    if (cap >= 3) {
                        // Từ 3 người trở lên: Chỉ hiển thị 'Tất cả các phòng' hoặc 'Phòng Family Suite'
                        if (val !== 'all' && val !== 'Phòng Family Suite') {
                            options[i].hidden = true;
                            options[i].style.display = 'none'; // Fallback an toàn cho một số trình duyệt
                        } else {
                            options[i].hidden = false;
                            options[i].style.display = '';
                        }
                    } else {
                        // 1 hoặc 2 người: Hiển thị lại tất cả các loại phòng
                        options[i].hidden = false;
                        options[i].style.display = '';
                    }
                }

                // Kiểm tra xem option đang được chọn có đang bị ẩn hay không
                const selectedOption = searchRoomType.options[searchRoomType.selectedIndex];

                // Nếu loại phòng đang chọn bị ẩn đi, tự động chuyển về 'Phòng Family Suite'
                if (selectedOption && (selectedOption.hidden || selectedOption.style.display === 'none')) {
                    searchRoomType.value = 'Phòng Family Suite';
                }
            }

            // Lắng nghe sự kiện người dùng thay đổi số lượng khách
            if (searchCap) {
                searchCap.addEventListener('change', function () {
                    updateRoomOptions();
                });
            }

            // Chạy hàm lần đầu (Delay một chút để JS nhận diện tham số từ URL trước)
            setTimeout(updateRoomOptions, 100);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="./assets/js/components/auth.js"></script>
    <script src="./assets/js/components/chatbox.js"></script>
    <script src="./assets/js/pages/booking.js"></script>
    <script src="./assets/js/pages/room-info.js"></script>
    <script src="./assets/js/components/capacity-filter.js"></script>

</body>

</html>