<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expert;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        Expert::create([
            'name' => 'Ahli Lingkungan A',
            'field' => 'Lingkungan',
        ]);
    }
}
