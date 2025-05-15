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
        'ref_mobile_no',
        'ref_name',
        'ref_city',
        'file_no',
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

    public function finance()
    {
        return $this->belongsTo(Finance::class, 'finance_id');
    }
    public function invoices(){
        return $this->belongsTo(Sale::class, 'invoice_id');
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class, 'finance_id');
    }
}
