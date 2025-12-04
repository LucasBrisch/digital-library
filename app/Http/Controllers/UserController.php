<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Inertia\Inertia;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile (Request $request) {
        $rentals = Rental::where('user_id', Auth::id())
            ->with('book')
            ->get()
            ->map(function ($rental) {
                $rentalArray = $rental->toArray();
                $rentalArray['book']['isRated'] = Rating::where('book_id', $rental->book_id)->where('user_id', Auth::id())->exists();
                if (Rating::where('book_id', $rental->book_id)->where('user_id', Auth::id())->exists()) {
                    $rentalArray['book']['userRate'] = Rating::where('book_id', $rental->book_id)->where('user_id', Auth::id())->value('rate');
                }

                return $rentalArray;
            });

        $user = Auth::user();
        
        return Inertia::render('User/Profile', [
            'Rentals' => $rentals,
            'User' => $user
        ]);
    }

}
