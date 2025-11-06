<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index()
    {
        return response()->json(ClassSchedule::with('course')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'class_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'topic' => 'required',
        ]);

        $schedule = ClassSchedule::create($validated);
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        return response()->json(ClassSchedule::with('course')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->update($request->all());
        return response()->json($schedule);
    }

    public function destroy($id)
    {
        ClassSchedule::destroy($id);
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
