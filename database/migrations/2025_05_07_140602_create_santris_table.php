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
            // Kolom Primary Key
            $table->string('id_santri')->primary();

            // Kolom Data Diri Santri
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');

            // Kolom Informasi Akademik
            $table->string('pendidikan');
            $table->string('kelas');
            $table->string('kamar');
            $table->string('tahun_masuk', 4);

            // Kolom Data Orang Tua
            $table->string('nama_bapak');
            $table->string('nama_ibu');
            $table->string('nomer_orang_tua');

            // Kolom Status dan Foto
            // PERUBAHAN: Menyesuaikan nilai enum sesuai permintaan
            $table->enum('status_santri', ['Santri Baru', 'Santri Aktif', 'Pengurus', 'Alumni'])->default('Santri Baru');
            $table->string('foto')->nullable();

            // Timestamps
            $table->timestamps();
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