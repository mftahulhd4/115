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
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->foreign('santri_id')->references('id')->on('santris')->onDelete('cascade');
            
            // Kolom-kolom yang kita gunakan di form
            $table->string('kepentingan_izin');
            $table->date('tanggal_izin');
            $table->date('tanggal_kembali_rencana');
            $table->text('keterangan_tambahan')->nullable();
            
            // Kolom status yang di-set otomatis
            $table->string('status')->default('diproses');
            
            $table->timestamps();
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