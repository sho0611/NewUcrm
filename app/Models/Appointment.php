<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Staff;
use App\Models\Customer;



class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'staff_id',
        'appointment_date',
        'appointment_time'
    ];
    

    public function item()
    {
        return $this->belongsTo(Item::class, 'service_id', 'id'); 
    }

    public function staff()
    {
        return $this->belongsTo(staff::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
