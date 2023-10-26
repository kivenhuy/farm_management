<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLandLatLng extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'farm_land_id',
        'order',
        'lat',
        'lng',
    ];


   
}
