<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Support\Facades\Hash; // Import Hash facade

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Miftah', // Ganti dengan nama yang Anda inginkan
            'email' => 'admin@gmail.com', // Ganti dengan email admin
            'password' => Hash::make('password123'), // Ganti 'password' dengan password yang kuat
            'role' => 'admin',
            'email_verified_at' => now(), // Langsung verifikasi email
        ]);

        // Anda bisa menambahkan user lain di sini jika perlu
        // User::create([
        //     'name' => 'Pengurus Satu',
        //     'email' => 'pengurus@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'pengurus',
        //     'email_verified_at' => now(),
        // ]);
    }
}