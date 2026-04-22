<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sach extends Model
{
    use HasFactory;

    protected $table = 'sach'; // Kết nối với bảng 'sach' trong DB

    protected $fillable = [ // Các trường được phép ghi dữ liệu
        'ten_sach',
        'the_loai',
        'tac_gia',
        'mo_ta',
        'gia',
        'so_luong',
        'hinh_anh',
    ];
    // Thiết lập mối quan hệ: Một cuốn sách có thể nằm trong nhiều chi tiết đơn hàng
    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'sach_id');
    }

    // Thiết lập mối quan hệ: Một cuốn sách có thể nằm trong nhiều giỏ hàng
    public function gioHangs()
    {
        return $this->hasMany(GioHang::class, 'sach_id');
    }

    // Thiết lập mối quan hệ: Một cuốn sách có thể có nhiều đánh giá
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'sach_id');
    }

    // Thiết lập mối quan hệ: Một cuốn sách có thể có nhiều tác giả
    public function tacGias()
    {
        return $this->belongsToMany(TacGia::class, 'tac_gia_sach', 'sach_id', 'tac_gia_id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->danh_gias_avg_so_sao ?? ($this->danhGias()->avg('so_sao') ?: 0);
    }

    public function getReviewCountAttribute()
    {
        return $this->danh_gias_count ?? $this->danhGias()->count();
    }
}
