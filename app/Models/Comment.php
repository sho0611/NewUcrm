<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Customer;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'post_id', 'customer_id'];

    protected $table = 'comments'; 
    protected $primaryKey = 'comment_id'; 
    protected $keyType = 'int'; 

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    //親コメント
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    //子コメント
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
