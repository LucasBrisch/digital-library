<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\intro;

class FriendshipController extends Controller
{
    public function index () {
        $currentUserId = Auth::id();
        $users = User::where('id', '!=', $currentUserId)->get();

        $friendships = Friendship::where('requester_id', $currentUserId)
            ->orWhere('addressee_id', $currentUserId)
            ->get();

        $usersWithStatus = $users->map(function ($user) use ($currentUserId, $friendships) {
            $friendship = $friendships->first(function ($friendship) use ($currentUserId, $user) {
                return ($friendship->requester_id == $currentUserId && $friendship->addressee_id == $user->id) ||
                       ($friendship->requester_id == $user->id && $friendship->addressee_id == $currentUserId);
            });

            $status = 'none';
            if ($friendship) {
                if ($friendship->status === 'accepted') {
                    $status = 'friend';
                } elseif ($friendship->status === 'blocked') {
                    $status = 'blocked';
                } elseif ($friendship->requester_id == $currentUserId) {
                    $status = 'pending';
                } else {
                    $status = 'received';
                }
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'status' => $status,
            ];
        });

        return Inertia::render('User/Friends', [
            'users' => $usersWithStatus,
            'userId' => $currentUserId
        ]);
    }
}
