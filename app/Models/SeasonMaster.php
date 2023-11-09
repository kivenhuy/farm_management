<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonMaster extends Model
{
    use HasFactory;
    protected $table = 'season_masters';
    // protected $appends = ['season_name'];

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_code', 'code');
    }

    // public function getSeasonNameAttribute()
    // {
    //     return $this->season->name;
    // }
}
