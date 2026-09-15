<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'user_id',
        'job_task_id',
        'report_date',
        'progress',
        'description',
        'obstacle',
        'next_plan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jobTask()
    {
        return $this->belongsTo(JobTask::class);
    }
}
