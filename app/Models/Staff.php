<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $with = ['farmer_details'];

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
        return $this->hasMany(FarmerDetails::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }
}
