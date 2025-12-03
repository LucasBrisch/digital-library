<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    
    public function store(Request $request) {

        $request->validate([
        'book_id' => 'required|exists:books,id',
        'user_id' => 'required|exists:users,id',
    ]);

    Rental::create([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'rented_at' => now(),
        'returned_at' => null,
    ]);

    return redirect()->back();
}
}
