<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perizinans', function (Blueprint $table) {
            $table->string('id_izin')->primary();
            
            $table->string('id_santri');
            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');

            $table->string('keperluan');
            $table->dateTime('waktu_pergi');
            $table->dateTime('estimasi_kembali');
            $table->dateTime('waktu_kembali_aktual')->nullable();
            
            // PERUBAHAN: Menambahkan 'Ditolak'
            $table->enum('status', ['Pengajuan', 'Diizinkan', 'Ditolak', 'Kembali', 'Terlambat'])->default('Pengajuan');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};