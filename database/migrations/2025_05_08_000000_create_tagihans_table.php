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
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('Id_tagihan')->unique();
            $table->string('jenis_tagihan');
            $table->decimal('nominal', 10, 2);
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_pelunasan')->nullable();
            $table->string('status')->default('Belum Lunas'); // Contoh: 'Lunas', 'Belum Lunas', 'Jatuh Tempo'
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