<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('jenis_tagihan_id')->constrained('jenis_tagihans')->onDelete('cascade');
            $table->string('id_santri');
            $table->foreign('id_santri')->references('id_santri')->on('santris')->onDelete('cascade');
            
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->dateTime('tanggal_pembayaran')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};