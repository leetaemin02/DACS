<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TacGia extends Model
{
    use HasFactory;

    protected $table = 'tac_gia';

    protected $fillable = [
        'ten_tac_gia',
    ];

    public $timestamps = false; // Based on SQL, no timestamps here

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'tac_gia_sach', 'tac_gia_id', 'sach_id');
    }
}
