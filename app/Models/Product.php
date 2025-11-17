<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'sku_code',
        'product_rate',
    ];

    protected $casts = [
        'product_rate' => 'decimal:2',
    ];
}

