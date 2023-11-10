<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPLabourRight extends Model
{
    use HasFactory;
    protected $table = 'srp_labour_right';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srps_id',
        'question',
        'answer',
        'score',
    ];
}
