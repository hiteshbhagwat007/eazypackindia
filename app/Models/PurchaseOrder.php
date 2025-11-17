<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'product_name',
        'po_number',
        'sku_number',
        'quantity',
        'product_price',
        'delivery_date',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'product_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected $appends = ['total_amount'];

    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->quantity * (float) $this->product_price);
    }
}