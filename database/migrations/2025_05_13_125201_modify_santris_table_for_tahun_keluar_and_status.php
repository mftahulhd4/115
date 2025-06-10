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
        Schema::table('santris', function (Blueprint $table) {
            // 1. Tambahkan kolom 'tahun_keluar' setelah 'tahun_masuk'
            // Kolom ini bisa nullable karena santri yang masih aktif belum punya tahun keluar
            // Tipe data 'year' mungkin tidak didukung oleh semua DB via Blueprint,
            // jadi kita bisa gunakan smallInteger atau string(4)
            $table->string('tahun_keluar', 4)->nullable()->after('tahun_masuk');

            // 2. Modifikasi kolom 'status_santri'
            // Hati-hati jika sudah ada data, perubahan enum bisa menyebabkan masalah.
            // Pastikan data lama sesuai dengan salah satu nilai enum baru atau backup dulu.
            // Kita set defaultnya ke 'Aktif' untuk data baru.
            $table->enum('status_santri', ['Aktif', 'Alumni', 'Pengurus', 'Baru']) // 'Baru' tetap ada untuk pendaftaran awal
                  ->default('Aktif')
                  ->change(); // 'change()' digunakan untuk memodifikasi kolom yang sudah ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn('tahun_keluar');

            // Kembalikan ke definisi enum sebelumnya jika perlu (sesuaikan dengan definisi lama Anda)
            // Ini contoh jika sebelumnya ada 'Non-Aktif'
            $table->enum('status_santri', ['Aktif', 'Non-Aktif', 'Alumni', 'Baru'])
                  ->default('Baru') // Sesuaikan dengan default lama
                  ->change();
        });
    }
};