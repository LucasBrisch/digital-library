<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile (Request $request) {
        $rentals = Rental::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        return Inertia::render('User/Profile', [
            'rentals' => $rentals
        ]);
    }

}
