<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store (Request $request) {
        $request->validate([
            'book_id' => 'required',
            'rate' => 'required'
        ]);

        $rating = Rating::create([
            'rate' => $request->rate,
            'user_id'=> Auth::id(),
            'book_id' => $request->book_id
        ]);

        return redirect()->back();
    }
}
