/* ==========================================================================
   COMPONENT SCRIPT: AUTHENTICATION
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    
    // --- Tự động nhận diện đường dẫn hiện tại ---
    const currentPath = window.location.pathname.toLowerCase();
    const isInAdmin = currentPath.includes('/admin/');
    
    const apiPath = isInAdmin ? '../api/api-auth.php' : './api/api-auth.php';
    const dashboardUrl = isInAdmin ? 'dashboard.php' : './admin/dashboard.php';

    // Pop-up HTML
    const authModalsHTML = `
    <style>
        #loginModal .form-control:focus, #registerModal .form-control:focus, #forgotPasswordModal .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15) !important;
            outline: 0;
        }
        .dropdown-item:hover, .dropdown-item:focus, .dropdown-item:active {
            background-color: #f1f5f9 !important;
            color: var(--secondary-color) !important;
        }
        #userDropdown::after { border-top-color: rgba(255, 255, 255, 0.5) !important; vertical-align: middle !important; }
        #userDropdown:focus { box-shadow: none !important; }
        @media (max-width: 991px) { .auth-mobile-wrapper .dropdown-menu { position: absolute !important; } }
        
        /* Toast Notification Container */
        .toast-container { z-index: 10000; }
    </style>

    <!-- Toasts (Thông báo góc màn hình) -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="systemToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Nội dung thông báo -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Modal Đăng nhập -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="font-family: 'Playfair Display', serif; color: var(--secondary-color);">Chào mừng trở lại</h4>
                        <p class="text-muted small">Đăng nhập để nhận các ưu đãi lưu trú đặc quyền</p>
                    </div>
                    <form id="loginForm">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" required>
                            <label for="loginEmail">Địa chỉ Email</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password" required>
                            <label for="loginPassword">Mật khẩu</label>
                        </div>
                        <div class="d-flex justify-content-end mb-4">
                            <a href="#" class="small text-muted text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Quên mật khẩu?</a>
                        </div>
                        <button type="submit" class="btn-custom btn-primary-theme w-100 py-3 mb-3">Đăng nhập</button>
                        <div class="text-center small">
                            <span class="text-muted">Chưa có tài khoản?</span> 
                            <a href="#" class="text-primary-theme fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký ngay</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Đăng ký -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="font-family: 'Playfair Display', serif; color: var(--secondary-color);">Tạo tài khoản mới</h4>
                        <p class="text-muted small">Gia nhập cộng đồng thượng lưu của Luxury Hotel</p>
                    </div>
                    <form id="registerForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="regName" placeholder="Họ và tên" required>
                            <label for="regName">Họ và tên</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="regEmail" placeholder="name@example.com" required>
                            <label for="regEmail">Địa chỉ Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="phone" class="form-control" id="regPhone" placeholder="+84(000) 000-000" required>
                            <label for="regPhone">Số điện thoại</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="regPassword" placeholder="Password" required minlength="6">
                            <label for="regPassword">Mật khẩu (Tối thiểu 6 ký tự)</label>
                        </div>
                        <button type="submit" class="btn-custom btn-primary-theme w-100 py-3 mb-3">Đăng ký tài khoản</button>
                        <div class="text-center small">
                            <span class="text-muted">Đã có tài khoản?</span> 
                            <a href="#" class="text-primary-theme fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quên Mật Khẩu -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="font-family: 'Playfair Display', serif; color: var(--secondary-color);">Khôi phục mật khẩu</h4>
                        <p class="text-muted small">Nhập email của bạn, chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu.</p>
                    </div>
                    <form id="forgotPasswordForm">
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="forgotEmail" placeholder="name@example.com" required>
                            <label for="forgotEmail">Địa chỉ Email đã đăng ký</label>
                        </div>
                        <button type="submit" class="btn-custom btn-primary-theme w-100 py-3 mb-3">Gửi liên kết khôi phục</button>
                        <div class="text-center small">
                            <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-arrow-left me-1"></i> Quay lại đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

    // Hiển trị login form khi nhấn đăng nhập
    if (!document.getElementById('loginModal')) {
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = authModalsHTML;
        document.body.appendChild(modalContainer);
    }

    // Hiển thị thông báo (Toast)
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('systemToast');
        const toastMessage = document.getElementById('toastMessage');
        
        if(toastEl && toastMessage) {
            toastEl.className = `toast align-items-center text-white border-0 bg-${type}`;
            toastMessage.innerHTML = message;
            
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        }
    }

    // Navbar cho mobile
    function relocateAuthSection() {
        const authSection = document.getElementById('authSection');
        const toggler = document.querySelector('.navbar-toggler');
        const collapseMenu = document.querySelector('.navbar-collapse');

        if (authSection && toggler && authSection.closest('.collapse')) {
            const container = toggler.parentElement;
            const actionGroup = document.createElement('div');
            actionGroup.className = 'd-flex align-items-center ms-auto order-lg-3 auth-mobile-wrapper';

            container.insertBefore(actionGroup, toggler);
            actionGroup.appendChild(authSection);
            actionGroup.appendChild(toggler);

            authSection.className = 'list-unstyled me-3 me-lg-0 ms-lg-3 mb-0';
            if (collapseMenu) collapseMenu.classList.add('order-lg-2');
        }
    }

    // Avatar login 
    function updateAuthUI() {
        const authSection = document.getElementById('authSection');
        if (!authSection) return;

        const userStr = localStorage.getItem('luxury_user');

        if (userStr) {
            const user = JSON.parse(userStr);
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=D4AF37&color=fff&bold=true`;
            
            // Phân quyền hiển thị Menu: Admin và Employee đều thấy trang quản trị
            const canAccessDashboard = (user.role === 'admin' || user.role === 'employee');

            authSection.innerHTML = `
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center p-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                        <img src="${avatarUrl}" class="rounded-circle" width="36" height="36" alt="Avatar">
                        <span class="text-light text-opacity-75 ms-2 d-none d-lg-inline-block">Chào, ${user.name.split(' ').pop()}!</span>
                        
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" aria-labelledby="userDropdown">
                        ${user.role === 'customer' ? `
                        <li><a class="dropdown-item py-2" href="user.php"><i class="bi bi-person me-2 text-muted"></i>Tài khoản của tôi</a></li>
                        <li><a class="dropdown-item py-2" href="user.php?tab=history"><i class="bi bi-clock-history me-2 text-muted"></i>Lịch sử đặt phòng</a></li>
                        ` : ''}
                        ${canAccessDashboard ? `
                            <li><a class="dropdown-item py-2 fw-bold text-primary-theme" href="./admin/dashboard.php"><i class="bi bi-shield-lock me-2"></i>Trang quản trị</a></li>
                            ` : ''}
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="#" id="btnLogout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            `;

        } else {
            authSection.innerHTML = `
                <button class="btn-custom btn-primary-theme rounded-pill px-3 px-lg-4 py-2 shadow-sm" style="font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-person d-none d-lg-inline-block me-1"></i>
                    <i class="bi bi-box-arrow-in-right d-inline-block d-lg-none"></i>
                    <span class="d-none d-sm-inline-block">Đăng nhập</span>
                </button>
            `;
        }
    }

    // AUTHEN & AUTHOR
    // ĐĂNG NHẬP
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang kiểm tra...';
            btn.disabled = true;

            const email = document.getElementById('loginEmail').value.toLowerCase();
            const password = document.getElementById('loginPassword').value;
            
            try {
                // Gọi API backend
                const response = await fetch(apiPath, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'login', email: email, password: password })
                });
                
                const data = await response.json();

                if (data.success) {
                    // Đăng nhập thành công từ CSDL
                    localStorage.setItem('luxury_user', JSON.stringify(data.user));
                    
                    bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                    updateAuthUI();
                    loginForm.reset();
                    showToast('<i class="bi bi-check-circle me-2"></i>Đăng nhập thành công!');

                } else {
                    // Báo lỗi (sai mật khẩu, sai email)
                    showToast('<i class="bi bi-exclamation-triangle me-2"></i>' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Lỗi kết nối API:', error);
                showToast('<i class="bi bi-wifi-off me-2"></i>Không thể kết nối với máy chủ!', 'danger');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

    // ĐĂNG KÝ
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang tạo tài khoản...';
            btn.disabled = true;

            const name = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const phone = document.getElementById('regPhone').value;
            const password = document.getElementById('regPassword').value;

            try {
                // Gọi API tạo mới tài khoản
                const response = await fetch(apiPath, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'register', name: name, email: email, phone: phone, password: password })
                });
                
                const data = await response.json();

                if (data.success) {
                    // Ẩn form đăng ký, bật form đăng nhập
                    bootstrap.Modal.getInstance(document.getElementById('registerModal')).hide();
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                    
                    // Điền sẵn email vừa đăng ký vào form đăng nhập
                    document.getElementById('loginEmail').value = email;

                    // Hiển thị thông báo thành công từ backend
                    showToast('<i class="bi bi-cloud-check me-2"></i>' + data.message + ' Vui lòng đăng nhập.', 'success');
                    registerForm.reset();
                } else {
                    showToast('<i class="bi bi-exclamation-triangle me-2"></i>' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Lỗi kết nối API:', error);
                showToast('<i class="bi bi-wifi-off me-2"></i>Không thể kết nối với máy chủ!', 'danger');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

    // QUÊN MẬT KHẨU
    const forgotForm = document.getElementById('forgotPasswordForm');
    if (forgotForm) {
        forgotForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            btn.disabled = true;
            
            const email = document.getElementById('forgotEmail').value;

            try {
                // Gọi API gửi yêu cầu khôi phục
                const response = await fetch(apiPath, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'forgot_password', email: email })
                });
                
                const data = await response.json();

                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal')).hide();
                    showToast('<i class="bi bi-envelope-check me-2"></i>' + data.message, 'primary');
                    forgotForm.reset();
                } else {
                    showToast('<i class="bi bi-exclamation-triangle me-2"></i>' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Lỗi kết nối API:', error);
                showToast('<i class="bi bi-wifi-off me-2"></i>Không thể kết nối với máy chủ!', 'danger');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

    // =========================================================================
    // XỬ LÝ ĐĂNG XUẤT ĐỒNG BỘ TOÀN HỆ THỐNG (CẬP NHẬT GỌI API)
    // =========================================================================
    document.addEventListener('click', async function (e) {
        const btnLogout = e.target.closest('#btnLogout');
        if (btnLogout) {
            e.preventDefault();
            
            // 1. Gọi API để hủy Session trên máy chủ
            try {
                await fetch(apiPath, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'logout' })
                });
            } catch (err) {
                console.error('Lỗi đăng xuất từ máy chủ', err);
            }

            // 2. Xóa thông tin giao diện
            localStorage.removeItem('luxury_user');
            
            // 3. Gắn cờ thông báo & Chuyển hướng
            localStorage.setItem('logout_success', 'true');
            if (typeof isInAdmin !== 'undefined' && isInAdmin) {
                window.location.href = '../index.php'; 
            } else {
                window.location.href = 'index.php'; 
            }
        }
    });

    relocateAuthSection(); 
    updateAuthUI();  

    // =========================================================================
    // XỬ LÝ HIỂN THỊ THÔNG BÁO SAU KHI ĐÃ CHUYỂN TRANG
    // =========================================================================
    if (localStorage.getItem('logout_success') === 'true') {
        localStorage.removeItem('logout_success'); // Xoá cờ để không hiển thị lại khi F5
        
        // Đợi 0.5s để giao diện Load xong mới báo Toast
        setTimeout(() => {
            showToast('<i class="bi bi-info-circle me-2"></i>Đã đăng xuất khỏi tài khoản!', 'secondary');
        }, 500); 
    }
    
});