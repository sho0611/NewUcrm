<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPurchase extends Model
{
    use HasFactory;
    protected $table = 'item_purchase';
    protected $primaryKey = 'item_purchase_id';

    protected $fillable = [
        'purchase_id',
        'item_id',
        'quantity'
    ];
}
