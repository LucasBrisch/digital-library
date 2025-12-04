<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
            ->get();

        $user = Auth::user();
        
        return Inertia::render('User/Profile', [
            'Rentals' => $rentals,
            'User' => $user
        ]);
    }

}
