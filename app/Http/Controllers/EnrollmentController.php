<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // ইউজার নিজের সব এনরোলমেন্ট দেখানোর জন্য
    public function index(Request $request)
    {
        $enrollments = Enrollment::where('student_id', $request->student_id)
            ->with('course', 'payment') // কোর্সের তথ্য সহিতে লোড করছি
            ->latest()
            ->get();
            
        return response()->json($enrollments);
    }

    // একটি কোর্সে এনরোলমেন্ট করার জন্য
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        
        // চেক করুন ইউজার আগে থেকেই এই কোর্সে এনরোল করেছে কিনা
        $existingEnrollment = Enrollment::where('student_id', $request->student_id)
                                        ->where('course_id', $request->course_id)
                                        ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'You are already enrolled in this course.',
                'enrollment' => $existingEnrollment
            ], 201); // 409 Conflict স্ট্যাটাস
        }

        // নতুন এনরোলমেন্ট তৈরি করুন
        $enrollment = Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'status' => 'Approved', 
            'enroll_date' => now(),
        ]);

        return response()->json([
            'message' => 'Enrollment successful! Please proceed to payment. To pay got to enrollment page',
            'enrollment' => $enrollment
        ], 201);
    }
}