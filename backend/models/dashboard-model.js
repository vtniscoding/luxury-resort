/* ==========================================================================
   PAGE SCRIPT: ADMIN DASHBOARD
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const API_URL = '../api/api-admin.php'; 
    const formatVND = (num) => Number(num).toLocaleString('vi-VN') + 'đ';

    /* ======================================================================
       1. RBAC - KIỂM TRA QUYỀN TRUY CẬP & GIAO DIỆN THEO ROLE
       ====================================================================== */
    const userStr = localStorage.getItem('luxury_user');
    let currentUser = null;

    if (!userStr) {
        window.location.href = '../index.php';
        return;
    } 

    currentUser = JSON.parse(userStr);
    // CẬP NHẬT ĐỒNG BỘ: Nút Đăng xuất ở giao diện Quản trị
    document.getElementById('btnLogoutAdminMenu')?.addEventListener('click', async (e) => {
        e.preventDefault(); 
        
        // Gọi API Hủy Session máy chủ
        try {
            await fetch(API_URL.replace('api-admin.php', 'api-auth.php'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'logout' })
            });
        } catch(err) {}

        // 1. Xóa phiên làm việc
        localStorage.removeItem('luxury_user'); 
        
        // 2. Gắn cờ thông báo cho trang chủ
        localStorage.setItem('logout_success', 'true');
        
        // 3. Chuyển hướng
        window.location.href = '../index.php';
    });

    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('sidebar');
    let overlay = document.getElementById('sidebarOverlay');
    if(!overlay) { 
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay'; 
        overlay.id = 'sidebarOverlay'; 
        document.body.appendChild(overlay); 
    }
    
    toggleBtn?.addEventListener('click', (e) => { 
        e.stopPropagation(); 
        sidebar.classList.toggle('show'); 
        overlay.classList.toggle('show'); 
    });
    overlay.addEventListener('click', () => { 
        sidebar.classList.remove('show'); 
        overlay.classList.remove('show'); 
    });

    /* ======================================================================
       2. TÍNH NĂNG THÔNG BÁO
       ====================================================================== */
    let localNotifications = JSON.parse(localStorage.getItem('admin_local_notifs')) || [];
    let isNotifRead = localStorage.getItem('admin_notif_read') === 'true';

    window.addLocalNotification = function(title, desc, icon = 'bi-info-circle', color = 'secondary') {
        const newNotif = { title, desc, icon, color, time: new Date().toISOString(), isLocal: true };
        localNotifications.unshift(newNotif);
        if(localNotifications.length > 10) localNotifications.pop();
        localStorage.setItem('admin_local_notifs', JSON.stringify(localNotifications));
        localStorage.setItem('admin_notif_read', 'false');
        isNotifRead = false;
        loadNotifications();
    }

    function loadNotifications() {
        fetch(`${API_URL}?action=get_notifications`)
            .then(res => res.json())
            .then(dbData => {
                let mergedData = [];
                if(Array.isArray(dbData)) mergedData = [...dbData];
                
                mergedData = [...localNotifications, ...mergedData];
                mergedData.sort((a,b) => new Date(b.time) - new Date(a.time));

                const notifList = document.getElementById('notificationList');
                const modalList = document.getElementById('modalNotifList');
                const badge = document.getElementById('notifBadge');
                
                if (mergedData.length === 0) {
                    if(notifList) notifList.innerHTML = '<li class="p-3 text-center text-muted small">Không có thông báo nào</li>';
                    if(modalList) modalList.innerHTML = '<li class="list-group-item text-center text-muted">Trống</li>';
                    if(badge) badge.style.display = 'none';
                    return;
                }

                if(badge) badge.style.display = isNotifRead ? 'none' : 'block';
                
                let html = '';
                mergedData.forEach(item => {
                    const timeStr = new Date(item.time).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
                    const readClass = isNotifRead ? 'notif-read bg-light' : '';
                    html += `
                        <li class="dropdown-item py-2 border-bottom ${readClass}" style="white-space: normal;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-${item.color} bg-opacity-10 text-${item.color} rounded-circle d-flex justify-content-center align-items-center" style="width: 35px; height: 35px; flex-shrink: 0;"><i class="bi ${item.icon}"></i></div>
                                <div><p class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">${item.title}</p><p class="mb-1 text-muted" style="font-size: 0.8rem;">${item.desc}</p><span class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>${timeStr}</span></div>
                            </div>
                        </li>`;
                });

                if(notifList) notifList.innerHTML = html;
                if(modalList) modalList.innerHTML = html.replace(/dropdown-item/g, 'list-group-item border-0');
            });
    }
    
    document.getElementById('markReadBtn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        localStorage.setItem('admin_notif_read', 'true');
        isNotifRead = true;
        const badge = document.getElementById('notifBadge');
        if(badge) badge.style.display = 'none';
        loadNotifications();
    });

    loadNotifications();

    /* ======================================================================
       3. CHUYỂN TAB THÔNG MINH
       ====================================================================== */
    function switchTab(tabId, elementText) {
        if(currentUser.role === 'employee' && ['history', 'customers', 'pricing'].includes(tabId)) {
            alert('Bạn không có quyền truy cập khu vực này!'); return;
        }

        document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.nav-item-custom').forEach(n => n.classList.remove('active'));
        document.getElementById('tab-' + tabId)?.classList.add('active');
        document.querySelector(`.nav-item-custom[data-tab="${tabId}"]`)?.classList.add('active');
        
        const pt = document.getElementById('pageTitle');
        if (pt && elementText) pt.innerText = elementText.replace(/<[^>]*>?/gm, '').trim();

        if (tabId === 'dashboard') loadDashboard();
        if (tabId === 'rooms') loadRooms();
        if (tabId === 'booking') loadBookingForm();
        if (tabId === 'history') loadHistory();
        if (tabId === 'customers') loadCustomers();
        if (tabId === 'pricing') loadPricing();

        if (window.innerWidth <= 991 && sidebar) { 
            sidebar.classList.remove('show'); 
            overlay.classList.remove('show'); 
        }
    }

    document.querySelectorAll('.nav-item-custom').forEach(item => {
        item.addEventListener('click', function(e) { 
            e.preventDefault(); 
            switchTab(this.getAttribute('data-tab'), this.innerText); 
        });
    });

    /* ======================================================================
       4. TỔNG QUAN & LỊCH SỬ ĐƠN HÀNG
       ====================================================================== */
    function loadDashboard() {
        fetch(`${API_URL}?action=dashboard_stats`).then(res => res.json()).then(data => {
            const sb = document.getElementById('stat-bookings');
            const sr = document.getElementById('stat-rooms');
            const srev = document.getElementById('stat-revenue');
            if(sb) sb.innerText = data.total_bookings || 0;
            if(sr) sr.innerText = data.available_rooms || 0;
            if(srev) srev.innerText = formatVND(data.total_revenue || 0);
        });

        fetch(`${API_URL}?action=get_recent_bookings`).then(res => res.json()).then(data => {
            let html = (!data || data.length === 0) ? '<tr><td colspan="6" class="text-center">Chưa có đơn</td></tr>' : '';
            if(data && data.length > 0) {
                data.forEach(b => {
                    let badge = '';
                    if (b.status === 'Completed') badge = '<span class="badge bg-success">Đã thanh toán</span>';
                    else if (b.status === 'Cancelled') badge = '<span class="badge bg-secondary">Huỷ bỏ</span>';
                    else badge = '<span class="badge bg-warning text-dark">Chưa thanh toán</span>';

                    let editBtn = '';
                    if (currentUser.role === 'admin') {
                        editBtn = `<i class="bi bi-pencil-square text-primary ms-2" style="cursor:pointer;" onclick="openEditStatus(${b.id}, '${b.booking_code}', '${b.status}')" title="Đổi trạng thái"></i>`;
                    }

                    html += `<tr><td class="fw-bold">${b.booking_code}</td><td>${b.customer_name}</td><td>P${b.room_number}</td><td>${b.room_type}</td><td>${b.check_in_date} - ${b.check_out_date}</td><td>${badge} ${editBtn}</td></tr>`;
                });
            }
            const rb = document.getElementById('recentBookingsBody');
            if(rb) rb.innerHTML = html;
        });
    }

    // CẬP NHẬT: Giao diện Cột Lịch Sử mới
    window.loadHistory = function() {
        fetch(`${API_URL}?action=get_all_bookings`).then(res => res.json()).then(data => {
            let html = (!data || data.length === 0) ? '<tr><td colspan="10" class="text-center py-4">Chưa có dữ liệu</td></tr>' : '';
            if(data && data.length > 0) {
                data.forEach(b => {
                    
                    // 1. Trạng thái Badge
                    let badge = '';
                    if (b.status === 'Completed') badge = '<span class="badge bg-success">Đã thanh toán</span>';
                    else if (b.status === 'Cancelled') badge = '<span class="badge bg-secondary">Huỷ bỏ</span>';
                    else badge = '<span class="badge bg-warning text-dark">Chưa thanh toán</span>';
                    
                    // 2. Phương thức thanh toán UI
                    let payMethod = '<span class="text-muted">-</span>';
                    if (b.payment_method === 'Cash/Transfer') {
                        payMethod = '<span class="fw-medium text-success"><i class="bi bi-cash-stack me-1"></i>Tại quầy</span>';
                    } else if (b.payment_method === 'Thẻ tín dụng') {
                        payMethod = '<span class="fw-medium text-primary"><i class="bi bi-credit-card me-1"></i>Thẻ tín dụng</span>';
                    } else if (b.payment_method === 'Ví MoMo') {
                        payMethod = '<span class="fw-medium" style="color: #a50064;"><i class="bi bi-wallet2 me-1"></i>MoMo</span>';
                    } else if (b.payment_method === 'Ví PayPal') {
                        payMethod = '<span class="fw-medium" style="color: #003087;"><i class="bi bi-paypal me-1"></i>PayPal</span>';
                    } else if (b.payment_method) {
                        payMethod = `<span class="fw-medium text-secondary">${b.payment_method}</span>`;
                    }
                    
                    // 3. Nút Hành động cho Admin
                    let actionBtns = '';
                    if (currentUser.role === 'admin') {
                         actionBtns = `<button class="btn btn-sm btn-outline-primary me-1" onclick="openEditStatus(${b.id}, '${b.booking_code}', '${b.status}')" title="Cập nhật trạng thái"><i class="bi bi-pencil"></i></button>
                                       <button class="btn btn-sm btn-outline-danger" onclick="deleteBooking(${b.id}, '${b.booking_code}')" title="Xoá vĩnh viễn"><i class="bi bi-trash"></i></button>`;
                    }

                    // 4. In Dòng Table
                    html += `<tr>
                                <td>${b.id}</td>
                                <td class="fw-bold">${b.booking_code}</td>
                                <td>${b.customer_name}</td>
                                <td><span class="badge bg-light text-dark border">P${b.room_number}</span></td>
                                <td>${b.check_in_date}</td>
                                <td>${b.check_out_date}</td>
                                <td class="text-success fw-bold">${formatVND(b.total_price)}</td>
                                <td>${payMethod}</td>
                                <td>${badge}</td>
                                <td class="text-end">${actionBtns}</td>
                             </tr>`;
                });
            }
            const hb = document.getElementById('historyBody');
            if(hb) hb.innerHTML = html;
        });
    }

    window.openEditStatus = function(id, code, status) {
        document.getElementById('esId').value = id;
        document.getElementById('esCodeLabel').innerText = `Cập nhật mã đơn: ${code}`;
        
        const statusSelect = document.getElementById('esStatus');
        if(statusSelect) {
            statusSelect.value = (status === 'Confirmed') ? 'Pending' : status;
        }
        new bootstrap.Modal(document.getElementById('editStatusModal')).show();
    };

    document.getElementById('editStatusForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = { 
            action: 'update_booking_status', 
            id: document.getElementById('esId').value, 
            status: document.getElementById('esStatus').value 
        };

        fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) })
            .then(res => res.json()).then(res => {
            if(res.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('editStatusModal')).hide();
                addLocalNotification('Đổi trạng thái đơn', `Trạng thái của đơn hàng đã được cập nhật thành công.`, 'bi-check-all', 'primary');
                loadDashboard();
                if(document.getElementById('tab-history').classList.contains('active')) loadHistory();
            } else {
                alert(res.message);
            }
        });
    });

    /* ======================================================================
       5. DANH SÁCH PHÒNG & BỘ LỌC TÌM KIẾM
       ====================================================================== */
    let currentRoomFilter = 'All';
    const filterRoomIn = document.getElementById('filterRoomIn');
    const filterRoomOut = document.getElementById('filterRoomOut');
    const btnFilterRooms = document.getElementById('btnFilterRoomsTime');
    
    if (filterRoomIn && filterRoomOut) {
        const todayStr = new Date().toISOString().split('T')[0];
        filterRoomIn.min = todayStr; filterRoomIn.value = todayStr;
        let tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        filterRoomOut.min = tomorrowStr; filterRoomOut.value = tomorrowStr;
        
        filterRoomIn.addEventListener('change', function () {
            filterRoomOut.min = this.value; 
            if (filterRoomOut.value <= this.value) {
                let next = new Date(this.value); next.setDate(next.getDate() + 1); 
                filterRoomOut.value = next.toISOString().split('T')[0];
            }
        });
    }

    if (btnFilterRooms) {
        btnFilterRooms.addEventListener('click', function() {
            let processingOverlay = document.getElementById('processingOverlay');
            if (!processingOverlay) {
                processingOverlay = document.createElement('div');
                processingOverlay.id = 'processingOverlay';
                processingOverlay.innerHTML = `
                    <div class="spinner-border" style="width: 3.5rem; height: 3.5rem; color: var(--primary-color);" role="status"></div>
                    <h5 class="text-white mt-4 fw-bold" style="letter-spacing: 1px;">Đang đồng bộ dữ liệu hệ thống...</h5>
                `;
                Object.assign(processingOverlay.style, {
                    position: 'fixed', top: '0', left: '0', width: '100%', height: '100%',
                    backgroundColor: 'rgba(26, 37, 47, 0.85)', zIndex: '9999',
                    display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center',
                    backdropFilter: 'blur(5px)'
                });
                document.body.appendChild(processingOverlay);
            }
            processingOverlay.style.display = 'flex';
            
            setTimeout(() => {
                loadRooms(() => { processingOverlay.style.display = 'none'; });
            }, 800);
        });
    }

    function loadRooms(callback) {
        const inDate = filterRoomIn?.value || '';
        const outDate = filterRoomOut?.value || '';
        let url = `${API_URL}?action=get_rooms`;
        if (inDate && outDate) { url += `&in=${inDate}&out=${outDate}`; }

        fetch(url).then(res => res.json()).then(data => {
            let html = '';
            if(!data || data.length === 0) {
                html = '<div class="col-12 text-center text-muted">Không có dữ liệu phòng</div>';
            } else {
                data.forEach(room => {
                    let statusText = 'Trống', badgeClass = 'bg-success';
                    if (room.current_status === 'Occupied') { statusText = 'Đang Thuê'; badgeClass = 'bg-danger'; } 
                    else if (room.current_status === 'Maintenance') { statusText = 'Bảo Trì'; badgeClass = 'bg-warning text-dark'; }
                    html += `
                        <div class="room-item shadow-sm" data-number="${room.room_number}" data-status="${room.current_status}">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge ${badgeClass}">${statusText}</span><span class="fw-bold text-secondary">P${room.room_number}</span>
                            </div>
                            <div class="text-muted small mb-2">${room.type_name}</div>
                            <div class="fw-bold border-top pt-2">${formatVND(room.base_price)}</div>
                        </div>`;
                });
            }
            const rc = document.getElementById('roomsContainer');
            if(rc) { rc.innerHTML = html; filterRooms(); }
        }).catch(err => console.error(err)).finally(() => {
            if (callback && typeof callback === 'function') callback();
        });
    }

    const filterChips = document.querySelectorAll('.filter-chip');
    filterChips.forEach(chip => {
        chip.addEventListener('click', function () {
            filterChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentRoomFilter = this.getAttribute('data-filter');
            filterRooms();
        });
    });

    document.getElementById('roomSearch')?.addEventListener('keyup', filterRooms);

    function filterRooms() {
        const term = document.getElementById('roomSearch')?.value.toLowerCase() || '';
        document.querySelectorAll('.room-item').forEach(r => {
            const num = r.getAttribute('data-number').toLowerCase();
            const status = r.getAttribute('data-status');
            r.style.display = (num.includes(term) && (currentRoomFilter === 'All' || status === currentRoomFilter)) ? 'block' : 'none';
        });
    }

    /* ======================================================================
       6. TẠO ĐƠN & TỰ TÍNH TỔNG TIỀN
       ====================================================================== */
    const cCheckIn = document.getElementById('cCheckIn'), cCheckOut = document.getElementById('cCheckOut');
    const cRoom = document.getElementById('cRoom'), cTotal = document.getElementById('cTotal');

    if (cCheckIn && cCheckOut) {
        const todayStr = new Date().toISOString().split('T')[0];
        cCheckIn.min = todayStr; cCheckIn.value = todayStr;
        let tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
        cCheckOut.min = todayStr; cCheckOut.value = tomorrow.toISOString().split('T')[0];

        cCheckIn.addEventListener('change', function () {
            cCheckOut.min = this.value;
            if (cCheckOut.value <= this.value) {
                let next = new Date(this.value); next.setDate(next.getDate() + 1); cCheckOut.value = next.toISOString().split('T')[0];
            }
            fetchAvailableRooms();
        });
        cCheckOut.addEventListener('change', fetchAvailableRooms);
    }

    function calculateTotal() {
        if (!cCheckIn || !cCheckOut || !cRoom || !cTotal) return;
        const inD = new Date(cCheckIn.value), outD = new Date(cCheckOut.value);
        const selected = cRoom.options[cRoom.selectedIndex];
        
        if (inD && outD && selected && selected.value) {
            const price = parseFloat(selected.getAttribute('data-price'));
            let days = Math.ceil((outD - inD) / (1000 * 3600 * 24));
            if (days <= 0) days = 1; 
            cTotal.value = days * price; 
        } else { cTotal.value = ''; }
    }

    cRoom?.addEventListener('change', calculateTotal);

    function fetchAvailableRooms() {
        if (!cCheckIn || !cCheckOut || !cRoom) return;
        cRoom.innerHTML = '<option value="">Đang kiểm tra CSDL...</option>'; cTotal.value = '';
        fetch(`${API_URL}?action=get_available_rooms&in=${cCheckIn.value}&out=${cCheckOut.value}`)
            .then(res => res.json()).then(data => {
                cRoom.innerHTML = '<option value="">-- Chọn phòng --</option>';
                data.forEach(r => cRoom.innerHTML += `<option value="${r.id}" data-price="${r.base_price}">P${r.room_number} - ${r.name} (${formatVND(r.base_price)}/đêm)</option>`);
            });
    }

    function loadBookingForm() { fetchAvailableRooms(); }

    document.getElementById('adminBookingForm')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
        btn.disabled = true;

        const postData = {
            action: 'create_booking', name: document.getElementById('cName').value, phone: document.getElementById('cPhone').value,
            email: document.getElementById('cEmail').value, room_id: cRoom.value, check_in: cCheckIn.value,
            check_out: cCheckOut.value, total: cTotal.value, payment_status: document.getElementById('cPaymentStatus').value
        };

        fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(postData) })
            .then(res => res.json()).then(res => {
                if (res.status === 'success') {
                    addLocalNotification('Đã tạo đơn thành công', `Hệ thống vừa ghi nhận mã ${res.booking_code}.`, 'bi-check-circle', 'success');
                    this.reset(); cCheckIn.value = new Date().toISOString().split('T')[0];
                    switchTab('dashboard', 'Tổng quan');
                } else alert('Lỗi: ' + res.message);
            }).finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    });

    /* ======================================================================
       7. QUẢN TRỊ: XỬ LÝ MODAL CONFIRM VÀ CRUD
       ====================================================================== */
    let confirmActionCallback = null;
    const confirmModalEl = document.getElementById('confirmModal');
    const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;

    window.showConfirm = function(title, message, callback) {
        if (!confirmModal) {
            if(confirm(message)) callback();
            return;
        }
        const ct = document.getElementById('confirmModalTitle');
        const cm = document.getElementById('confirmModalMessage');
        if(ct) ct.innerText = title;
        if(cm) cm.innerText = message;
        confirmActionCallback = callback;
        confirmModal.show();
    };

    document.getElementById('confirmModalBtn')?.addEventListener('click', function() {
        if (confirmActionCallback) confirmActionCallback();
        if(confirmModal) confirmModal.hide();
    });

    window.openEditUser = function(id, name, email, phone) {
        document.getElementById('euId').value = id; document.getElementById('euName').value = name;
        document.getElementById('euEmail').value = email; document.getElementById('euPhone').value = phone;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    };

    window.deleteUser = function(id, name) {
        showConfirm('Xác nhận xoá', `Bạn có chắc chắn muốn xoá khách hàng: ${name}?`, function() {
            fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({action: 'delete_user', id: id}) })
                .then(res => res.json()).then(res => {
                    if(res.status === 'success') {
                        addLocalNotification('Đã xoá tài khoản', `Người dùng ${name} đã bị gỡ.`, 'bi-trash', 'danger');
                        loadCustomers();
                    } else alert(res.message);
                });
        });
    };
    
    window.deleteBooking = function(id, code) {
        showConfirm('Xoá đơn đặt phòng', `Bạn có chắc chắn muốn xoá đơn ${code}? Dữ liệu sẽ mất vĩnh viễn.`, function() {
            fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({action: 'delete_booking', id: id}) })
                .then(res => res.json()).then(res => {
                    if(res.status === 'success') {
                        addLocalNotification('Đã xoá lịch sử đặt phòng', `Mã đơn ${code} đã được xoá.`, 'bi-trash', 'danger');
                        loadHistory();
                        loadDashboard(); 
                    } else alert(res.message);
                });
        });
    };

    document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = { action: 'update_user', id: document.getElementById('euId').value, name: document.getElementById('euName').value, phone: document.getElementById('euPhone').value };
        fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) }).then(res => res.json()).then(res => {
            if(res.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                addLocalNotification('Cập nhật tài khoản', `Hồ sơ khách hàng ${data.name} đã được sửa.`, 'bi-pencil-square', 'info');
                loadCustomers();
            }
        });
    });

    function loadCustomers() {
        fetch(`${API_URL}?action=get_customers`).then(res => res.json()).then(data => {
            let html = '';
            if(!data || data.length === 0) html = '<tr><td colspan=\"6\" class=\"text-center\">Trống</td></tr>';
            else {
                data.forEach(c => {
                    html += `<tr><td>#${c.id}</td><td class="fw-bold">${c.full_name}</td><td>${c.email}</td><td>${c.phone}</td><td>${c.created_at}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="openEditUser(${c.id}, '${c.full_name}', '${c.email}', '${c.phone}')"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${c.id}, '${c.full_name}')"><i class="bi bi-trash"></i></button>
                                </td></tr>`;
                });
            }
            const cb = document.getElementById('customersBody');
            if(cb) cb.innerHTML = html;
        });
    }

    /* ======================================================================
       8. QUẢN TRỊ: CÀI ĐẶT GIÁ PHÒNG
       ====================================================================== */
    window.openEditPrice = function(id, name, price) {
        document.getElementById('epId').value = id; document.getElementById('epNameLabel').innerText = name;
        document.getElementById('epPrice').value = price;
        new bootstrap.Modal(document.getElementById('editPriceModal')).show();
    };

    document.getElementById('editPriceForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = { action: 'update_room_price', id: document.getElementById('epId').value, price: document.getElementById('epPrice').value };
        fetch(API_URL, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) }).then(res => res.json()).then(res => {
            if(res.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('editPriceModal')).hide();
                addLocalNotification('Đổi giá thành công', `Giá phòng đã được cập nhật.`, 'bi-tag', 'primary');
                loadPricing();
            }
        });
    });

    function loadPricing() {
        fetch(`${API_URL}?action=get_room_types`).then(res => res.json()).then(data => {
            let html = '';
            if(!data || data.length === 0) html = '<tr><td colspan=\"5\" class=\"text-center\">Trống</td></tr>';
            else {
                data.forEach(t => {
                    html += `<tr><td>${t.id}</td><td class="fw-bold text-secondary">${t.name}</td><td>${t.capacity} Người</td><td class="text-success fw-bold">${formatVND(t.base_price)}</td>
                             <td class="text-end"><button class="btn btn-sm btn-outline-secondary" onclick="openEditPrice(${t.id}, '${t.name}', ${t.base_price})">Sửa giá</button></td></tr>`;
                });
            }
            const pb = document.getElementById('pricingBody');
            if(pb) pb.innerHTML = html;
        });
    }

    // KHỞI CHẠY DEFAULT
    switchTab('dashboard', 'Tổng quan');
});