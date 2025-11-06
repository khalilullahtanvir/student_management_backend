<?php

namespace App\Http\Controllers;

use App\Models\PaymentInstallment;
use Illuminate\Http\Request;

class PaymentInstallmentController extends Controller
{
    public function index()
    {
        return response()->json(PaymentInstallment::with('payment')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'installment_no' => 'required|integer',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $installment = PaymentInstallment::create($validated);
        return response()->json(['message' => 'Installment created', 'data' => $installment]);
    }

    public function show($id)
    {
        return response()->json(PaymentInstallment::with('payment')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $installment = PaymentInstallment::findOrFail($id);
        $installment->update($request->all());
        return response()->json(['message' => 'Installment updated', 'data' => $installment]);
    }

    public function destroy($id)
    {
        PaymentInstallment::destroy($id);
        return response()->json(['message' => 'Installment deleted']);
    }
}
