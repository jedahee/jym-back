<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    public $table = 'calendar_events';
    public $timestamps = false;

    protected $fillable = [
        'eventId',
        'date'
    ];

    // Relaciones
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
