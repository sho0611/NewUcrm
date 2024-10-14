<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Item;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'customer_id', 'item_id', 'rating', 'comment'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
