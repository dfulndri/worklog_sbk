<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'address', 'contact', 'status'];

    public function jobTasks()
    {
        return $this->hasMany(JobTask::class);
    }
}
