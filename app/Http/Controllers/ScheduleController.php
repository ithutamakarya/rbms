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
        // $slug = $request->query('room') ? $request->query('room') : 'inovasi';
        // $room = Room::where('slug', $slug)->first();
        // $events = Book::with('requester.organization')->where('room_id', $room->id)->get();
        $events = Book::with('requester.organization', 'room')->get();
        $rooms = Room::all();

        if(!empty($request->query('room'))) {
            $slug = $request->query('room') ? $request->query('room') : 'inovasi';
            $room = Room::where('slug', $slug)->first();
            $events = Book::with('requester.organization', 'room')->where('room_id', $room->id)->get();

            return Inertia::render('Schedule', [
                'events' => $events,
                'rooms' => $rooms,
                'selectedRoom' => $slug,
            ]);
        }

        return Inertia::render('Schedule', [
            'events' => $events,
            'rooms' => $rooms,
            'selectedRoom' => '',
        ]);
    }
}
