-- =========================================================================
-- LUXURY HOTEL - ADVANCED DEPLOYMENT SCHEMA
-- Hướng dẫn: Mở phpMyAdmin -> Chọn tab SQL -> Copy & Paste toàn bộ mã này -> Nhấn Go
-- =========================================================================

-- Tắt kiểm tra khóa ngoại để tránh lỗi khi xóa hoặc ghi đè bảng cũ
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- =========================================================================
-- 1. XÓA VÀ TẠO LẠI CƠ SỞ DỮ LIỆU TỪ ĐẦU
-- =========================================================================
DROP DATABASE IF EXISTS `luxury_hotel_db`;
CREATE DATABASE `luxury_hotel_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `luxury_hotel_db`;

-- =========================================================================
-- 2. TẠO CẤU TRÚC CÁC BẢNG (ĐÃ TỐI ƯU & BỔ SUNG INDEX)
-- =========================================================================

-- Bảng Người dùng
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL COMMENT 'Mật khẩu đã mã hóa. Null nếu là khách đặt vãng lai không cần tài khoản',
  `role` enum('admin','employee','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_email` (`email`),
  INDEX `idx_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Hạng Phòng (Nâng cấp cấu trúc lưu trữ nội dung số)
CREATE TABLE `room_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL COMMENT 'Mô tả ngắn hiển thị dạng thẻ',
  `long_description` text DEFAULT NULL COMMENT 'Mô tả chi tiết trong trang phòng',
  `area` int(11) NOT NULL COMMENT 'Diện tích (m2)',
  `capacity` int(11) NOT NULL COMMENT 'Số khách tối đa',
  `base_price` decimal(12,2) NOT NULL COMMENT 'Giá phòng cơ bản (Dùng Decimal cho tài chính an toàn)',
  `amenities` json DEFAULT NULL COMMENT 'Danh sách tiện ích (Array JSON)',
  `images` json DEFAULT NULL COMMENT 'Danh sách link ảnh (Array JSON)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Phòng Vật Lý
CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL COMMENT 'Số phòng (VD: 101, 202)',
  `status` enum('Available','Maintenance','Occupied') DEFAULT 'Available',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_room_number` (`room_number`),
  FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Đơn Đặt Phòng
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(20) NOT NULL COMMENT 'Mã đặt phòng ngẫu nhiên dùng để tra cứu',
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(12,2) NOT NULL COMMENT 'Tổng tiền thanh toán cuối cùng',
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_booking_code` (`booking_code`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  INDEX `idx_dates` (`check_in_date`,`check_out_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Lịch sử Thanh toán
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `payment_method` enum('Credit Card','MoMo','PayPal','Bank Transfer','Cash') NOT NULL COMMENT 'Cổng thanh toán đa dạng',
  `amount` decimal(12,2) NOT NULL COMMENT 'Số tiền đã trả',
  `transaction_id` varchar(100) DEFAULT NULL COMMENT 'Mã đối soát từ cổng thanh toán đối tác',
  `payment_status` enum('Pending','Success','Failed') DEFAULT 'Pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  INDEX `idx_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================================
-- 4. CHÈN DỮ LIỆU MẶC ĐỊNH (SEED DATA)
-- =========================================================================

-- Chèn tài khoản (Bao gồm Admin, Lễ tân và Khách hàng Demo)
INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`) VALUES
(1, 'Quản trị viên', 'admin@luxuryhotel.com', '0999999999', '$2a$12$hZql6Sl4iSo4/4M.e19RueFojeLaCVRLhXYG7VjLvat2h/fSWOTUe', 'admin'),
(2, 'Nhân viên Lễ Tân', 'nhanvien1@luxuryhotel.com', '0901111111', '$2a$12$hZql6Sl4iSo4/4M.e19RueFojeLaCVRLhXYG7VjLvat2h/fSWOTUe', 'employee'),
(3, 'Khách hàng Demo', 'khachhang@gmail.com', '0903333333', '$2a$12$hZql6Sl4iSo4/4M.e19RueFojeLaCVRLhXYG7VjLvat2h/fSWOTUe', 'customer');

-- Chèn danh mục Hạng phòng (Dữ liệu chi tiết có mảng JSON hình ảnh và tiện ích)
INSERT INTO `room_types` (`id`, `name`, `description`, `long_description`, `area`, `capacity`, `base_price`, `amenities`, `images`) VALUES
(1, 'Phòng Deluxe Hướng Biển', 'Tận hưởng bình minh trên biển ngay từ ban công riêng. Trang bị giường đôi cỡ lớn và bồn tắm đứng.', 'Thức giấc cùng tiếng sóng vỗ và đón ánh bình minh tuyệt đẹp ngay từ ban công riêng tư của bạn. Phòng Deluxe Hướng Biển được thiết kế với tông màu đại dương mát mẻ, trang bị giường King-size êm ái, bồn tắm đứng vách kính sang trọng và khu vực ghế sofa đọc sách. Một trải nghiệm đánh thức mọi giác quan.', 45, 2, 2800000.00, '["Free Wifi", "Ban công view biển", "Bồn tắm đứng"]', '["https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1498503182468-3b51cbb6cb24?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80"]'),
(2, 'Phòng Suite Cao Cấp', 'Không gian rộng rãi với khu vực tiếp khách riêng biệt. Phòng tắm cẩm thạch trang bị bồn tắm jacuzzi thư giãn.', 'Biểu tượng của sự xa hoa và tinh tế. Suite Cao Cấp sở hữu không gian mở rộng lớn, phân chia rõ rệt giữa phòng khách tiếp tân sang trọng và phòng ngủ riêng tư. Điểm nhấn là phòng tắm ốp đá cẩm thạch Ý với bồn tắm Jacuzzi sục massage, cùng các đặc quyền VIP bao gồm bữa sáng tại phòng và quyền sử dụng Club Lounge.', 70, 2, 5500000.00, '["Ăn sáng", "Bồn tắm Jacuzzi", "Phòng khách riêng", "Minibar miễn phí"]', '["https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80"]'),
(3, 'Phòng Family Suite', 'Lựa chọn hoàn hảo cho gia đình. Hai phòng ngủ thông nhau, đảm bảo không gian chung ấm cúng và sự riêng tư tuyệt đối.', 'Không gian gắn kết hoàn hảo cho những kỳ nghỉ gia đình. Family Suite là sự kết hợp thông minh của hai phòng ngủ riêng biệt nhưng được kết nối qua một không gian sinh hoạt chung rộng rãi. Trẻ em sẽ yêu thích góc giải trí với Smart TV và máy chơi game, trong khi bố mẹ có thể tận hưởng sự thư giãn tuyệt đối tại quầy bar mini cao cấp trong phòng.', 90, 4, 7200000.00, '["2 Smart TV", "Phòng ngủ thông nhau", "Khu vui chơi trẻ em"]', '["https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80"]'),
(4, 'Phòng Standard Hướng Phố', 'Thiết kế tối giản, gọn gàng và đầy đủ tiện nghi. Sự lựa chọn tiết kiệm và hoàn hảo cho các chuyến công tác ngắn ngày.', 'Khám phá sự cân bằng hoàn hảo giữa phong cách thiết kế đương đại và sự tiện nghi tối ưu. Phòng Standard Hướng Phố mang đến không gian nghỉ ngơi thư thái với cửa sổ lớn đón ánh sáng tự nhiên, góc làm việc công thái học và phòng tắm hiện đại. Lựa chọn lý tưởng để nạp lại năng lượng sau một ngày dài khám phá thành phố.', 30, 2, 1500000.00, '["Điều hòa", "Góc làm việc", "Nước suối miễn phí"]', '["https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80", "https://images.unsplash.com/photo-1600566752355-35792bedcfea?auto=format&fit=crop&w=800&q=80"]');

-- Chèn danh sách Phòng thực tế
INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `status`) VALUES
(1, 1, '101', 'Available'),
(2, 1, '102', 'Available'),
(3, 1, '103', 'Available'),
(4, 2, '201', 'Available'),
(5, 2, '202', 'Available'),
(6, 3, '301', 'Available'),
(7, 4, '401', 'Available'),
(8, 4, '402', 'Available');

-- Bật lại kiểm tra khóa ngoại sau khi cài đặt xong
SET FOREIGN_KEY_CHECKS = 1;