<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_tagihans', function (Blueprint $table) {
            $table->string('id_jenis_tagihan')->primary();
            $table->string('nama_jenis_tagihan');
            $table->text('deskripsi')->nullable();
            
            // PENAMBAHAN KOLOM BULAN DAN TAHUN
            $table->string('bulan')->nullable();
            $table->year('tahun')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_tagihans');
    }
};