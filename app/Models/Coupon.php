<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount_value', 'expiration_date','status'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class)
        ->withPivot('use_at');
    }

}
