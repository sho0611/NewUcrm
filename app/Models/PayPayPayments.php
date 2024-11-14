<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPayPayments extends Model
{
    use HasFactory;
    protected $table = 'paypay_payments';   
    protected $primaryKey = 'paypay_payment_id';    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price',
        'is_payment',
        'paypay_merchant_payment_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_payment' => 'boolean',
    ];
}
