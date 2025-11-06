<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Student, Course, Payment, Enrollment};

class AdminDashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'students' => Student::count(),
            'courses' => Course::count(),
            'payments' => Payment::count(),
            'enrollments' => Enrollment::count(),
        ]);
    }
}
