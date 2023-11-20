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
        'cultivation_id',
        'season_id',
        'variety',
        'sowing_date',
        'quantity',
        'min_price',
        'max_price',
        'product_id',
        'date_for_harvest',
        'aviable_date',
        'grade',
        'age_of_crop',
        'quality_check',
    ];
   
}
