<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTask extends Model
{
    protected $fillable = [
        'client_id',
        'expert_id',
        'employee_id',
        'document_category_id',
        'authority_id',
        'stage_id',
        'title',
        'progress',
        'deadline',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function documentCategory()
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }

    public function stage()
    {
        return $this->belongsTo(JobStage::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }
}
