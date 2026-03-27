/* ==========================================================================
   PAGE SCRIPT: BOOKING
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const ui = {
        form: document.getElementById('bookSearchForm'),
        checkIn: document.getElementById('searchIn'),
        checkOut: document.getElementById('searchOut'),
        capacity: document.getElementById('searchCap'),
        roomType: document.getElementById('searchRoomType'),
        title: document.getElementById('dynamicTitle'),
        roomCards: document.querySelectorAll('.room-card'),
        resultHeader: document.getElementById('searchResultHeader'),
        roomListContainer: document.getElementById('roomListContainer')
    };

    const roomCapacities = {
        'Phòng Deluxe Hướng Biển': 2,
        'Phòng Suite Cao Cấp': 2,
        'Phòng Family Suite': 4,
        'Phòng Standard Hướng Phố': 2
    };

    const urlParams = new URLSearchParams(window.location.search);
    let targetRoom = urlParams.get('room') || 'all';
    const reqIn = urlParams.get('in');
    const reqOut = urlParams.get('out');
    const reqCap = urlParams.get('capacity');

    let isInitialLoad = true;

    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const formatDateForInput = (date) => date.toISOString().split('T')[0];
    const formatDateForDisplay = (dateStr) => {
        const [y, m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    };

    if (ui.resultHeader) ui.resultHeader.style.display = 'none';
    if (ui.roomListContainer) ui.roomListContainer.style.display = 'none';

    if (ui.checkIn && ui.checkOut) {
        ui.checkIn.min = formatDateForInput(today);
        ui.checkIn.value = reqIn || formatDateForInput(today);

        ui.checkOut.min = ui.checkIn.value;
        ui.checkOut.value = reqOut || formatDateForInput(tomorrow);

        if (reqCap && ui.capacity) ui.capacity.value = reqCap;

        if (ui.roomType) {
            const options = Array.from(ui.roomType.options).map(opt => opt.value);
            if (options.includes(targetRoom)) {
                ui.roomType.value = targetRoom;
            } else {
                ui.roomType.value = 'all';
            }
        }

        ui.checkIn.addEventListener('change', function () {
            ui.checkOut.min = this.value;
            if (ui.checkOut.value <= this.value) {
                let nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                ui.checkOut.value = formatDateForInput(nextDay);
            }
        });
    }

    if (ui.form) {
        ui.form.addEventListener('submit', function (e) {
            e.preventDefault();
            isInitialLoad = false;
            window.searchRooms();
        });
    }

    window.searchRooms = function () {
        if (ui.resultHeader) ui.resultHeader.style.display = 'block';
        if (ui.roomListContainer) ui.roomListContainer.style.display = 'flex';

        const inDate = ui.checkIn.value;
        const outDate = ui.checkOut.value;
        const requestedCap = parseInt(ui.capacity ? ui.capacity.value : 2);

        if (!isInitialLoad && ui.roomType) {
            targetRoom = ui.roomType.value;
        }

        if (ui.title) {
            ui.title.innerText = (targetRoom && targetRoom !== 'all')
                ? `Kết quả cho: ${targetRoom}`
                : `Phòng trống từ ${formatDateForDisplay(inDate)} - ${formatDateForDisplay(outDate)}`;
        }

        ui.roomCards.forEach(card => {
            const badge = card.querySelector('.badge-theme');
            const btn = card.querySelector('.book-action-btn');
            const priceEl = card.querySelector('.room-price');

            if (badge) {
                badge.className = 'badge-theme badge-available mb-2';
                badge.style.background = 'rgba(26, 37, 47, 0.8)';
                badge.style.color = 'white';
                badge.innerHTML = `<span class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></span> Đang tải CSDL...`;
            }
            if (btn) {
                btn.disabled = true;
                btn.className = 'btn-custom btn-secondary px-4 py-2 book-action-btn';
                btn.innerText = 'Đang xử lý';
            }
            if (priceEl) {
                // Hiển thị vòng quay loading vào phần giá tiền
                priceEl.innerHTML = `<span class="spinner-border spinner-border-sm text-secondary"></span>`;
            }
        });

        let newUrl = `${window.location.pathname}?in=${inDate}&out=${outDate}&capacity=${requestedCap}`;
        if (targetRoom && targetRoom !== 'all') {
            newUrl += `&room=${encodeURIComponent(targetRoom)}`;
        }

        if (isInitialLoad) {
            window.history.replaceState({ path: newUrl }, '', newUrl);
        } else {
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        fetch(`./api/api-rooms.php?in=${inDate}&out=${outDate}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    console.error("Lỗi từ máy chủ:", data.error);
                    updateRoomUI([], requestedCap);
                } else {
                    updateRoomUI(data, requestedCap);
                }
            })
            .catch(err => {
                console.error("Lỗi kết nối API:", err);
                alert("Không thể kết nối đến cơ sở dữ liệu. Vui lòng thử lại sau.");
                updateRoomUI([], requestedCap);
            });
    };

    function updateRoomUI(apiData, requestedCap) {
        let hasVisibleRooms = false;

        ui.roomCards.forEach(card => {
            const roomNameElem = card.querySelector('.room-title');
            if (!roomNameElem) return;
            const roomName = roomNameElem.innerText.trim();
            const cardWrapper = card.closest('.col-lg-6');

            // --- TÌM DỮ LIỆU PHÒNG TỪ API ---
            const roomData = apiData.find(r => r.name === roomName);
            const availableCount = roomData ? parseInt(roomData.available_count) : 0;
            const maintainCount = roomData ? parseInt(roomData.maintain_count) : 0;
            
            // CẬP NHẬT: Lấy giá từ Database thay vì lấy từ HTML
            const dbPrice = roomData ? parseInt(roomData.base_price) : 0;

            const roomCap = roomCapacities[roomName] || 2;
            const isRoomTypeMatch = !(targetRoom && targetRoom !== 'all' && roomName !== targetRoom);
            const isCapacityMatch = requestedCap <= roomCap;

            if (!isRoomTypeMatch || !isCapacityMatch) {
                if (cardWrapper) cardWrapper.style.display = 'none';
                return;
            } else {
                if (cardWrapper) cardWrapper.style.display = 'block';
                hasVisibleRooms = true;
            }

            const badge = card.querySelector('.badge-theme');
            const btn = card.querySelector('.book-action-btn');
            const priceEl = card.querySelector('.room-price');
            const img = card.querySelector('.room-card-img').src;

            // --- 1. HIỂN THỊ GIÁ TIỀN ĐỘNG ---
            if (priceEl) {
                if (dbPrice > 0) {
                    priceEl.innerHTML = `${dbPrice.toLocaleString('vi-VN')}₫ <span class="text-muted fw-normal" style="font-size: 0.8rem;">/ đêm</span>`;
                } else {
                    priceEl.innerHTML = `<span class="text-danger small">Lỗi hiển thị giá</span>`;
                }
            }

            // --- 2. KIỂM TRA TRẠNG THÁI ƯU TIÊN ---
            
            // Ưu tiên 1: CÓ phòng trống
            if (availableCount > 0) {
                if (badge) {
                    badge.className = 'badge-theme badge-available mb-2';
                    badge.style.background = 'rgba(212, 175, 55, 0.15)';
                    badge.style.color = 'var(--primary-hover)';
                    badge.innerHTML = `<i class="bi bi-check2-circle me-1"></i> Còn ${availableCount} phòng`;
                }

                if (btn) {
                    btn.disabled = false;
                    btn.className = 'btn-custom btn-primary-theme px-4 py-2 book-action-btn';
                    btn.innerText = 'Đặt ngay';
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    // Truyền dbPrice từ CSDL thay vì giá ảo
                    btn.onclick = function () { bookRoom(roomName, dbPrice, img); };
                }
            } 
            // Ưu tiên 2: Hết phòng trống, CÓ phòng bảo trì
            else if (maintainCount > 0) {
                if (badge) {
                    badge.className = 'badge-theme badge-maintain mb-2';
                    badge.style.background = 'rgba(255, 193, 7, 0.15)';
                    badge.style.color = '#d39e00';
                    badge.innerHTML = `<i class="bi bi-tools me-1"></i> Đang bảo trì`;
                }

                if (btn) {
                    btn.disabled = true;
                    btn.className = 'btn-custom btn-outline-warning px-4 py-2 book-action-btn text-dark';
                    btn.innerText = 'Bảo trì';
                    btn.style.opacity = '0.6';
                    btn.style.cursor = 'not-allowed';
                    btn.onclick = null;
                }
            } 
            // Ưu tiên 3: Kín lịch
            else {
                if (badge) {
                    badge.className = 'badge-theme badge-booked mb-2';
                    badge.style.background = 'rgba(239, 68, 68, 0.1)';
                    badge.style.color = '#ef4444';
                    badge.innerHTML = `<i class="bi bi-x-circle me-1"></i> Kín lịch`;
                }

                if (btn) {
                    btn.disabled = true;
                    btn.className = 'btn-custom btn-outline-secondary px-4 py-2 book-action-btn';
                    btn.innerText = 'Hết phòng';
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                    btn.onclick = null;
                }
            }
        });

        // Xử lý cuộn tự động lần đầu
        if (isInitialLoad) {
            const overlay = document.getElementById('pageLoadingOverlay');
            if (overlay && overlay.style.display === 'flex') {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'auto' });
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            if (ui.form && (targetRoom && targetRoom !== 'all' || reqIn)) {
                setTimeout(() => {
                    const yOffset = -120;
                    const y = ui.form.getBoundingClientRect().top + window.scrollY + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }, 50);
            }
            isInitialLoad = false;
        }
    }

    function bookRoom(name, price, img) {
        localStorage.setItem('selectedRoomName', name);
        localStorage.setItem('selectedRoomPrice', price);
        localStorage.setItem('selectedRoomImg', img);
        localStorage.setItem('searchCheckIn', ui.checkIn.value);
        localStorage.setItem('searchCheckOut', ui.checkOut.value);
        window.location.href = 'checkout.php';
    }

    /* --- KHỞI CHẠY --- */
    if (reqIn || reqOut || reqCap || (targetRoom && targetRoom !== 'all')) {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => { window.searchRooms(); }, 700);
        } else {
            window.searchRooms();
        }
    }
});