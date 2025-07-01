<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tagihan');
            $table->text('deskripsi')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_tagihans');
    }
};