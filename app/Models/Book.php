<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'room_id',
        'requester_id',
        'start_hour',
        'finish_hour',
        'start_date',
        'end_date',
        'status',
        'notes',
        'remarks'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
