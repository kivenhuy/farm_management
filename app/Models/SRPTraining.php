<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPTraining extends Model
{
    use HasFactory;
    protected $table = 'srp_trainings';
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
