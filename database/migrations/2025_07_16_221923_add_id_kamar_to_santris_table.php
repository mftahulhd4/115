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
            // Menambahkan kolom id_kamar setelah kolom id_status
            $table->unsignedBigInteger('id_kamar')->nullable()->after('id_status');

            // Menetapkan foreign key constraint
            $table->foreign('id_kamar')->references('id_kamar')->on('kamars')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus kolom
            $table->dropForeign(['id_kamar']);
            $table->dropColumn('id_kamar');
        });
    }
};