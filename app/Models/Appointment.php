<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [ 'service_id','customer_name', 'appointment_date', 'appointment_time'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'service_id', 'id'); 
    }
}
