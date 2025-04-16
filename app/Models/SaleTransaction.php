<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    use HasFactory;
    protected $table = 'sales_transactions';
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_mode',
        'reference_no',

    ];
}
