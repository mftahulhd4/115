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
            $table->unsignedBigInteger('santri_id');
            $table->foreign('santri_id')->references('id')->on('santris')->onDelete('cascade');

            $table->string('jenis_tagihan');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_pelunasan')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas', 'Jatuh Tempo'])->default('Belum Lunas');
            
            // TAMBAHKAN KOLOM INI
            $table->text('keterangan_tambahan')->nullable();

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