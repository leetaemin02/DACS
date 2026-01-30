<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gia';

    protected $fillable = [
        'ma_code',
        'so_tien_giam',
        'phan_tram_giam',
        'so_luong',
        'ngay_het_han',
    ];

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_giam_gia_id');
    }
}
