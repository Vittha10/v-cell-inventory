<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    public function product()
{
    // Ini menghubungkan tabel stock_opnames dengan tabel products
    return $this->belongsTo(Product::class, 'product_id', 'id');
}
}
