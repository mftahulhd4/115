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
            $table->string('id_santri'); // PASTIKAN NAMA KOLOM KONSISTEN
            $table->string('Id_tagihan')->unique();
            $table->string('jenis_tagihan');
            $table->decimal('nominal', 10, 2);
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_pelunasan')->nullable();
            $table->string('status')->default('Belum Lunas');
            $table->text('keterangan_tambahan')->nullable();
            $table->timestamps();

            // DIUBAH MENJADI HURUF KECIL
            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');
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