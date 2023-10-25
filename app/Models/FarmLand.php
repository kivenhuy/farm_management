<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLand extends Model
{
    use HasFactory;

    protected $table = 'farm_lands';

    protected $fillable = [
            'farmer_id',
            'farm_name',
            'total_land_holding',
            'lat',
            'lng',
            'farm_land_ploting',
            'actual_area',
            'farm_photo',
            'land_ownership',
            'srp_score',
            'carbon_index',
            'approach_road',
            'land_topology',
            'land_gradient',
            'land_document',
    ];


    public function farm_land_lat_lng()
    {
        return $this->hasMany(FarmLand::class);
    }
    
}
