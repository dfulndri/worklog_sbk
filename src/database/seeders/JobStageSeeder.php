<?php

namespace Database\Seeders;

use App\Models\JobStage;
use Illuminate\Database\Seeder;

class JobStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['Susun draft', 10, 'draft'],
            ['Fiksasi dokumen (internal)', 25, 'draft'],
            ['Submit dokumen', 35, 'draft'],
            ['Pemeriksaan', 45, 'revisi'],
            ['Rapat pembahasan (sidang)', 60, 'sidang'],
            ['Verifikasi lapangan', 70, 'sidang'],
            ['Revisi tim konsultan', 80, 'revisi'],
            ['Draft final', 90, 'final'],
            ['Selesai', 100, 'final'],
        ];

        foreach ($stages as $i => [$name, $weight, $group]) {
            JobStage::updateOrCreate(
                ['name' => $name],
                ['weight' => $weight, 'group' => $group, 'order_no' => $i + 1],
            );
        }
    }
}
