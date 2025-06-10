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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade'); // Relasi ke santri
            $table->string('jenis_tagihan'); // Contoh: SPP Januari, Uang Buku, dll.
            $table->decimal('nominal_tagihan', 15, 2); // Nominal dengan 2 angka desimal
            $table->date('tanggal_tagihan'); // Tanggal tagihan ini dibuat/diberlakukan
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_tagihan', ['Belum Lunas', 'Lunas', 'Sebagian Dibayar', 'Dibatalkan'])->default('Belum Lunas');
            $table->date('tanggal_pembayaran')->nullable(); // Tanggal kapan pembayaran (lunas/sebagian) dilakukan
            $table->decimal('jumlah_dibayar', 15, 2)->nullable(); // Jumlah yang sudah dibayar jika statusnya 'Sebagian Dibayar'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
