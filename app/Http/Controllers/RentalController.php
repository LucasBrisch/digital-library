<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    
    public function returnbook (Request $request) {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
        ]);

        $rental = Rental::where('id', $request->rental_id)
            ->where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Aluguel não encontrado ou já devolvido!');
        }

        $rental->update([
            'returned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Livro devolvido com sucesso!');
    }

    public function store(Request $request) {

        $request->validate([
        'book_id' => 'required|exists:books,id',
        'user_id' => 'required|exists:users,id',
    ]);

    $isRented = Rental::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->whereNull('returned_at')
            ->exists();   

    if ($isRented) {
        return redirect()->back()->with('error', 'Você já alugou este livro!');
    }

    Rental::create([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'rented_at' => now(),
        'due_date' => now()->addDays($request->rental_days),
        'returned_at' => null,
    ]);

    return redirect()->back();
}
}
