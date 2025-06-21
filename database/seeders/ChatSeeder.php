<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Seed the chat with test data.
     */
    public function run(): void
    {
        // Create some test users if they don't exist
        $mainUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
        
        // Create additional users for conversations
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Mike Johnson', 'email' => 'mike@example.com'],
        ];
        
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );
            
            // Create some messages between main user and this user
            Message::create([
                'sender_id' => $mainUser->id,
                'recipient_id' => $user->id,
                'content' => "Hello {$user->name}, how are you?",
                'is_read' => true,
                'created_at' => now()->subDays(rand(1, 5))->subHours(rand(1, 23)),
            ]);
            
            Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $mainUser->id,
                'content' => "Hi {$mainUser->name}, I'm doing well! How about you?",
                'is_read' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 5))->subHours(rand(1, 23)),
            ]);
            
            // Add a few more messages with random read status
            for ($i = 0; $i < rand(1, 3); $i++) {
                Message::create([
                    'sender_id' => $mainUser->id,
                    'recipient_id' => $user->id,
                    'content' => "This is message #{$i} from me to you.",
                    'is_read' => true,
                    'created_at' => now()->subDays(rand(0, 4))->subHours(rand(1, 23)),
                ]);
                
                Message::create([
                    'sender_id' => $user->id,
                    'recipient_id' => $mainUser->id,
                    'content' => "This is message #{$i} from me to you.",
                    'is_read' => rand(0, 1),
                    'created_at' => now()->subDays(rand(0, 4))->subHours(rand(1, 23)),
                ]);
            }
        }
    }
}