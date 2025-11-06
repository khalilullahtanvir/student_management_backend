<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'amount',
        'payment_method',
        'transaction_id',
        'payment_date',
        'status',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function paymentInstallments()
    {
        return $this->hasMany(PaymentInstallment::class);
    }
}