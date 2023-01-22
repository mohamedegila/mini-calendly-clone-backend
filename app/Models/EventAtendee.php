<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EventAtendee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'event_id',
        'email',
        'name',
        'link',
        'duration'
    ];
    protected $dates = ['deleted_at'];

     /**
     * Get the atendee that owns the event.
     */
    public function atendee()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
