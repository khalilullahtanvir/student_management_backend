<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'class_date',
        'start_time',
        'end_time',
        'topic',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
