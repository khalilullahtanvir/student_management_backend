<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'class_id',
        'status',
        'remark',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'class_id');
    }
}
