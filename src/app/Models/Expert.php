<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    protected $fillable = ['name', 'field'];

    public function jobTasks()
    {
        return $this->hasMany(JobTask::class);
    }
}
