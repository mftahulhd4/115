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
            $table->id();
            $table->string('id_santri')->unique()->nullable(); // DIUBAH MENJADI HURUF KECIL
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('pendidikan')->nullable();
            $table->string('kelas')->nullable();
            $table->string('kamar')->nullable();
            $table->string('nama_bapak');
            $table->string('nama_ibu');
            $table->string('nomer_orang_tua');
            $table->enum('status_santri', ['Aktif', 'Baru', 'Pengurus', 'Alumni'])->default('Baru');
            $table->year('tahun_masuk');
            $table->year('tahun_keluar')->nullable();
            $table->string('foto')->nullable();
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