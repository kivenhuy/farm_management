<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRP extends Model
{
    use HasFactory;
    protected $table = 'srps';
    protected $appends = ['farmer_name', 'cultivation', 'season'];

    protected $fillable = [
        'farmer_id',
        'staff_id',
        'farm_land_id',
        'season_id',
        'cultivation_id',
        'score',
        'sowing_date',
    ];

    public function getFarmerNameAttribute()
    {
        $farmer_data = FarmerDetails::find($this->farmer_id);
        return $data= [
            'full_name'=>$farmer_data->full_name,
            'phone_number'=>$farmer_data->phone_number
        ];
    }

    public function getCultivationAttribute()
    {
        $cultivation_data = Cultivations::find($this->cultivation_id);
        return $data= [
            'crop_varierty'=>$cultivation_data->crop_variety,
            'sowing_date'=>$cultivation_data->sowing_date
        ];
    }

    public function getSeasonAttribute()
    {
        $cultivation_data = SeasonMaster::find($this->season_id)->season_name;
        return $cultivation_data;
    }

    public function srp_schedule()
    {
        return $this->hasMany(SRPSchedule::class,'srp_id','id');
    }
}
