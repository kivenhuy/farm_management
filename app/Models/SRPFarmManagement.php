<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPFarmManagement extends Model
{
    use HasFactory;
    protected $table = 'srp_farm_managements';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'title',
        'type',
        'question',
        'answer',
        'score',
    ];
}
