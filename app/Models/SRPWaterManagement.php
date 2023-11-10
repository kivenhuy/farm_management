<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPWaterManagement extends Model
{
    use HasFactory;
    protected $table = 'srp_water_management';
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
