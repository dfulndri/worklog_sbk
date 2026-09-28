<?php

namespace Database\Seeders;

use App\Models\Authority;
use Illuminate\Database\Seeder;

class AuthoritySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Kabupaten / Kota', 'Provinsi', 'Kementerian'] as $i => $name) {
            Authority::updateOrCreate(['name' => $name], ['sort_order' => $i + 1]);
        }
    }
}
