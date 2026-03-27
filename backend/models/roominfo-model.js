document.addEventListener('DOMContentLoaded', () => {
    // 1. Nhận diện các vùng chứa (Container) trên giao diện
    const homeSwiperContainer = document.getElementById('homeRoomSwiperContainer');
    const featuredContainer = document.getElementById('featuredRoomContainer');
    const listContainer = document.getElementById('roomListContainer');
    const otherRoomsTitle = document.getElementById('otherRoomsTitle');

    // 2. Gọi API lấy dữ liệu phòng từ Backend
    fetch('./api/api-rooms.php') // Đảm bảo đúng tên file API của bạn
        .then(response => response.json())
        .then(result => {
            if (result.status !== 'success') {
                throw new Error(result.message);
            }

            // Xử lý dữ liệu JSON (Parse mảng hình ảnh và tiện ích)
            const rooms = result.data.map(room => ({
                ...room,
                images: typeof room.images === 'string' ? JSON.parse(room.images) : room.images,
                amenities: typeof room.amenities === 'string' ? JSON.parse(room.amenities) : room.amenities
            }));

            // 3. Phân luồng hiển thị tùy theo trang đang mở
            if (homeSwiperContainer) {
                renderHomeSwiper(rooms, homeSwiperContainer);
            }

            if (featuredContainer && listContainer) {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedRoomName = urlParams.get('room');

                const featuredRoom = rooms.find(r => r.name === selectedRoomName) || rooms[0];
                const otherRooms = rooms.filter(r => r.id !== featuredRoom.id);

                if (selectedRoomName && otherRoomsTitle) {
                    otherRoomsTitle.innerText = "Khám Phá Các Hạng Phòng Khác";
                }

                renderStaticFeaturedRoom(featuredRoom, featuredContainer);
                renderOtherRoomsGrid(otherRooms, listContainer);
            }
        })
        .catch(error => {
            console.error('Lỗi tải dữ liệu:', error);
            const errorHtml = `<p class="text-danger text-center"><i class="bi bi-exclamation-triangle-fill"></i> Không thể kết nối đến máy chủ. Vui lòng thử lại sau.</p>`;
            if (homeSwiperContainer) homeSwiperContainer.innerHTML = errorHtml;
            if (featuredContainer) featuredContainer.innerHTML = errorHtml;
        });
});

// ==========================================
// CÁC HÀM TIỆN ÍCH DÙNG CHUNG
// ==========================================

// Định dạng tiền tệ VNĐ
const formatPrice = (price) => {
    if (!price || isNaN(price)) return 'Liên hệ';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
};

// Hàm tạo HTML Thẻ Phòng Dọc chuẩn UI Kit (Dùng cho cả Swiper và Grid)
const createRoomCardHTML = (room) => {
    return `
        <div class="card room-card">
            <div class="room-card-img-wrapper">
                <!-- Áp dụng Nhãn trạng thái (Badge) từ UI Kit -->
                <span class="badge-theme badge-available badge-floating">
                    <i class="bi bi-people-fill me-1"></i> ${room.capacity} Khách
                </span>
                <img src="${room.images[0]}" class="room-card-img" alt="${room.name}">
            </div>
            <div class="room-card-body">
                <h4 class="room-title">${room.name}</h4>
                <div class="room-card-meta justify-content-start">
                    <span><i class="bi bi-arrows-fullscreen"></i> ${room.area}m²</span>
                    <span><i class="bi bi-star-fill text-warning"></i> 5.0</span>
                </div>
                <!-- Text-clamp inline để cắt chữ quá 2 dòng -->
                <p class="text-muted small flex-grow-1 mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    ${room.description}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.75rem;">Mỗi đêm từ</span>
                        <span class="fw-bold fs-5" style="color: var(--secondary-color);">${formatPrice(room.base_price)}</span>
                    </div>
                    <!-- Áp dụng Nút bấm phụ (Secondary Button) từ UI Kit -->
                    <a href="rooms.php?room=${encodeURIComponent(room.name)}" class="btn-secondary-theme">Chi tiết</a>
                </div>
            </div>
        </div>
    `;
};


// ==========================================
// HÀM RENDER CHO TỪNG KHU VỰC
// ==========================================

// 1. Render Swiper (Trang chủ)
function renderHomeSwiper(rooms, container) {
    // Gọi hàm helper để tạo HTML cho từng slide
    let slidesHTML = rooms.map(room => `<div class="swiper-slide h-auto">${createRoomCardHTML(room)}</div>`).join('');

    // Bọc HTML Swiper (Chỉ giữ lại CSS cấu trúc khung ngoài và nút điều hướng)
    container.innerHTML = `
        <style>
            .home-swiper-outer { position: relative; max-width: 1320px; margin: 0 auto; }
            .home-swiper-inner { padding: 0 75px; }
            .homeRoomSwiper { padding: 20px 15px 60px 15px !important; overflow: hidden !important; }
            .home-swiper-nav { background-color: #ffffff; width: 55px !important; height: 55px !important; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.15); color: var(--secondary-color) !important; transition: all 0.3s ease; }
            .home-swiper-nav:hover { background-color: var(--secondary-color); color: #ffffff !important; transform: scale(1.1); }
            .home-swiper-nav::after { font-size: 24px !important; font-weight: 900; }
            @media (max-width: 768px) {
                .home-swiper-nav { display: none !important; }
                .home-swiper-inner { padding: 0 !important; }
            }
        </style>
        
        <div class="home-swiper-outer">
            <div class="swiper-button-prev home-swiper-nav" style="left: 0;"></div>
            <div class="swiper-button-next home-swiper-nav" style="right: 0;"></div>
            <div class="home-swiper-inner">
                <div class="swiper homeRoomSwiper">
                    <div class="swiper-wrapper">${slidesHTML}</div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    `;

    // Khởi tạo Swiper JS
    new Swiper('.homeRoomSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        grabCursor: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true, dynamicBullets: true },
        navigation: { nextEl: '.home-swiper-outer .swiper-button-next', prevEl: '.home-swiper-outer .swiper-button-prev' },
        breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
    });
}

