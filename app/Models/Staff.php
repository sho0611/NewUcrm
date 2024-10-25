<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;



class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'memo', 'password'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}

