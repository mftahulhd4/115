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
        Schema::create('santris', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key, auto-increment)
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pendidikan_terakhir')->nullable(); // Bisa jadi ada yg belum punya
            $table->string('kamar')->nullable(); // Mungkin diisi setelah diterima
            $table->year('tahun_masuk');
            $table->string('nama_orang_tua');
            $table->string('nomor_telepon_orang_tua')->nullable(); // Tambahan yang mungkin berguna
            $table->enum('status_santri', ['Aktif', 'Non-Aktif', 'Alumni', 'Baru'])->default('Baru');
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};