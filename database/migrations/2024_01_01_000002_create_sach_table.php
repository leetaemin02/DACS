<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sach', function (Blueprint $table) {
            $table->id();
            $table->string('ten_sach');
            $table->string('tac_gia')->nullable();
            $table->text('mo_ta')->nullable();
            $table->decimal('gia', 10, 2);
            $table->integer('so_luong')->default(0);
            $table->string('hinh_anh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sach');
    }
};
