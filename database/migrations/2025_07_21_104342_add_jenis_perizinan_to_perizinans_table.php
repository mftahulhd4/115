<?php

// Bagian ini sudah diperbaiki
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            // Tambahkan kolom foreign key setelah id_santri
            $table->foreignId('id_jenis_perizinan')->nullable()->after('id_santri')->constrained('jenis_perizinans', 'id_jenis_perizinan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['id_jenis_perizinan']);
            // Hapus kolomnya
            $table->dropColumn('id_jenis_perizinan');
        });
    }
};