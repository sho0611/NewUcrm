<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Review;

class Item extends Model
{
    use HasFactory;
    const sale = 1;
    const not_sale = 2;
    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = ['name', 'memo', 'price', 'is_selling', 'duration'];

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'item_purchase', 'item_id', 'purchase_id')
            ->withPivot('quantity');
    }



    public function appointments()
    {
        return $this->hasMany(Appointment::class); 
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
