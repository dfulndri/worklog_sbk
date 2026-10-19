<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStage extends Model
{
    public const PHASES = [
        1 => 'Progress 1',
        2 => 'Progress 2',
        3 => 'Progress 3',
        4 => 'Progress 4',
    ];

    public const PHASE_BADGES = [
        1 => 'text-bg-secondary',
        2 => 'text-bg-info',
        3 => 'text-bg-warning',
        4 => 'text-bg-success',
    ];

    /** Bobot tiap Progress (kumulatif): Progress 1 = 25%, ... Progress 4 = 100%. */
    public const PHASE_WEIGHT = 25;

    protected $fillable = ['category_id', 'name', 'phase', 'weight', 'order_no'];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'phase' => 'integer',
            'weight' => 'integer',
            'order_no' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Bobot selalu diturunkan dari Progress, tidak diisi manual.
        static::saving(function (self $stage) {
            $stage->weight = (int) $stage->phase * self::PHASE_WEIGHT;
        });
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function phaseLabel(): string
    {
        return self::PHASES[$this->phase] ?? 'Progress ' . $this->phase;
    }

    public function phaseBadge(): string
    {
        return self::PHASE_BADGES[$this->phase] ?? 'text-bg-secondary';
    }
}
