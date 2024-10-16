<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Review;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','kana','email','tel','postcode','address','birthday','gender','memo'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function appintments()
    {
        return $this->hasMany(Appointment::class);
    }


    public function scopeCustomerItems($query)
    {
        return $query->select(
                'customers.id AS customer_id',
                'customers.name AS customer_name',
                'customers.kana AS customer_kana',
                'customers.tel AS customer_tel',
                'purchases.id AS purchase_id',
                'purchases.customer_id',
                'item_purchase.purchase_id AS item_purchase_id',
                'item_purchase.item_id',
                'item_purchase.quantity',
                'items.id AS item_id',
                'items.name AS item_name',
                'items.price AS item_price',
                'items.memo AS item_memo'
            )
            ->leftJoin('purchases', 'customers.id', '=', 'purchases.customer_id')
            ->leftJoin('item_purchase', 'purchases.id', '=', 'item_purchase.purchase_id')
            ->leftJoin('items', 'item_purchase.item_id', '=', 'items.id');
    }
}


    

