<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = [
        'district_id',
        'commune_name',
        'commune_code',
        'pin_location',
        'status'
    ];

    public function commune()
    {
        return $this->hasMany(Commune::class);
    }
}
