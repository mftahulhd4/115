<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // <-- TAMBAHKAN
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nama_orang_tua');
            $table->string('pendidikan');
            $table->string('nomer_orang_tua');
            $table->year('tahun_masuk');
            $table->year('tahun_keluar')->nullable();
            $table->enum('status_santri', ['Aktif', 'Baru', 'Pengurus', 'Alumni'])->default('Baru');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};