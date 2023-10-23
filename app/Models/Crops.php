<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crops extends Model
{
    use HasFactory;
    protected $fillable = [
        'farm_land_id',
        'season_id',
        'crop_id',
        'crop_variety',
        'sowing_date',
        'expect_date',
        'est_yield',
        'photo'
    ];
    

    public function farm_land()
    {
        return $this->hasOne(FarmLand::class,'id','farm_land_id');
    }

    public function season()
    {
        return $this->hasOne(SeasonMaster::class,'id','season_id');
    }

    public function crops_master()
    {
        return $this->hasOne(CropInformation::class,'id','crop_id');
    }
}
