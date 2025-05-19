<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'invoice_date',
        'sub_total',
        'tax_type',
        'tax_amount',
        'total_tax_amount',
        'total_amount',
        'total_amount_rounded',
        'payment_method',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sales_id');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }

}
