<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\Like;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable;

    const Man = 0;
    const Woman = 1;
    const Other = 2;

    protected $table = 'customers'; 
    protected $primaryKey = 'customer_id'; 

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
        return $this->c(Appointment::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)
        ->withPivot('use_at');
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}


    

