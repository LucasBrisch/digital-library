<?php

use Inertia\Inertia;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return Inertia::render('Books/Index');
});

Route::get('/books', [BookController::class, 'index']);