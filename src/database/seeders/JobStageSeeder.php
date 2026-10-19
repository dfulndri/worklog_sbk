<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use App\Models\JobStage;
use Illuminate\Database\Seeder;

class JobStageSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            'Kontruksi' => [
                1 => ['Survey awal lokasi kegiatan', 'Barang masuk'],
                2 => ['Mulai pekerjaan (kontruksi)', 'Selesai pekerjaan (kontruksi)'],
                3 => ['Uji coba / commissioning', 'Pengujian laboratorium (3x)'],
                4 => ['Penyelesaian pekerjaan (BAST)', 'Pelatihan operator'],
            ],
            'Non Kontruksi' => [
                1 => [
                    'Pencarian data awal',
                    'Penyusunan draft dokumen',
                    'Pembahasan dokumen (internal & eksternal)',
                    'Submit dokumen ke pihak ketiga (pemerintah/kawasan)',
                ],
                2 => ['Rapat pembahasan (sidang)', 'Verifikasi lapangan'],
                3 => ['Revisi dokumen', 'Draft final (QC)'],
                4 => ['SK terbit', 'Cetak dokumen', 'Selesai'],
            ],
        ];

        foreach ($tracks as $kategoriName => $phases) {
            $kategori = DocumentCategory::where('level', 'kategori')->where('name', $kategoriName)->firstOrFail();

            $order = 1;
            foreach ($phases as $phase => $names) {
                foreach ($names as $name) {
                    JobStage::updateOrCreate(
                        ['category_id' => $kategori->id, 'name' => $name],
                        ['phase' => $phase, 'order_no' => $order++],
                    );
                }
            }
        }
    }
}
