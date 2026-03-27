/* ==========================================================================
   PAGE SCRIPT: CUSTOMER ACCOUNT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    const API_URL = './api/api-user.php';
    const formatVND = (num) => Number(num).toLocaleString('vi-VN') + 'đ';

    // 1. Kiểm tra đăng nhập
    const userStr = localStorage.getItem('luxury_user');
    if (!userStr) {
        window.location.href = 'index.html';
        return;
    }
    
    const currentUser = JSON.parse(userStr);
    const userEmail = currentUser.email;

    // Load Avatar & Tên chung
    document.getElementById('userAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.name)}&background=D4AF37&color=fff&size=150`;
    document.getElementById('userDisplayName').innerText = currentUser.name;

    // Lắng nghe URL params để tự động nhảy sang Tab lịch sử (nếu click từ menu thả xuống)
    const urlParams = new URLSearchParams(window.location.search);
    const targetTab = urlParams.get('tab');
    if (targetTab === 'history') {
        const triggerEl = document.querySelector('#v-pills-history-tab');
        if(triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
    }

    // 2. Fetch Thông tin cá nhân (Profile)
    function loadProfile() {
        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_profile', email: userEmail })
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                document.getElementById('profName').value = res.data.full_name;
                document.getElementById('profEmail').value = res.data.email;
                document.getElementById('profPhone').value = res.data.phone || '';
                document.getElementById('profJoinDate').innerText = res.data.join_date;
            }
        })
        .catch(err => console.error("Lỗi lấy thông tin: ", err));
    }

    // 3. Cập nhật Thông tin cá nhân
    const profileForm = document.getElementById('profileForm');
    if(profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveProfile');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang lưu...';
            btn.disabled = true;

            const newName = document.getElementById('profName').value;
            const newPhone = document.getElementById('profPhone').value;

            // Giả lập hệ thống xử lý mất 1.5s
            setTimeout(() => {
                fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_profile', email: userEmail, name: newName, phone: newPhone })
                })
                .then(res => res.json())
                .then(res => {
                    const toastEl = document.getElementById('accountToast');
                    const toastMsg = document.getElementById('accountToastMsg');

                    if(res.status === 'success') {
                        // Hiển thị Toast Thành công
                        if (toastEl && toastMsg) {
                            toastEl.className = 'toast align-items-center text-white border-0 bg-success';
                            toastMsg.innerHTML = res.message;
                            const toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        }

                        // Cập nhật lại localStorage để header đồng bộ tên mới
                        currentUser.name = newName;
                        localStorage.setItem('luxury_user', JSON.stringify(currentUser));
                        document.getElementById('userDisplayName').innerText = newName;
                        document.getElementById('userAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(newName)}&background=D4AF37&color=fff&size=150`;
                        
                        // Mẹo nhỏ: reload lại trang để Auth UI Navbar cập nhật tên mới sau khi kịp đọc thông báo
                        setTimeout(() => window.location.reload(), 1500); 
                    } else {
                        // Hiển thị Toast Lỗi
                        if (toastEl && toastMsg) {
                            toastEl.className = 'toast align-items-center text-white border-0 bg-danger';
                            toastMsg.innerHTML = res.message;
                            const toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        } else {
                            alert(res.message);
                        }
                    }
                })
                .catch(err => {
                    alert("Lỗi kết nối máy chủ");
                    console.error(err);
                })
                .finally(() => {
                    btn.innerHTML = 'Lưu Thay Đổi';
                    btn.disabled = false;
                });
            }, 1500);
        });
    }

    // 4. Fetch Lịch sử đặt phòng (History)
    function loadHistory() {
        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_history', email: userEmail })
        })
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('historyTableBody');
            if(!tbody) return;

            if(res.status === 'error' || !res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Bạn chưa có đơn đặt phòng nào.</td></tr>';
                return;
            }

            let html = '';
            res.data.forEach(b => {
                let badge = '';
                if (b.status === 'Completed') badge = '<span class="badge bg-success">Đã thanh toán</span>';
                else if (b.status === 'Cancelled') badge = '<span class="badge bg-secondary">Đã huỷ</span>';
                else badge = '<span class="badge bg-warning text-dark">Chờ thanh toán</span>';

                html += `
                    <tr>
                        <td class="fw-bold" style="color: var(--secondary-color);">${b.booking_code}</td>
                        <td>
                            <span class="fw-bold text-dark d-block">P${b.room_number}</span>
                            <span class="text-muted small">${b.room_type}</span>
                        </td>
                        <td>${b.check_in_date}</td>
                        <td>${b.check_out_date}</td>
                        <td class="fw-bold text-success">${formatVND(b.total_price)}</td>
                        <td>${badge}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        })
        .catch(err => console.error("Lỗi lấy lịch sử: ", err));
    }

    // Khởi chạy load data
    loadProfile();
    loadHistory();
});