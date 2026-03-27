<!-- CHAT ICON -->
<div class="chat-widget-btn" id="chatBtn">
    <i class="bi bi-chat-dots-fill"></i>
</div>

<!-- CHAT BOX -->
<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <div class="chat-header-title">
            <i class="bi bi-headset"></i> Trợ lý Luxury Hotel
        </div>
        <i class="bi bi-x-lg close-chat"></i>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="chat-msg bot">
            Kính chào quý khách! Mình là trợ lý ảo của Luxury Hotel. Mình có thể hỗ trợ gì cho kỳ nghỉ của quý khách
            ạ? (Gợi ý: Hỏi về <strong>giá phòng</strong>, <strong>địa chỉ</strong>, hoặc <strong>dịch vụ</strong>)
        </div>
        <div class="typing-indicator" id="typingIndicator">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
    <div class="chat-footer">
        <input type="text" class="chat-input" id="chatInput" placeholder="Nhập tin nhắn...">
        <button class="chat-send-btn"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<style>
    .chat-widget-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .chat-widget-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.6);
    }

    /* --- 2. CỬA SỔ CHAT CHÍNH (Chat Window) --- */
    .chat-window {
        position: fixed;
        bottom: 100px;
        right: 30px;
        z-index: 1050;
        width: 360px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        display: none;
        /* Mặc định ẩn, điều khiển bằng JS */
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        animation: slideUp 0.3s ease-out;
    }

    /* --- 3. PHẦN ĐẦU (Header) --- */
    .chat-header {
        background: var(--secondary-color);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .chat-header-title i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    .close-chat {
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.2s;
    }

    .close-chat:hover {
        color: var(--primary-color);
    }

    /* --- 4. KHU VỰC HIỂN THỊ TIN NHẮN --- */
    .chat-body {
        padding: 20px;
        height: 350px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Tùy chỉnh thanh cuộn cho chat */
    .chat-body::-webkit-scrollbar {
        width: 5px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 5px;
    }

    /* Định dạng các bóng tin nhắn (Message Bubbles) */
    .chat-msg {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.9rem;
        line-height: 1.5;
        animation: fadeIn 0.3s ease;
    }

    .chat-msg.bot {
        background: white;
        color: var(--text-main);
        border: 1px solid #e2e8f0;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }

    .chat-msg.user {
        background: var(--primary-color);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 10px rgba(212, 175, 55, 0.2);
    }

    .chat-msg a {
        color: var(--primary-hover);
        font-weight: 600;
    }

    /* --- 5. KHU VỰC NHẬP LIỆU --- */
    .chat-footer {
        padding: 15px;
        background: white;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 10px 15px;
        outline: none;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .chat-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .chat-send-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: 0.3s;
    }

    .chat-send-btn:hover {
        background: var(--primary-hover);
        transform: translateX(2px) scale(1.05);
    }

    /* --- 6. HIỆU ỨNG GÕ CHỮ --- */
    .typing-indicator {
        display: none;
        align-items: center;
        gap: 4px;
        padding: 12px 16px;
        background: white;
        border: 1px solid #e2e8f0;
        align-self: flex-start;
        border-radius: 16px;
        border-bottom-left-radius: 4px;
    }

    .dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out both;
    }

    .dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .dot:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes typing {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* --- 7. RESPONSIVE (Cho điện thoại) --- */
    @media (max-width: 576px) {
        .chat-window {
            right: 15px;
            left: 15px;
            bottom: 90px;
            width: auto;
        }

        .chat-widget-btn {
            right: 20px;
            bottom: 20px;
            width: 55px;
            height: 55px;
        }
    }
</style>