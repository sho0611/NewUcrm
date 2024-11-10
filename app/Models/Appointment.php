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
    protected $table = 'appointments'; 
    protected $primaryKey = 'appointment_id';


    protected $fillable = [
        'item_id' ,
        'customer_id',
        'staff_id',
        'appointment_date',
        'appointment_time', 
        'payment_method' 
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id'); 
    }

    public function staff()
    {
        return $this->belongsTo(staff::class, 'staff_id', 'staff_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
