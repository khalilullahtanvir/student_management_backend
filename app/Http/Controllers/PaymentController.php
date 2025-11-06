<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // ইউজারের পেমেন্টের ইতিহাস দেখানোর জন্য
    public function history()
    {
        $payments = Auth::user()
            ->with('enrollment.course') // এনরোলমেন্ট এবং কোর্সের তথ্য লোড করছি
            ->latest()
            ->get();

        return response()->json($payments);
    }

    // একটি এনরোলমেন্টের জন্য পেমেন্ট প্রসেস করার জন্য
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        // চেক করুন এই এনরোলমেন্টটি বর্তমান্ত ইউজারেরই
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // পেমেন্ট রেকর্ড তৈরি করুন
        $payment = Payment::create([
            'enrollment_id' => $enrollment->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'completed', // পেমেন্ট সফল হয়েছে ধরে ধরুন
        ]);

        // এনরোলমেন্টের স্ট্টাস 'paid' করে দিন
        $enrollment->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Payment successful!',
            'payment' => $payment
        ]);
    }
}