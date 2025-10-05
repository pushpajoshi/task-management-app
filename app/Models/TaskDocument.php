<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    use HasFactory;
    protected $guard=[];
    protected $fillable=['filename','filepath','uploaded_by','task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
