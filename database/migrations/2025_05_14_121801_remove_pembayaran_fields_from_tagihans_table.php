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
            // Hapus kolom tanggal_pembayaran dan jumlah_dibayar
            // Pastikan Anda sudah yakin tidak membutuhkan data di kolom ini lagi,
            // atau sudah membackupnya.
            if (Schema::hasColumn('tagihans', 'tanggal_pembayaran')) {
                $table->dropColumn('tanggal_pembayaran');
            }
            if (Schema::hasColumn('tagihans', 'jumlah_dibayar')) {
                $table->dropColumn('jumlah_dibayar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            // Tambahkan kembali kolom jika migrasi di-rollback
            // Sesuaikan dengan definisi sebelumnya jika perlu
            $table->date('tanggal_pembayaran')->nullable()->after('status_tagihan');
            $table->decimal('jumlah_dibayar', 15, 2)->nullable()->after('tanggal_pembayaran');
        });
    }
};