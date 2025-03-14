<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lucky_wheel extends Model
{
    protected $table = 'lucky_wheel';
    protected $fillable = [
        'id', 
        'reward', 
        'user_id', 
        'spin_date', 
        'created_at', 
        'updated_at',
        'status',
    ];
    public $timestamps = true;
}
