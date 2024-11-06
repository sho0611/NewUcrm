<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;
    
    protected $table = 'login_histories';  
    protected $primaryKey = 'login_histories_id';   

    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'account_id',
        'login_time',
        'logout_time',
    ];
    
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}
