<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStage extends Model
{
    public const GROUPS = [
        'draft' => 'Draft',
        'revisi' => 'Revisi',
        'sidang' => 'Sidang',
        'final' => 'Final',
    ];

    public const GROUP_BADGES = [
        'draft' => 'text-bg-secondary',
        'revisi' => 'text-bg-warning',
        'sidang' => 'text-bg-info',
        'final' => 'text-bg-success',
    ];

    protected $fillable = ['name', 'weight', 'group', 'order_no'];

    public function groupLabel(): string
    {
        return self::GROUPS[$this->group] ?? ucfirst($this->group);
    }

    public function groupBadge(): string
    {
        return self::GROUP_BADGES[$this->group] ?? 'text-bg-secondary';
    }
}
