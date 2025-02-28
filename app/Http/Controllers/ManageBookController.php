<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ManageBookController extends Controller
{
    public function index()
    {
        $books = Book::with('room', 'requester.organization')->paginate(20);
        return Inertia::render('ManageBook/Index', compact('books'));
    }

    public function approve(Book $book)
    {
        $book->update([
            'status' => 'approved'
        ]);
        return redirect(route('manage-books.index'))->with('success', 'Berhasil menyutujui pesanan ruangan!');
    }

    public function reject(Request $request, Book $book)
    {
        $validated = $request->validate([
            'remark' => 'nullable'
        ]);

        $book->update([
            'status' => 'rejected',
            'remarks' => $validated['remark']
        ]);
        
        return redirect(route('manage-books.index'))->with('success', 'Berhasil menolak pesanan ruangan!');
    }
}
