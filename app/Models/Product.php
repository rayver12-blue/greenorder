<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category', 'description', 'price',
        'day_availability', 'image', 'sold_count', 'stock',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'sold_count' => 'integer',
        'stock'      => 'integer',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
