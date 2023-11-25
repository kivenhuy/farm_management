<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Staff extends Model
{
    use HasFactory;

    protected $appends = ['name'];
    
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'lat',
        'lng',
        'phone_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farmer_details()
    {
        return $this->hasMany(FarmerDetails::class,'staff_id','id');
    }

    public function srp_schedule()
    {

        return $this->hasManyThrough(
            SRPSchedule::class, 
            SRP::class,
            'staff_id', // Foreign key on the environments table...
            'srp_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id');
    }

    public function getNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function farm_land(): HasManyThrough
    {
        return $this->hasManyThrough(
        FarmLand::class, 
        FarmerDetails::class,
        'staff_id', // Foreign key on the environments table...
        'farmer_id', // Foreign key on the deployments table...
        'id', // Local key on the projects table...
        'id')->join('cultivations','farm_lands.id','=','cultivations.farm_land_id')->select('cultivations.*');
    }

    public function farm_land_count(): HasManyThrough
    {
        return $this->hasManyThrough(
        FarmLand::class, 
        FarmerDetails::class,
        'staff_id', // Foreign key on the environments table...
        'farmer_id', // Foreign key on the deployments table...
        'id', // Local key on the projects table...
        'id');
    }
}
