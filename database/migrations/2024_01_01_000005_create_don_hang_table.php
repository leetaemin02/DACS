<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->onDelete('cascade');
            $table->foreignId('thanh_toan_id')->nullable()->constrained('thanh_toan')->onDelete('set null');
            $table->foreignId('ma_giam_gia_id')->nullable()->constrained('ma_giam_gia')->onDelete('set null');
            $table->decimal('tong_tien', 12, 2);
            $table->string('trang_thai')->default('cho_xu_ly'); // cho_xu_ly, dang_giao, hoan_thanh, da_huy
            $table->text('dia_chi_giao_hang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};
