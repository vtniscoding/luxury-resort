/* ==========================================================================
   COMPONENT: CHATBOX SCRIPT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* --- 1. DOM ELEMENTS (Khởi tạo các thành phần giao diện) --- */
    const chatBtn = document.getElementById('chatBtn');
    const chatWindow = document.getElementById('chatWindow');
    const closeBtn = document.querySelector('.close-chat');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.querySelector('.chat-send-btn');
    const typingIndicator = document.getElementById('typingIndicator');

    /* --- 2. KỊCH BẢN TRẢ LỜI (Bot Scenarios) --- */
    // Dễ dàng mở rộng thêm các từ khóa và câu trả lời tại đây
    const botScenarios = [
        {
            keywords: ['giá', 'phòng', 'bao nhiêu', 'tiền'],
            response: "Dạ, hệ thống phòng bên em bắt đầu từ <strong>900.000đ/đêm</strong> đối với phòng Standard. Quý khách có thể truy cập mục <a href='rooms.html'>Phòng & Giá</a> ở thanh menu để xem chi tiết nhé!"
        },
        {
            keywords: ['địa chỉ', 'ở đâu', 'vị trí', 'bản đồ'],
            response: "Dạ, Luxury Hotel nằm tại địa chỉ: <strong>123 Đường Biển, Thành phố Nghỉ Dưỡng, Việt Nam</strong>. Khách sạn cách sân bay chỉ 15 phút di chuyển ạ."
        },
        {
            keywords: ['dịch vụ', 'tiện ích', 'ăn sáng', 'hồ bơi', 'spa'],
            response: "Khách sạn cung cấp đầy đủ dịch vụ chuẩn 5 sao bao gồm: Hồ bơi vô cực, Spa & Massage, Nhà hàng sao Michelin và Buffet sáng miễn phí cho mọi hạng phòng ạ."
        },
        {
            keywords: ['chào', 'hello', 'hi', 'xin chào'],
            response: "Dạ em chào quý khách! Em có thể giúp gì cho kỳ nghỉ sắp tới của quý khách ạ?"
        },

        {
            keywords: ['thực đơn', 'đồ ăn', 'nhà hàng', 'bar'],
            response: "Dạ, quý khách có thể tham khảo qua thực đơn phục vụ nhà hàng & quầy bar bên em tại đây: <a href='menu.php'>Thực Đơn Phục Vụ & Quầy Bar</a>"
        },

        {
            keywords: ['cảm ơn', 'thanks', 'tks', 'ok'],
            response: "Dạ, rất hân hạnh được hỗ trợ quý khách. Chúc quý khách một ngày tốt lành!"
        }
    ];

    const defaultResponse = "Dạ em chưa hiểu ý của quý khách lắm. Quý khách vui lòng để lại Số Điện Thoại, lễ tân bên em sẽ gọi lại tư vấn trực tiếp, hoặc quý khách có thể gọi Hotline <strong>1900 1234</strong> nhé!";

    /* --- 3. HÀM XỬ LÝ GIAO DIỆN (UI Functions) --- */
    const toggleChat = () => {
        if (!chatWindow) return;

        if (chatWindow.style.display === 'flex') {
            chatWindow.style.display = 'none';
        } else {
            chatWindow.style.display = 'flex';
            if (chatInput) chatInput.focus();
        }
    };

    const scrollToBottom = () => {
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    };

    const appendMessage = (text, sender) => {
        const msgDiv = document.createElement('div');
        msgDiv.classList.add('chat-msg', sender);
        msgDiv.innerHTML = text;

        // Chèn tin nhắn mới lên phía trên cái hiệu ứng "đang gõ..."
        chatBody.insertBefore(msgDiv, typingIndicator);
        scrollToBottom();
    };

    /* --- 4. LOGIC XỬ LÝ TIN NHẮN (Core Logic) --- */
    const getBotResponse = (text) => {
        const lowerText = text.toLowerCase();

        // Duyệt qua từng kịch bản, nếu câu của khách chứa từ khóa thì trả về câu tương ứng
        for (let scenario of botScenarios) {
            if (scenario.keywords.some(keyword => lowerText.includes(keyword))) {
                return scenario.response;
            }
        }
        return defaultResponse;
    };

    const sendMessage = () => {
        const message = chatInput.value.trim();
        if (message === '') return;

        // 1. Hiển thị tin nhắn của khách
        appendMessage(message, 'user');
        chatInput.value = '';

        // 2. Hiện hiệu ứng bot đang gõ chữ
        typingIndicator.style.display = 'flex';
        scrollToBottom();

        // 3. Phản hồi sau 1 giây (Tạo cảm giác giống người thật đang rep)
        setTimeout(() => {
            typingIndicator.style.display = 'none';
            const botResponse = getBotResponse(message);
            appendMessage(botResponse, 'bot');
        }, 1000);
    };

    /* --- 5. GẮN SỰ KIỆN (Event Listeners) --- */
    if (chatBtn) chatBtn.addEventListener('click', toggleChat);
    if (closeBtn) closeBtn.addEventListener('click', toggleChat);
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);

    if (chatInput) {
        // Lắng nghe phím Enter
        chatInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Ngăn hành vi xuống dòng mặc định
                sendMessage();
            }
        });
    }
});