<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    use HasFactory;

    protected $table = 'stock_history';

    protected $fillable = [
        'product_id',
        'change_type',
        'quantity',
    ];

    /**
     * Get the product associated with the stock history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
