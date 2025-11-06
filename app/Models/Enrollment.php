<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
    ];

    // একজন এনরোলমেন্ট একজন ইউজারের সাথে থাকতে পারে
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // একটি এনরোলমেন্ট একটি কোর্সের সাথে থাকতে পারে
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // একটি এনরোলমেন্টের জন্য একাধিক পেমেন্ট হতে পারে
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}