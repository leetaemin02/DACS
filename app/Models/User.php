<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'so_dien_thoai',
        'dia_chi',
        'vai_tro',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mat_khau' => 'hashed',
    ];

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'nguoi_dung_id');
    }

    public function gioHangs()
    {
        return $this->hasMany(GioHang::class, 'nguoi_dung_id');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'nguoi_dung_id');
    }
}
