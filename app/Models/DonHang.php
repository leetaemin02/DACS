<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang'; // Kết nối với bảng 'don_hang' trong DB

    protected $fillable = [ // Các trường được phép ghi dữ liệu
        'nguoi_dung_id',
        'thanh_toan_id',
        'ma_giam_gia_id',
        'tong_tien',
        'trang_thai',
        'dia_chi_giao_hang',
    ];
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
