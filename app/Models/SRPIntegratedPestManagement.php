<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPIntegratedPestManagement extends Model
{
    use HasFactory;
    protected $table = 'srp_integrated_pest_management';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'question',
        'answer',
        'score',
        'created_at',
    ];
}
