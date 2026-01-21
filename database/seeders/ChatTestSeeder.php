<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;

class ChatTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $user1 = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        $user3 = User::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        // Create friendships
        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user2->id,
            'status' => 'accepted'
        ]);

        Friendship::create([
            'requester_id' => $user1->id,
            'addressee_id' => $user3->id,
            'status' => 'accepted'
        ]);

        // Create some messages between Alice and Bob
        Message::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'Hey Bob! How are you?'
        ]);

        Message::create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'message' => 'Hi Alice! I\'m doing great, thanks!'
        ]);

        Message::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'That\'s wonderful! Want to grab coffee later?'
        ]);

        // Create a message between Alice and Charlie
        Message::create([
            'sender_id' => $user3->id,
            'receiver_id' => $user1->id,
            'message' => 'Hello Alice! Long time no see!'
        ]);

        $this->command->info('Test users and chat data created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Alice: alice@example.com / password');
        $this->command->info('Bob: bob@example.com / password');
        $this->command->info('Charlie: charlie@example.com / password');
    }
}

