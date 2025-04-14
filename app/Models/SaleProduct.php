<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleProduct extends Model
{
    use HasFactory, softDeletes;
    protected $table = "sales_products";

    protected $fillable = [
        'product_id',
        'price',
        'discount',
        'discount_amount',
        'price_subtotal',
        'tax',
        'tax_amount',
        'price_total',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sales_id');
    }

}
