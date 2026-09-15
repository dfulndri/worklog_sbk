<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypes = [
            'Pertek Air',
            'Pertek Udara',
            'UKL-UPL',
            'AMDAL/ANDAL',
            'Rintek Limbah B3',
            'Andalalin',
            'PBG/SLF',
        ];

        foreach ($documentTypes as $name) {
            DocumentType::create([
                'name' => $name,
            ]);
        }
    }
}
