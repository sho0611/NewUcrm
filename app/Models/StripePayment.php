<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StripePayment extends Model
{
    use HasFactory;
    protected $table = 'stripe_payments';    
    protected $primaryKey = 'stripe_payment_id';    


    protected $fillable = [
        'appointment_id',
        'charge_id',
        'amount',
        'customer_id'   
    ];
}
