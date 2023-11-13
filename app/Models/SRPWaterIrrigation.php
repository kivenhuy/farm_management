<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPWaterIrrigation extends Model
{
    use HasFactory;
    protected $table = 'srp_water_irrigation';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srps_id',
        'collection_code',
        'question',
        'answer',
        'score',
    ];
}
