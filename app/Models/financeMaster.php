<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class financeMaster extends Model
{
    use HasFactory;
    use SoftDeletes;
protected $table = 'finances_masters';
    protected $fillable = [
        'name',
    ];

    public function finance()
    {
        return $this->belongsTo(Finance::class, 'finances_master_id');
    }
}
