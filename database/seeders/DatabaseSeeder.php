<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Komentari atau hapus jika tidak ingin membuat user factory

        // Panggil AdminUserSeeder
        $this->call([
            AdminUserSeeder::class,
            // Anda bisa menambahkan seeder lain di sini jika ada
            // AnotherSeeder::class,
        ]);

        // Kode User::factory()->create() yang ada sebelumnya bisa dihapus jika
        // admin utama sudah dibuat melalui AdminUserSeeder.
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
