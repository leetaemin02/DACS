# Phân Chia Công Việc Đồ Án Website Bán Sách (4 Thành Viên)

Dưới đây là bảng phân chia công việc đề xuất để đảm bảo khối lượng công việc được cân bằng và logic cho nhóm 4 người.

## Thành Viên 1: Trưởng Nhóm & Admin Panel (Quản trị viên)
**Trách nhiệm chính:** Xây dựng trang quản trị (Backend nặng)
*   **Quản lý Sách (CRUD):**
    *   Hiển thị danh sách sách.
    *   Thêm sách mới (Upload hình ảnh, nhập giá, tác giả).
    *   Sửa thông tin sách.
    *   Xóa sách.
*   **Quản lý Đơn hàng:**
    *   Xem danh sách các đơn hàng mới.
    *   Cập nhật trạng thái đơn hàng (đang giao, hoàn thành, hủy).
*   **Quản lý Người dùng (Cơ bản):** Xem danh sách khách hàng.
*   **Thống kê (Nâng cao - nếu còn thời gian):** Doanh thu, sách bán chạy.

## Thành Viên 2: Giao Diện Chính & Trải Nghiệm Mua Sắm
**Trách nhiệm chính:** Frontend trang chủ & Chi tiết sản phẩm (Frontend nặng)
*   **Trang Chủ (Homepage):**
    *   Hiển thị sách nổi bật, sách mới.
    *   Thiết kế giao diện đẹp, responsive (Mobile/Desktop).
*   **Trang Chi Tiết Sách:**
    *   Hiển thị đầy đủ thông tin, giá, mô tả.
    *   Hiển thị "Sách liên quan".
*   **Tìm kiếm & Lọc:**
    *   Thanh tìm kiếm sách theo tên.
    *   (Tùy chọn) Lọc theo giá, thể loại.
*   **Thiết kế chung (Layout):** Đảm bảo Header, Footer, Menu nhất quán cho toàn bộ web.

## Thành Viên 3: Tài Khoản & Khách Hàng
**Trách nhiệm chính:** Xác thực & Trang cá nhân (Cân bằng Backend/Frontend)
*   **Đăng Ký (Registration):** Form đăng ký, validate dữ liệu, lưu vào CSDL.
*   **Đăng Nhập (Login):** Xử lý đăng nhập, ghi nhớ mật khẩu, Logout.
*   **Trang Cá Nhân (Profile):**
    *   Hiển thị thông tin cá nhân.
    *   Cập nhật thông tin (Tên, SĐT, Địa chỉ).
    *   Đổi mật khẩu.
*   **Lịch Sử Mua Hàng:** Hiển thị danh sách các đơn hàng user đã đặt xem trạng thái đơn hàng.

## Thành Viên 4: Giỏ Hàng & Thanh Toán (Order Flow)
**Trách nhiệm chính:** Xử lý nghiệp vụ đặt hàng (Logic phức tạp)
*   **Giỏ Hàng (Shopping Cart):**
    *   Thêm sản phẩm vào giỏ (Lưu vào database bảng `gio_hang`).
    *   Xem giỏ hàng, cập nhật số lượng, xóa sản phẩm.
    *   Tính tổng tiền tạm tính.
*   **Thanh Toán (Checkout):**
    *   Form điền thông tin giao hàng.
    *   Xử lý lưu đơn hàng (chuyển từ Giỏ hàng -> Đơn hàng `don_hang` & `chi_tiet_don_hang`).
    *   Chọn phương thức thanh toán.
*   **Xử lý Mã giảm giá (Nếu có):** Áp dụng coupon.

---

## Ghi Chú Kỹ Thuật
*   **Source Code:** Dùng chung Git để quản lý.
*   **Cơ sở dữ liệu:** Dùng chung file migration đã tạo.
*   **Quy tắc:** Không sửa trực tiếp file của người khác nếu không báo trước.
