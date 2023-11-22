<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivations extends Model
{
    use HasFactory;
    protected $table = 'cultivations';
    
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
        return $this->belongsTo(FarmLand::class,'farm_land_id','id');
    }

    public function season()
    {
        return $this->belongsTo(SeasonMaster::class,'season_id','id');
    }

    public function crops_master()
    {
        return $this->belongsTo(CropInformation::class,'crop_id','id');
    }

    public function carbon_emission()
    {
        return $this->hasOne(CarbonEmission::class,'cultivation_id','id')->where('season_id',$this->season_id);
    }

    public function srp()
    {
        return $this->hasOne(SRP::class,'cultivation_id','id')->where('season_id',$this->season_id);
    }

    public function getCropPhotoUrlAttribute()
    {
        $photoIds = explode(',', $this->photo);
        $url = [];
        foreach ($photoIds as $photoId) {
            $upload = Uploads::find($photoId);
            if ($upload) {
                $url[] = asset($upload->file_name);
            }
        }

        return $url;
    }
}
