<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanh_toan';

    protected $fillable = [
        'phuong_thuc',
        'trang_thai',
    ];

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'thanh_toan_id');
    }
}
