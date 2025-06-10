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
        Schema::table('tagihans', function (Blueprint $table) {
            // 1. Tambahkan kolom 'tanggal_pelunasan' setelah 'jumlah_dibayar' atau posisi lain yang sesuai
            // Kolom ini nullable karena baru diisi saat lunas
            $table->date('tanggal_pelunasan')->nullable()->after('jumlah_dibayar');

            // 2. Modifikasi kolom 'status_tagihan'
            // Hati-hati jika sudah ada data dengan status 'Sebagian Dibayar' atau 'Dibatalkan'.
            // Anda perlu memikirkan bagaimana mengkonversi status tersebut.
            // Untuk contoh ini, kita akan mengubahnya dan set default ke 'Belum Lunas'.
            // Jika ada data lama, mungkin perlu query manual untuk mengubah status 'Sebagian Dibayar' menjadi 'Belum Lunas'.
            $table->enum('status_tagihan', ['Belum Lunas', 'Lunas'])
                  ->default('Belum Lunas')
                  ->change(); // 'change()' untuk memodifikasi kolom yang sudah ada

            // Jika Anda ingin menghapus kolom yang tidak terpakai lagi (misal jumlah_dibayar jika tidak ada lagi status 'Sebagian Dibayar')
            // $table->dropColumn('jumlah_dibayar'); // Hapus jika 'Sebagian Dibayar' dihilangkan dan tidak perlu lagi
            // $table->dropColumn('tanggal_pembayaran'); // Pertimbangkan apakah ini masih relevan atau digantikan 'tanggal_pelunasan'
                                                      // Untuk sekarang, kita biarkan dulu karena 'tanggal_pembayaran' bisa untuk histori jika ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn('tanggal_pelunasan');

            // Kembalikan ke definisi enum sebelumnya jika perlu (sesuaikan dengan definisi lama Anda)
            $table->enum('status_tagihan', ['Belum Lunas', 'Lunas', 'Sebagian Dibayar', 'Dibatalkan'])
                  ->default('Belum Lunas') // Sesuaikan dengan default lama
                  ->change();

            // Jika Anda menghapus kolom di 'up()', tambahkan kembali di 'down()'
            // $table->decimal('jumlah_dibayar', 15, 2)->nullable()->after('tanggal_pembayaran');
            // $table->date('tanggal_pembayaran')->nullable()->after('status_tagihan');
        });
    }
};