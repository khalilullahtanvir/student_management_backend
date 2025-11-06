<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // ইউজার নিজের সব এনরোলমেন্ট দেখানোর জন্য
    public function myEnrollments()
    {
        $enrollments = Auth::user()
            ->with('course') // কোর্সের তথ্য সহিতে লোড করছি
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

        $user = Auth::user();

        // চেক করুন ইউজার আগে থেকেই এই কোর্সে এনরোল করেছে কিনা
        $existingEnrollment = Enrollment::where('user_id', $user->id)
                                        ->where('course_id', $request->course_id)
                                        ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'You are already enrolled in this course.',
                'enrollment' => $existingEnrollment
            ], 409); // 409 Conflict স্ট্যাটাস
        }

        // নতুন এনরোলমেন্ট তৈরি করুন
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'status' => 'pending', // প্রথমে স্ট্টাস 'pending'
        ]);

        return response()->json([
            'message' => 'Enrollment successful! Please proceed to payment.',
            'enrollment' => $enrollment
        ], 201);
    }
}