<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $users = [];
    public $selectedUser = null;
    public $message = '';
    public $messages = [];
    public $unreadCounts = [];
    
    public function mount()
    {
        $this->loadUsers();
    }
    
    public function loadUsers()
    {
        $currentUser = Auth::user();
        $this->users = $currentUser->conversationUsers();
        
        // Get unread message counts for each user
        foreach ($this->users as $user) {
            $this->unreadCounts[$user->id] = Message::where('sender_id', $user->id)
                ->where('recipient_id', $currentUser->id)
                ->where('is_read', false)
                ->count();
        }
    }
    
    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadMessages();
        
        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('recipient_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Update unread count
        $this->unreadCounts[$userId] = 0;
        
        // Broadcast that messages were read
        $this->dispatch('messages-read', userId: Auth::id());
    }
    
    public function loadMessages()
    {
        if (!$this->selectedUser) {
            return;
        }
        
        $this->messages = Message::conversation(Auth::id(), $this->selectedUser->id)->get();
    }
    
    public function sendMessage()
    {
        if (empty($this->message) || !$this->selectedUser) {
            return;
        }
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $this->selectedUser->id,
            'content' => $this->message,
            'is_read' => false,
        ]);
        
        $this->message = '';
        $this->messages->push($message);
        
        // Broadcast the message to the recipient
        $this->dispatch('new-message', messageId: $message->id)->to('user.' . $this->selectedUser->id);
    }
    
    #[On('new-message')]
    public function handleNewMessage($messageId)
    {
        // If we're already chatting with the sender, add the message to the conversation
        $message = Message::with('sender')->find($messageId);
        
        if ($message && $this->selectedUser && $message->sender_id === $this->selectedUser->id) {
            $this->messages->push($message);
            $message->markAsRead();
            
            // Broadcast that message was read
            $this->dispatch('messages-read', userId: Auth::id());
        } else if ($message) {
            // Otherwise, just update the unread count
            $this->unreadCounts[$message->sender_id] = ($this->unreadCounts[$message->sender_id] ?? 0) + 1;
            
            // If the user isn't in our list, refresh the user list
            if (!isset($this->unreadCounts[$message->sender_id])) {
                $this->loadUsers();
            }
        }
    }
    
    #[On('messages-read')]
    public function handleMessagesRead($userId)
    {
        // Update the UI to show messages as read
        if ($this->selectedUser && $this->selectedUser->id == $userId) {
            foreach ($this->messages as $message) {
                if ($message->sender_id === Auth::id()) {
                    $message->is_read = true;
                }
            }
        }
    }
    
    public function render()
    {
        return view('livewire.chat');
    }
}