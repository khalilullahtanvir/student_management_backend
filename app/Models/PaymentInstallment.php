<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'installment_no',
        'amount',
        'paid_date',
        'status',
        'transaction_id',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
