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
        Schema::table('santris', function (Blueprint $table) {
            // Tambahkan kolom 'foto' setelah kolom 'status_santri' (atau sesuaikan posisinya)
            // Kolom ini akan menyimpan nama file foto, bisa nullable
            $table->string('foto')->nullable()->after('status_santri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};