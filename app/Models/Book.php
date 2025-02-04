<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'room_id',
        'requester',
        'booking_date',
        'start_hour',
        'finish_hour',
        'status',
    ];
}
