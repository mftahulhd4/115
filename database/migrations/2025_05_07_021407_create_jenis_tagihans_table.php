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
        Schema::create('jenis_tagihans', function (Blueprint $table) {
            $table->string('id_jenis_tagihan')->primary();
            $table->string('nama_jenis_tagihan');
            $table->text('deskripsi')->nullable();
            
            // --- KOLOM BARU YANG DIPINDAHKAN ---
            $table->decimal('jumlah_tagihan', 10, 2)->default(0);
            $table->date('tanggal_tagihan')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            // --- AKHIR KOLOM BARU ---

            $table->tinyInteger('bulan')->nullable();
            $table->year('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_tagihans');
    }
};