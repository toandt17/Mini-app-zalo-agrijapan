<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'status',
        'detail'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
