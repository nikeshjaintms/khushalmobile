<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use softDeletes;

    protected $fillable = [
        'name',
        'phone',
        'alternate_phone',
        'city',
    ];
}
