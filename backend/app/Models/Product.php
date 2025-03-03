<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'original_price',
        'image',
        'status',
        'quantity',
        'detail'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
