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
            $table->string('id_santri')->primary(); // Mengganti nama primary key
            $table->string('nama_santri');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('nomor_hp_wali');
            $table->year('tahun_masuk');
            
            // Foreign Key ke tabel master
            $table->foreignId('id_pendidikan')->constrained('pendidikans', 'id_pendidikan');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas');
            $table->foreignId('id_status')->constrained('statuses', 'id_status');

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