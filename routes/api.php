<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * ✅ Register new user
     */
    public function register(Request $request)
    {
        // ১. ইনকামিং ডেটা ভ্যালিডেট করুন
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed', // পাসওয়ার্ড কনফার্মেশন চেক করা হচ্ছে
        ]);

        if ($validator->fails()) {
            // ভ্যালিডেশন ব্যর্থ হলে 422 স্ট্যাটাস কোড সহ এরর মেসেজ পাঠানো হবে
            throw ValidationException::withMessages($validator->errors());
        }

        // ২. নতুন ইউজার তৈরি করুন
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        // ৩. Sanctum টোকেন তৈরি করুন
        $token = $user->createToken('auth_token')->plainTextToken;

        // ৪. সফলভাবে রেজিস্ট্রেশনের রেসপন্স পাঠানো
        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * ✅ Login user
     */
    public function login(Request $request)
    {
        // ১. ইনকামিং ডেটা ভ্যালিডেট করুন
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors());
        }

        // ২. ইউজারের ক্রিডেনশিয়াল চেক করুন
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // ৩. সফলভাবে লগইন করলে ইউজার এবং টোকেন পাঠানো
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * ✅ Get current user info
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * ✅ Logout user (শুধুমাত্র বর্তমান ডিভাইস থেকে লগআউট)
     */
    public function logout(Request $request)
    {
        // যে ডিভাইস থেকে রিকোযেস্ট এসেছে, শুধু সেই ডিভাইসের টোকেন ডিলিট করুন
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}