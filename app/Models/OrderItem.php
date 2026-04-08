<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // Supaya bisa simpan data lewat OrderItem::create([])
    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    /**
     * Relasi balik ke Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke Product (Ini yang bikin gambar & nama produk muncul)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}