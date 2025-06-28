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
        Schema::create('perizinans', function (Blueprint $table) {
            $table->string('id_izin')->primary();
            $table->string('id_santri');
            $table->string('kepentingan_izin');
            $table->date('tanggal_izin');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('perizinans');
    }
};