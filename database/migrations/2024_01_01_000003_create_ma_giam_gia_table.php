<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ma_giam_gia', function (Blueprint $table) {
            $table->id();
            $table->string('ma_code')->unique();
            $table->decimal('so_tien_giam', 10, 2)->nullable();
            $table->integer('phan_tram_giam')->nullable();
            $table->integer('so_luong')->default(0);
            $table->date('ngay_het_han')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ma_giam_gia');
    }
};
