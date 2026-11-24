<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya akun admin & staf untuk autentikasi awal sistem, tanpa data dummy
        User::updateOrCreate(
            ['email' => 'admin@theeasthotel.test'],
            [
                'name' => 'Admin The East',
                'password' => 'password123',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staf@theeasthotel.test'],
            [
                'name' => 'Staf Resepsionis',
                'password' => 'password123',
                'role' => 'staff',
                'is_active' => true,
            ]
        );
    }
}
