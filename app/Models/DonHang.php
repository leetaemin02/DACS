<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang'; // Kết nối với bảng 'don_hang' trong DB

    // Định nghĩa các hằng số phương thức thanh toán
    const PAYMENT_VNPAY = 'VNPAY';
    const PAYMENT_COD = 'Thanh toán khi nhận hàng (COD)';

    // Định nghĩa các hằng số trạng thái đơn hàng
    const STATUS_PENDING = 'Chờ duyệt';
    const STATUS_PROCESSING = 'Đang xử lý';
    const STATUS_SHIPPING = 'Đang giao';
    const STATUS_COMPLETED = 'Đã hoàn thành';
    const STATUS_CANCELLED = 'Đã hủy';

    protected $fillable = [ // Các trường được phép ghi dữ liệu
        'nguoi_dung_id',
        'thanh_toan_id',
        'ma_giam_gia_id',
        'tong_tien',
        'trang_thai',
        'dia_chi_giao_hang',
    ];

    // Kiểm tra xem đơn hàng có thanh toán qua VNPAY không
    public function isVnPay()
    {
        return $this->thanhToan && $this->thanhToan->phuong_thuc === self::PAYMENT_VNPAY;
    }

    // Kiểm tra xem đơn hàng có thanh toán COD không
    public function isCod()
    {
        return $this->thanhToan && $this->thanhToan->phuong_thuc === self::PAYMENT_COD;
    }

    // Lấy tên phương thức thanh toán
    public function getPaymentMethodName()
    {
        return $this->thanhToan ? $this->thanhToan->phuong_thuc : 'Chưa xác định';
    }

    // Thiết lập mối quan hệ: Một đơn hàng thuộc về một người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
    }
    // Thiết lập mối quan hệ: Một đơn hàng thuộc về một phương thức thanh toán
    public function thanhToan()
    {
        return $this->belongsTo(ThanhToan::class, 'thanh_toan_id');
    }
    // Thiết lập mối quan hệ: Một đơn hàng có thể sử dụng một mã giảm giá
    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'ma_giam_gia_id');
    }
    // Thiết lập mối quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'don_hang_id');
    }
}
