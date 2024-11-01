<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Item;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'purchases'; 
    protected $primaryKey = 'purchase_id';

    protected $fillable = [
        'customer_id',
        'status',
        ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_purchase', 'purchase_id', 'item_id')
            ->withPivot('quantity');
    }
    

    /**
     * 購入情報を取得し、アイテムと顧客情報を結合する
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPurchaseWithDetails()
    {
        return self::query()
            ->leftJoin('item_purchase', 'purchases.purchase_id', '=', 'item_purchase.purchase_id')
            ->leftJoin('items', 'item_purchase.item_id', '=', 'items.item_id')
            ->leftJoin('customers', 'purchases.customer_id', '=', 'customers.customer_id')
            ->groupBy('purchases_id') 
            ->selectRaw('purchases.purchase_id as id, SUM(items.price * item_purchase.quantity) as total, customers.name as customer_name, customers.kana as customer_kana, purchases.status, purchases.created_at')
            ->paginate(50);
    }


}
