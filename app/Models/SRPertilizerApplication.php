<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPertilizerApplication extends Model
{
    use HasFactory;
    protected $table = 'srp_fertilizer_application';
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
