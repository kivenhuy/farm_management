<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonMaster extends Model
{
    use HasFactory;
    protected $table = 'seasons';

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_code', 'code');
    }
}
