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
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade'); // Foreign key ke tabel santris
            $table->string('kepentingan_izin');
            $table->date('tanggal_izin');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_izin', ['Diajukan', 'Disetujui', 'Ditolak', 'Selesai', 'Terlambat Kembali'])->default('Diajukan');
            // $table->foreignId('user_id_approval')->nullable()->constrained('users')->onDelete('set null'); // Opsional: untuk siapa yang menyetujui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};