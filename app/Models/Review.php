<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Item;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews'; 
    protected $primaryKey = 'review_id'; 

    protected $fillable = ['item_id', 'customer_name' , 'rating', 'comment'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class,'service_id');
    }

    /**
     * レビューとアイテムを結合して取得する
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getReviewsWithItems()
    {
        return self::query()
            ->join('items', 'reviews.item_id', '=', 'items.item_id')
            ->select(
                'items.item_id AS item_id',
                'items.name AS item_name',
                'items.price AS item_price',
                'reviews.customer_name',
                'reviews.rating',
                'reviews.comment',
                'reviews.created_at'
            )
            ->get();
    }
}
