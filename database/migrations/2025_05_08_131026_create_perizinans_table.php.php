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
            // MENGUBAH PRIMARY KEY DARI 'id' MENJADI 'id_izin'
            $table->string('id_izin')->primary(); 
            
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('kepentingan_izin');
            $table->text('keterangan_izin')->nullable();
            $table->string('penjemput');
            $table->string('nomer_penjemput');
            $table->date('tanggal_izin');
            $table->time('jam_izin');
            $table->date('tanggal_kembali')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->string('status');
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