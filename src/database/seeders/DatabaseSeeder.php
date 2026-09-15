<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::factory()->create([
            'name' => 'Admin Melis',
            'email' => 'admin@sbk.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // User Karyawan
        $anisa = User::factory()->create([
            'name' => 'Anisa',
            'email' => 'anisa@sbk.test',
            'password' => bcrypt('password'),
            'role' => 'karyawan',
        ]);

        // Data Employee Anisa
        Employee::create([
            'user_id' => $anisa->id,
            'position' => 'Staff Lingkungan',
        ]);

        // Seeder data master
        $this->call([
            ClientSeeder::class,
            ExpertSeeder::class,
            DocumentTypeSeeder::class,
        ]);
    }
}
