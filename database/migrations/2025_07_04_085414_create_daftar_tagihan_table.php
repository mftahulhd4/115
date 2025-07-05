<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daftar_tagihan', function (Blueprint $table) {
            $table->string('id_daftar_tagihan')->primary();

            // Foreign Keys yang konsisten
            $table->string('id_santri');
            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');

            $table->string('id_jenis_tagihan');
            $table->foreign('id_jenis_tagihan')->references('id_jenis_tagihan')->on('jenis_tagihans')->onDelete('cascade');
            
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas', 'Cicil'])->default('Belum Lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_tagihan');
    }
};