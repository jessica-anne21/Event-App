<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini ada
use Illuminate\Support\Facades\Hash; // Pastikan ini ada

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah akun admin sudah ada untuk menghindari duplikasi
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@example.com', // Ganti dengan email admin yang Anda inginkan
                'password' => Hash::make('adminpassword'), // Ganti dengan password kuat Anda
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(), // Opsional: anggap email sudah terverifikasi
            ]);

            $this->command->info('Akun admin berhasil dibuat!');
        } else {
            $this->command->info('Akun admin sudah ada.');
        }
    }
}