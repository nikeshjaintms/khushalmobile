<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = ['brand_id','product_name','mrp','price','serial'];
    use HasFactory, SoftDeletes;

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function purchaseProducts(){
        return $this->hasMany(PurchaseProduct::class);
    }

    public function purchase(){
        return $this->hasMany(Purchase::class);
    }

}
