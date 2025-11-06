<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return response()->json(Attendance::with(['enrollment', 'classSchedule'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'class_id' => 'required|exists:class_schedules,id',
            'status' => 'required|string',
        ]);

        $attendance = Attendance::create($validated);
        return response()->json(['message' => 'Attendance recorded', 'data' => $attendance]);
    }

    public function show($id)
    {
        return response()->json(Attendance::with(['enrollment', 'classSchedule'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());
        return response()->json(['message' => 'Attendance updated', 'data' => $attendance]);
    }

    public function destroy($id)
    {
        Attendance::destroy($id);
        return response()->json(['message' => 'Attendance deleted']);
    }
}
