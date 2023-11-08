<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRP extends Model
{
    use HasFactory;
    protected $table = 'srps';

    protected $fillable = [
        'farmer_id',
        'staff_id',
        'farm_land_id',
        'season_id',
        'culitavtion_id',
        'score',
        'sowing_date',
    ];
}
