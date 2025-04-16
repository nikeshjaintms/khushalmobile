<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transction extends Model
{
    protected $table = 'transctions';
    protected $primaryKey = 'id';
    protected $fillable = ['amount','type','remark'];
    use HasFactory, SoftDeletes;
}