// 2. Render Khu vực xem chi tiết phòng tĩnh (Trang rooms.php)
function renderStaticFeaturedRoom(room, container) {
    let carouselIndicators = '';
    let carouselItems = '';

    // Xử lý hình ảnh
    if (room.images && room.images.length > 0) {
        room.images.forEach((img, i) => {
            const activeClass = i === 0 ? 'active' : '';
            const highResImg = img.replace('w=800', 'w=1600').replace('q=80', 'q=90');
            carouselIndicators += `<button type="button" data-bs-target="#previewSlider" data-bs-slide-to="${i}" class="${activeClass}"></button>`;
            carouselItems += `
                <div class="carousel-item ${activeClass} h-100">
                    <img src="${highResImg}" class="d-block w-100 h-100 featured-carousel-img" alt="${room.name}">
                </div>`;
        });
    }

    // Xử lý Tiện ích (Sử dụng class .badge-amenity từ UI Kit)
    let amenitiesHtml = '';
    if (room.amenities && room.amenities.length > 0) {
        room.amenities.forEach(am => {
            // Thay đổi icon linh hoạt hoặc dùng icon mặc định
            let icon = "bi-check-circle-fill";
            if(am.toLowerCase().includes('wifi')) icon = "bi-wifi";
            else if(am.toLowerCase().includes('tv')) icon = "bi-tv";
            else if(am.toLowerCase().includes('điều hoà') || am.toLowerCase().includes('ac')) icon = "bi-snow";

            amenitiesHtml += `<span class="badge-amenity py-2 px-3"><i class="bi ${icon} text-primary me-2"></i> ${am}</span>`;
        });
    }

    container.innerHTML = `
        <div class="card featured-room-card border-0 shadow-lg overflow-hidden rounded-4">
            <div class="row g-0">
                <div class="col-lg-7">
                    <div id="previewSlider" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
                        <div class="carousel-indicators">${carouselIndicators}</div>
                        <div class="carousel-inner h-100">${carouselItems}</div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#previewSlider" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#previewSlider" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                    </div>
                </div>
                <div class="col-lg-5 d-flex flex-column justify-content-center p-5">
                    <span class="text-uppercase small fw-bold mb-2" style="color: var(--primary-color); letter-spacing: 2px;">Thông tin chi tiết</span>
                    
                    <!-- Áp dụng tiêu đề kiểu 2 (Crimson Pro) từ UI Kit -->
                    <h2 class="luxury-title-2 fs-1 mb-4">${room.name}</h2>
                    
                    <!-- Meta info mượn từ UI Kit -->
                    <div class="room-card-meta mb-4 pb-4 border-bottom fs-6 justify-content-start gap-4">
                        <span><i class="bi bi-arrows-fullscreen"></i> ${room.area}m²</span>
                        <span><i class="bi bi-people"></i> ${room.capacity} Khách</span>
                    </div>
                    
                    <p class="text-muted mb-4" style="line-height: 1.6; font-size: 1.05rem;">${room.long_description || room.description}</p>
                    
                    <div class="mb-5">
                        <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Tiện ích nổi bật:</h6>
                        <div class="d-flex flex-wrap gap-1">
                            ${amenitiesHtml}
                        </div>
                    </div>

                    <div class="mt-auto d-flex flex-column flex-sm-row justify-content-between align-items-center bg-light p-4 rounded-4 border">
                        <div>
                            <span class="text-muted small d-block mb-1">Giá mỗi đêm từ</span>
                            <div class="fs-3 fw-bold" style="color: var(--primary-color);">
                                ${formatPrice(room.base_price)}
                            </div>
                        </div>
                        <!-- Áp dụng nút Primary từ UI Kit -->
                        <a href="booking.php?room=${encodeURIComponent(room.name)}" class="btn-custom btn-primary-theme px-5 py-3 mt-3 mt-sm-0 shadow-sm">
                            Đặt Ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// 3. Render Grid Danh sách phòng khác (Trang rooms.php)
function renderOtherRoomsGrid(rooms, container) {
    // Chỉ cần lặp mảng và gọi hàm Helper, HTML hoàn toàn vắng bóng CSS rác!
    container.innerHTML = rooms.map(room => `
        <div class="col-lg-4 col-md-6 mb-4">
            ${createRoomCardHTML(room)}
        </div>
    `).join('');
}