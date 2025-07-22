<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        // Perintah untuk membuat tabel baru
        Schema::create('jenis_perizinans', function (Blueprint $table) {
            // Mendefinisikan kolom-kolom tabel
            $table->id('id_jenis_perizinan'); // Membuat Primary Key bernama id_jenis_perizinan
            $table->string('nama');           // Membuat kolom VARCHAR untuk nama jenis perizinan
            $table->timestamps();             // Membuat kolom created_at dan updated_at secara otomatis
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_perizinans');
    }
};