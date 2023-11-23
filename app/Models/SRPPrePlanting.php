<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPPrePlanting extends Model
{
    use HasFactory;
    protected $table = 'srp_pre_plainings';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'question',
        'answer',
        'score',
        'created_at',
    ];
}
