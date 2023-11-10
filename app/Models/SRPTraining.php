<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPTraining extends Model
{
    protected $table = 'srp_trainings';
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srps_id',
        'question',
        'answer',
        'score',
    ];
}
