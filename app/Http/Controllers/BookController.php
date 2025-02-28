<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('room')->where('requester_id', Auth::user()->id)->paginate(5);
        return Inertia::render('Book/Index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return Inertia::render('Book/Create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'room_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'start_hour' => 'required',
            'finish_hour' => 'required',
            'notes' => 'nullable',
        ]);

        $book = $validated;
        $book['requester_id'] = Auth::user()->id;
        $book['status'] = 'pending';

        Book::create($book);
        return redirect(route('books.index'))->with('success', 'Berhasil melakukan pemesanan ruang rapat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $rooms = Room::all();
        return Inertia::render('Book/Edit', compact('book', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'room_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'start_hour' => 'required',
            'finish_hour' => 'required',
            'notes' => 'nullable',
        ]);

        $validated['status'] = 'pending';

        $book->update($validated);
        return redirect(route('books.index'))->with('success', 'Berhasil memperbarui pesanan ruang rapat!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect(route('books.index'))->with('success', 'Berhasil menghapus pesanan ruangan!');
    }
}
