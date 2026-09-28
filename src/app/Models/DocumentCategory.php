<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    /** Level berdasarkan kedalaman tree (0 = root). */
    public const LEVELS = ['kegiatan', 'kategori', 'sub_kategori', 'dokumen'];

    public const LEVEL_LABELS = [
        'kegiatan' => 'Jenis Kegiatan',
        'kategori' => 'Kategori',
        'sub_kategori' => 'Sub Kategori',
        'dokumen' => 'Jenis Dokumen',
    ];

    protected $fillable = ['parent_id', 'name', 'level', 'requires_authority', 'sort_order'];

    protected function casts(): array
    {
        return ['requires_authority' => 'boolean'];
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    public function levelLabel(): string
    {
        return self::LEVEL_LABELS[$this->level] ?? $this->level;
    }

    /** Level anak yang valid untuk node ini, null kalau sudah level terdalam. */
    public function childLevel(): ?string
    {
        $index = array_search($this->level, self::LEVELS, true);

        return self::LEVELS[$index + 1] ?? null;
    }

    /** Jalur lengkap tanpa root, mis. "Non Kontruksi › Dokumen Lingkungan › Dokumen UKL UPL/DPLH". */
    public function pathLabel(): string
    {
        $names = [];
        $node = $this;
        while ($node) {
            if ($node->parent_id) {
                array_unshift($names, $node->name);
            }
            $node = $node->parent;
        }

        return implode(' › ', $names ?: [$this->name]);
    }
}
