<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleIntention extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'farm_land_id',
        'crop_id',
        'season_id',
        'variety',
        'sowing_date',
        'photo',
        'quantity',
        'min_price',
        'max_price',
        'date_for_harvest',
        'aviable_date',
        'grade',
        'age_of_crop',
        'quality_check',
    ];
   
}
