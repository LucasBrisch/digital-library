<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\ChatController;
use App\Models\Friendship;

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/rent-a-book', [RentalController::class, 'store'])->name('rent.a.book');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/return-book', [RentalController::class, 'returnbook']);
    Route::post('/rate-book', [RatingController::class, 'store']);
    Route::post('/change-rating', [RatingController::class, 'edit']);

    Route::get('/friends', [FriendshipController::class, 'index']);
    Route::post('/send-request', [FriendshipController::class, 'sendRequest'])->name('friends.send.request');
    Route::get('/refuse-request', [FriendshipController::class, 'refuseRequest']);
    Route::post('/accept-request', [FriendshipController::class, 'acceptRequest']);
    Route::post('/remove-friendship', [FriendshipController::class, 'removeFriendship']);

    // Chat routes
    Route::get('/chat/conversations', [ChatController::class, 'getConversations']);
    Route::get('/chat/messages/{friendId}', [ChatController::class, 'getMessages']);
    Route::post('/chat/messages', [ChatController::class, 'sendMessage']);
});