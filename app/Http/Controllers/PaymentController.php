<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // ইউজারের পেমেন্টের ইতিহাস দেখানোর জন্য
    public function index(Request $request)
    {
        $payments = Payment::where('enrollment_id', $request->enrollment_id)
            ->with('enrollment.course', 'paymentInstallments')
            ->latest()
            ->first();

        return response()->json($payments);
    }

    // একটি এনরোলমেন্টের জন্য পেমেন্ট প্রসেস করার জন্য
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string',
        ]);

        // check if transction id is already used
        $checkTransaction = PaymentInstallment::where('transaction_id', $request->transaction_id)->first();
        if($checkTransaction){
            return response()->json([
                'message' => 'Transaction ID already used!',
                'transaction' => $checkTransaction
            ], 201);
        }

        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $coursePrice = $enrollment->course->price;
        
        $checkPayment = Payment::where('enrollment_id', $enrollment->id)->first();

        if($checkPayment){
            if($checkPayment->amount >= $coursePrice){
                return response()->json([
                    'message' => 'Payment already completed!',
                    'payment' => $checkPayment
                ], 201);
            }else{
                if($checkPayment->amount + $request->amount >= $coursePrice){
                    $status = 'completed';
                }else{
                    $status = 'Partial Payment';
                }
                $checkPayment->update([
                    'amount' => $checkPayment->amount + $request->amount,
                    'status' => $status,
                ]);
                $payment=$checkPayment;
            }
        }else{
            if($request->amount >= $coursePrice){
                $status = 'completed';
            }else{
                $status = 'Partial Payment';
            }
            $payment=Payment::create([
                'enrollment_id' => $enrollment->id,
                'amount' => $request->amount,
                'status' => $status,
                'payment_date' => now(),
            ]);
        }
      
        $installmentNumber = PaymentInstallment::where('payment_id', $payment->id)->count() + 1;

        $paymentInstallment = PaymentInstallment::firstOrCreate([
            'payment_id' => $payment->id,
            'amount' => $request->amount,
            'installment_no' => $installmentNumber,
            'transaction_id' => $request->transaction_id,
            'status' => 'Approved',
            'paid_date' => now(),
        ]);

        // এনরোলমেন্টের স্ট্টাস 'paid' করে দিন
       // $enrollment->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Payment successful!',
            'payment' => $paymentInstallment
        ], 200);
    }
}