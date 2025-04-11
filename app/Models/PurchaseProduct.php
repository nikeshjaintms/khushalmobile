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

}
