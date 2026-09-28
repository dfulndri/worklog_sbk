<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $root = $this->node(null, 'Jenis Kegiatan', 'kegiatan', 1);

        $kontruksi = $this->node($root, 'Kontruksi', 'kategori', 1);
        foreach ([
            'Kontruksi Fasilitas PPA (IPAL)',
            'Kontruksi Fasilitas PPU',
            'Rekontruksi IPAL / Equipment Set',
            'Pemeliharaan Unit PPA (IPAL/Sludge/dll)',
            'Pemeliharaan Unit PPU',
        ] as $i => $name) {
            $this->node($kontruksi, $name, 'sub_kategori', $i + 1);
        }

        $nonKontruksi = $this->node($root, 'Non Kontruksi', 'kategori', 2);

        $lingkungan = $this->node($nonKontruksi, 'Dokumen Lingkungan', 'sub_kategori', 1);
        foreach ([
            'Dokumen AMDAL/DELH',
            'Dokumen UKL UPL/DPLH',
            'Dokumen Pertek Pemenuhan Baku Mutu Air Limbah (PBMAL)',
            'Dokumen Pertek Pemenuhan Baku Mutu Emisi (PBMEU)',
            'Surat Laik Operasional (SLO) (Air/Udara)',
            'Surat Keterangan',
        ] as $i => $name) {
            $this->node($lingkungan, $name, 'dokumen', $i + 1, requiresAuthority: true);
        }

        $lahan = $this->node($nonKontruksi, 'Dokumen Lahan dan Bangunan', 'sub_kategori', 2);
        foreach ([
            'Rencana Tapak Rinci (Siteplan)',
            'Persetujuan Bangunan Gedung (PBG)',
            'Sertifikat Laik Fungsi (SLF) Bangunan',
            'PKKPR',
        ] as $i => $name) {
            $this->node($lahan, $name, 'dokumen', $i + 1);
        }
    }

    private function node(?DocumentCategory $parent, string $name, string $level, int $order, bool $requiresAuthority = false): DocumentCategory
    {
        return DocumentCategory::updateOrCreate(
            ['parent_id' => $parent?->id, 'name' => $name],
            ['level' => $level, 'sort_order' => $order, 'requires_authority' => $requiresAuthority],
        );
    }
}
