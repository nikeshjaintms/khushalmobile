<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProduct extends Model
{
    protected $table = "purchase_product";
    protected $primaryKey = "id";
    protected $fillable = ['purchase_id','product_id','invoice_id','color','imei','price','discount','discount_amount','price_subtotal','tax','tax_amount','product_total','status'];
    use HasFactory, SoftDeletes;

    public function saleProduct()
    {
        return $this->belongsTo(SaleProduct::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

      public function sale()
    {
        return $this->belongsTo(Sale::class, 'invoice_id');
    }



}
