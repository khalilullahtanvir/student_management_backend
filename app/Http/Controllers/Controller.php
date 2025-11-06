<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // রিকোয়েস্ট ডেটা লগ করুন
        Log::info('Registration request:', $request->all());
        
        // ইনপুট ডেটা নেওয়া
        $input = $request->only(['name', 'email', 'password', 'password_confirmation']);
        
        // ডেটা নরমালাইজেশন - অ্যারে হলে প্রথম এলিমেন্ট নেওয়া
        $normalized = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                // অ্যারে হলে প্রথম মান নেওয়া
                $firstValue = reset($value);
                $normalized[$key] = is_string($firstValue) ? trim($firstValue) : '';
                Log::warning("Field {$key} was array, normalized to: " . $normalized[$key]);
            } else {
                // স্ট্রিং হলে ট্রিম করা
                $normalized[$key] = is_string($value) ? trim($value) : (string) $value;
            }
        }

        // ভ্যালিডেশন
        $validator = Validator::make($normalized, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        Log::info('Validation passed:', $validated);

        // ইউজার তৈরি
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User created successfully:', ['user_id' => $user->id]);

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}