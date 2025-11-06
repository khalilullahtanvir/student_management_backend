<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class StudentController extends Controller
{
    public function register(Request $request)
    {
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $request->photo,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'Registration successful',
            'student' => $student,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
        if (!Auth::guard('student')->attempt($validated)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $student = Auth::guard('student')->user();
        $token = $student->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'student' => $student,
            'token' => $token,
        ]);
    }


    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}

