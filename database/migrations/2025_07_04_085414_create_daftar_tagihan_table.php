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
        Schema::create('daftar_tagihan', function (Blueprint $table) {
            $table->string('id_daftar_tagihan')->primary();
            $table->string('id_santri');
            $table->string('id_jenis_tagihan');
            
            // Kolom jumlah_tagihan, tanggal_tagihan, tanggal_jatuh_tempo dihapus dari sini
            
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->foreignId('user_id_pembayaran')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');
            $table->foreign('id_jenis_tagihan')->references('id_jenis_tagihan')->on('jenis_tagihans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_tagihan');
    }
};