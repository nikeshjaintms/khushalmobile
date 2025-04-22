<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'customer_id',
        'finances_master_id',
        'price',
        'downpayment',
        'processing_fee',
        'mobile_security_charges',
        'emi_charger',
        'finance_amount',
        'month_duration',
        'emi_value',
        'penalty',
        'dedication_date',
        'finance_year',
        'status'
    ];
    public function FinanceMaster()
    {
        return $this->hasMany(financeMaster::class, 'finances_master_id');
    }

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function invoices(){
        return $this->belongsTo(Sale::class, 'invoice_id');
    }




}
