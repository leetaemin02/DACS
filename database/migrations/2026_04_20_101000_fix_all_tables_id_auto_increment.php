<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'nguoi_dung',
            'sach',
            'ma_giam_gia',
            'thanh_toan',
            'don_hang',
            'chi_tiet_don_hang',
            'gio_hang',
            'danh_gia',
            'tac_gia'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->bigIncrements('id')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Changing back from auto-increment is complex and usually not needed for a fix migration.
    }
};
