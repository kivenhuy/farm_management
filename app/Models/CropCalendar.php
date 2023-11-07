<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropCalendar extends Model
{
    use HasFactory;
    protected $table = 'crop_calendars';

    public function cropCalendarDetails()
    {
        return $this->hasMany(CropCalendarDetail::class, 'crop_calendar_id', 'id');
    }
}
