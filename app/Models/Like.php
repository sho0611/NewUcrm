<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Customer;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likes'; 
    protected $primaryKey = 'like_id'; 
    protected $keyType = 'int'; 

    protected $fillable = ['post_id', 'customer_id'];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class); 
    }
}
