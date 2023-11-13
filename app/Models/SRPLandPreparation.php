<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPLandPreparation extends Model
{
    use HasFactory;
    protected $table = 'srp_land_preparation';
    
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
