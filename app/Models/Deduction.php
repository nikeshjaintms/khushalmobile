<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'deductions';
    protected $parimaryKey = 'id';

    protected $timestamp = true;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'customer_id',
        'finance_id',
        'emi_value',
        'emi_value_paid',
        'emi_date',
        'penalty',
        'remaining',
        'total',
        'payment_mode',
        'refernce_no',
        'status',
    ];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
