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
            $table->id('id_tagihan'); // Mengubah nama primary key
            $table->string('id_santri');
            $table->unsignedBigInteger('id_jenis_tagihan');
            $table->string('status_pembayaran')->default('Belum Lunas');
            $table->date('tanggal_pembayaran')->nullable();
            $table->timestamps();

            // Definisi Foreign Key
            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');
            $table->foreign('id_jenis_tagihan')->references('id_jenis_tagihan')->on('jenis_tagihans')->onDelete('cascade'); // Disesuaikan dengan primary key baru
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