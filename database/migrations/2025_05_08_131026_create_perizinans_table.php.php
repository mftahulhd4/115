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
            $table->string('id_izin')->unique(); // ID unik untuk perizinan
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('kepentingan_izin');
            $table->date('tanggal_izin');
            $table->date('tanggal_kembali')->nullable(); 
            $table->enum('status', ['Izin', 'Kembali', 'Terlambat'])->default('Izin');
            $table->text('keterangan_tambahan')->nullable();
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