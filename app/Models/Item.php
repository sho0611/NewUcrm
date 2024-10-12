<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'memo', 'price', 'is_selling'];

    public function purchases()
    { 
        return $this->belongsToMany(Purchase::class)
        ->withPivot('quantity');
    }

    public function scopeItemCustomers($query)
    {
        return $query->select(
            'items.id AS item_id', 
            'items.name AS item_name', 
            'items.price', 
            'item_purchase.id AS pivot_id', 
            'item_purchase.item_id', 
            'item_purchase.quantity', 
            'purchases.id AS purchase_id', 
            'purchases.customer_id', 
            'customers.id AS customer_id', 
            'customers.name AS customer_name', 
            'customers.kana AS customer_kana',
            'customers.tel AS customer_tel'
        )
        ->leftJoin('item_purchase', 'items.id', '=', 'item_purchase.item_id')
        ->leftJoin('purchases', 'item_purchase.purchase_id', '=', 'purchases.id')  
        ->leftJoin('customers', 'purchases.customer_id', '=', 'customers.id');
    }
    
}
