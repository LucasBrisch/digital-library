<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Get chat messages between auth user and a friend
     */
    public function getMessages(Request $request, $friendId)
    {
        $currentUserId = Auth::id();
        
        // Verify friendship
        if (!$this->areFriends($currentUserId, $friendId)) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $messages = Message::where(function ($query) use ($currentUserId, $friendId) {
            $query->where('sender_id', $currentUserId)
                  ->where('receiver_id', $friendId);
        })
        ->orWhere(function ($query) use ($currentUserId, $friendId) {
            $query->where('sender_id', $friendId)
                  ->where('receiver_id', $currentUserId);
        })
        ->with(['sender:id,name', 'receiver:id,name'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $friendId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Send a message to a friend
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000|min:1',
        ]);

        $currentUserId = Auth::id();
        $receiverId = $request->receiver_id;

        // Verify friendship
        if (!$this->areFriends($currentUserId, $receiverId)) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        // Sanitize the message to prevent XSS
        $sanitizedMessage = htmlspecialchars($request->message, ENT_QUOTES, 'UTF-8');

        $message = Message::create([
            'sender_id' => $currentUserId,
            'receiver_id' => $receiverId,
            'message' => $sanitizedMessage,
            'is_read' => false,
        ]);

        $message->load(['sender:id,name', 'receiver:id,name']);

        return response()->json($message, 201);
    }

    /**
     * Get list of conversations (friends with last message)
     */
    public function getConversations()
    {
        $currentUserId = Auth::id();

        // Get all friends
        $friends = User::whereIn('id', function ($query) use ($currentUserId) {
            $query->select('addressee_id')
                ->from('friendship')
                ->where('requester_id', $currentUserId)
                ->where('status', 'accepted');
        })
        ->orWhereIn('id', function ($query) use ($currentUserId) {
            $query->select('requester_id')
                ->from('friendship')
                ->where('addressee_id', $currentUserId)
                ->where('status', 'accepted');
        })
        ->get();

        $conversations = $friends->map(function ($friend) use ($currentUserId) {
            $lastMessage = Message::where(function ($query) use ($currentUserId, $friend) {
                $query->where('sender_id', $currentUserId)
                      ->where('receiver_id', $friend->id);
            })
            ->orWhere(function ($query) use ($currentUserId, $friend) {
                $query->where('sender_id', $friend->id)
                      ->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->first();

            $unreadCount = Message::where('sender_id', $friend->id)
                ->where('receiver_id', $currentUserId)
                ->where('is_read', false)
                ->count();

            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'last_message' => $lastMessage ? $lastMessage->message : null,
                'last_message_time' => $lastMessage ? $lastMessage->created_at : null,
                'unread_count' => $unreadCount,
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Check if two users are friends
     */
    private function areFriends($userId1, $userId2)
    {
        return Friendship::where(function ($query) use ($userId1, $userId2) {
            $query->where('requester_id', $userId1)
                  ->where('addressee_id', $userId2);
        })
        ->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('requester_id', $userId2)
                  ->where('addressee_id', $userId1);
        })
        ->where('status', 'accepted')
        ->exists();
    }
}
