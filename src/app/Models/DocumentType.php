<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['name', 'category'];

    public function jobTasks()
    {
        return $this->hasMany(JobTask::class);
    }
}
