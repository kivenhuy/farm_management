<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPHealthAndSafety extends Model
{
    use HasFactory;
    protected $table = 'srp_health_and_safety';
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
