<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerDetails extends Model
{
    use HasFactory;

    protected $with = ['farm_lands'];
    protected $table = 'farmer_details';
    
    protected $fillable = [
        'staff_id',
        'user_id',
        'enrollment_date',
        'enrollment_place',
        'full_name',
        'phone_number',
        'identity_proof',
        'country',
        'province',
        'district',
        'commune',
        'village',
        'lng',
        'lat',
        'proof_no',
        'gender',
        'farmer_code',
        'dob',
        'farmer_photo',
        'id_proof_photo',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function farm_lands()
    {
        return $this->hasMany(FarmLand::class, 'farmer_id', 'id');
    }
}
