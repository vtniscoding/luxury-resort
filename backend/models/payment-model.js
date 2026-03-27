/* ==========================================================================
   PAGE SCRIPT: CHECKOUT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    
    /* --- 1. BIẾN TRẠNG THÁI & LẤY DỮ LIỆU TỪ LOCAL STORAGE --- */
    const roomName = localStorage.getItem('selectedRoomName');
    const pricePerNight = parseInt(localStorage.getItem('selectedRoomPrice'));
    const roomImg = localStorage.getItem('selectedRoomImg');
    
    const savedCheckIn = localStorage.getItem('searchCheckIn');
    const savedCheckOut = localStorage.getItem('searchCheckOut');
    
    let isMomoProcessing = false;

    /* --- 2. GOM NHÓM CÁC THÀNH PHẦN GIAO DIỆN (DOM ELEMENTS) --- */
    const ui = {
        form: document.getElementById('checkoutForm'),
        checkin: document.getElementById('checkinInput'),
        checkout: document.getElementById('checkoutInput'),
        payButton: document.getElementById('payButton'),
        ccDetails: document.getElementById('cardDetails'),
        overlay: document.getElementById('paymentProcessingOverlay'),
        
        // Form Thông tin
        fullName: document.getElementById('fullName'),
        email: document.getElementById('email'),
        phone: document.getElementById('phone'),
        
        // Cột Tóm tắt
        summaryName: document.getElementById('summaryName'),
        summaryImg: document.getElementById('summaryImg'),
        summaryCheckin: document.getElementById('summaryCheckin'),
        summaryCheckout: document.getElementById('summaryCheckout'),
        summaryDays: document.getElementById('summaryDaysText'),
        summaryPrice: document.getElementById('summaryPrice'),
        summaryTax: document.getElementById('summaryTax'),
        summaryTotal: document.getElementById('summaryTotal'),
        
        // Modal & Thông báo
        momoAmount: document.getElementById('momoTotalAmount'),
        bookingCodeDisplay: document.getElementById('bookingCodeDisplay'),
        emailNotificationArea: document.getElementById('emailNotificationArea')
    };

    const modals = {
        qr: new bootstrap.Modal(document.getElementById('momoQrModal')),
        success: new bootstrap.Modal(document.getElementById('paymentSuccessModal'))
    };

    /* --- Tự động điền nếu đã đăng nhập --- */
    const userStr = localStorage.getItem('luxury_user');
    if(userStr) {
        const user = JSON.parse(userStr);
        if(ui.fullName && user.name) ui.fullName.value = user.name;
        if(ui.email && user.email) ui.email.value = user.email;
    }

    /* --- 3. HÀM TIỆN ÍCH (UTILITIES) --- */
    const formatVND = (number) => number.toLocaleString('vi-VN') + '₫';
    const formatDateForInput = (date) => new Date(date).toISOString().split('T')[0];
    const formatDateForDisplay = (dateStr) => {
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    };

    /* --- 4. KHỞI TẠO GIAO DIỆN MẶC ĐỊNH --- */
    const initDefaultValues = () => {
        if (ui.summaryName) ui.summaryName.innerText = roomName;
        if (ui.summaryImg) ui.summaryImg.src = roomImg;

        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        if (ui.checkin && ui.checkout) {
            ui.checkin.min = formatDateForInput(today);
            ui.checkin.value = savedCheckIn || formatDateForInput(today);
            ui.checkout.min = ui.checkin.value;
            ui.checkout.value = savedCheckOut || formatDateForInput(tomorrow);
        }
    };

    /* --- 5. LOGIC TÍNH TOÁN TIỀN PHÒNG (CHỈ ĐỂ HIỂN THỊ UI) --- */
    const updateCalculation = () => {
        if (!ui.checkin || !ui.checkout) return;

        const start = new Date(ui.checkin.value);
        const end = new Date(ui.checkout.value);

        let nights = Math.ceil((end - start) / (1000 * 3600 * 24));
        if (nights <= 0) nights = 1;

        const roomTotal = pricePerNight * nights;
        const tax = roomTotal * 0.1; 
        const visualTotalAmount = roomTotal + tax;

        ui.summaryCheckin.innerText = formatDateForDisplay(ui.checkin.value);
        ui.summaryCheckout.innerText = formatDateForDisplay(ui.checkout.value);
        ui.summaryDays.innerText = `(${nights} ngày)`;
        ui.summaryPrice.innerText = formatVND(roomTotal);
        ui.summaryTax.innerText = formatVND(tax);
        ui.summaryTotal.innerText = formatVND(visualTotalAmount);
        ui.momoAmount.innerText = formatVND(visualTotalAmount);
    };

    /* --- 6. LOGIC GỌI API BACK-END --- */
    const processBookingAPI = (methodName) => {
        const orderData = {
            fullName: ui.fullName.value,
            email: ui.email.value,
            phone: ui.phone.value,
            checkIn: ui.checkin.value,
            checkOut: ui.checkout.value,
            roomName: roomName,
            paymentMethod: methodName
        };

        fetch('./api/api-checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            ui.overlay.style.display = 'none';
            modals.qr.hide();

            if (data.status === 'success') {
                ui.bookingCodeDisplay.innerText = data.booking_code;
                ui.emailNotificationArea.style.display = 'block';
                modals.success.show();
            } else {
                alert("Lỗi từ hệ thống: " + data.message);
                resetPayButton();
            }
        })
        .catch(error => {
            console.error('Lỗi API:', error);
            ui.overlay.style.display = 'none';
            modals.qr.hide();
            alert("Lỗi kết nối máy chủ hoặc API chưa được thiết lập chuẩn.");
            resetPayButton();
        });
    };

    const resetPayButton = () => {
        ui.payButton.innerHTML = '<i class="bi bi-lock-fill me-2"></i> Xác Nhận & Thanh Toán';
        ui.payButton.disabled = false;
    };

    /* --- 7. LẮNG NGHE SỰ KIỆN (EVENT LISTENERS) --- */
    if (ui.checkin && ui.checkout) {
        ui.checkin.addEventListener('change', function () {
            if (ui.checkin.value >= ui.checkout.value) {
                let nextDay = new Date(ui.checkin.value);
                nextDay.setDate(nextDay.getDate() + 1);
                ui.checkout.value = formatDateForInput(nextDay);
            }
            ui.checkout.min = ui.checkin.value;
            updateCalculation();
        });
        ui.checkout.addEventListener('change', updateCalculation);
    }

    // Khi thay đổi Phương thức thanh toán
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const isCard = this.value === 'card';
            if (ui.ccDetails) {
                ui.ccDetails.style.display = isCard ? 'block' : 'none';
            }
            const cardInputs = ['ccNumber', 'ccExpiry', 'ccCVV'];
            cardInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) input.required = isCard;
            });
        });
    });

    // Khi Submit Form Thanh toán
    if (ui.form) {
        ui.form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!ui.form.checkValidity()) {
                event.stopPropagation();
                ui.form.classList.add('was-validated');
                return;
            }

            const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            let paymentString = 'Thẻ tín dụng';
            if(selectedMethod === 'momo') paymentString = 'Ví MoMo';
            if(selectedMethod === 'paypal') paymentString = 'Ví PayPal';

            if (selectedMethod === 'momo') {
                modals.qr.show();
                if(!isMomoProcessing) {
                    isMomoProcessing = true;
                    setTimeout(() => {
                        if(document.getElementById('momoQrModal').classList.contains('show')) {
                            processBookingAPI(paymentString);
                        }
                        isMomoProcessing = false;
                    }, 4500); 
                }
            } 
            else {
                ui.overlay.style.display = 'flex';
                setTimeout(() => processBookingAPI(paymentString), 2000); 
            }
        });
    }

    /* --- 8. KHỞI CHẠY LẦN ĐẦU --- */
    initDefaultValues();
    updateCalculation();
});