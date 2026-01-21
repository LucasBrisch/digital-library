<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friendship;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_cannot_chat_if_not_friends()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)
            ->getJson("/chat/messages/{$user2->id}");

        $response->assertStatus(403);
    }

    public function test_friends_can_send_messages()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create friendship
        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user2->id,
            'status' => 'accepted'
        ]);

        $response = $this->actingAs($user1)
            ->postJson('/chat/messages', [
                'receiver_id' => $user2->id,
                'message' => 'Hello friend!'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'sender_id',
                'receiver_id',
                'message',
                'is_read',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'Hello friend!',
            'is_read' => false
        ]);
    }

    public function test_friends_can_retrieve_messages()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create friendship
        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user2->id,
            'status' => 'accepted'
        ]);

        // Create some messages
        Message::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'Message 1'
        ]);

        Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'message' => 'Message 2'
        ]);

        $response = $this->actingAs($user1)
            ->getJson("/chat/messages/{$user2->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_messages_marked_as_read_when_retrieved()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create friendship
        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user2->id,
            'status' => 'accepted'
        ]);

        // Create unread message from user2 to user1
        $message = Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'message' => 'Unread message',
            'is_read' => false
        ]);

        // User1 retrieves messages
        $this->actingAs($user1)
            ->getJson("/chat/messages/{$user2->id}");

        // Check that message is now marked as read
        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_get_conversations_returns_friends_with_messages()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Create friendships
        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user2->id,
            'status' => 'accepted'
        ]);

        Friendship::create([
            'requester_id' => $user3->id,
            'addressee_id' => $user1->id,
            'status' => 'accepted'
        ]);

        // Create a message with user2
        Message::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'Hello'
        ]);

        $response = $this->actingAs($user1)
            ->getJson('/chat/conversations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'last_message',
                    'last_message_time',
                    'unread_count'
                ]
            ]);
    }
}
