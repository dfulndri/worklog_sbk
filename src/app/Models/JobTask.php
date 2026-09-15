<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTask extends Model
{
    protected $fillable = [
        'client_id',
        'expert_id',
        'employee_id',
        'document_type_id',
        'title',
        'stage',
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
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
}
