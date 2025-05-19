<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $fillable = ['dealer_id','po_no','po_date','sub_total','tax_type','total_tax_amount','total' ,'total_rounded'];
    use HasFactory, SoftDeletes;

    public function dealer(){
        return $this->belongsTo(Dealer::class,'dealer_id');
    }
}
