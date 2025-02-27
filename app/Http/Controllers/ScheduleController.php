<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $slug = $request->query('room') ? $request->query('room') : 'inovasi';
        $room = Room::where('slug', $slug)->first();
        $events = Book::where('room_id', $room->id)->get();
        $rooms = Room::all();

        return Inertia::render('Schedule', [
            'events' => $events,
            'selectedRoom' => $slug,
            'rooms' => $rooms
        ]);
    }
}
