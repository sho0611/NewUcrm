<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts'; 
    protected $primaryKey = 'post_id'; 

    protected $fillable = ['staff_id', 'item_id', 'description', 'image'];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
