<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BookController extends Controller
{
    public function index()
{
    $books = Book::withCount('activeRentals as rented_copies')
        ->get()
        ->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'writer_name' => $book->writer_name,
                'total_copies' => $book->total_copies,
                'rented_copies' => $book->rented_copies, 
                'available_copies' => max(0, $book->total_copies - $book->rented_copies), 
            ];
        });

    return Inertia::render('Books/Index', [
        'books' => $books,
        'userId' => Auth::id()
    ]);
}

}
