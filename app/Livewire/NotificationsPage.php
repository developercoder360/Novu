<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationsPage extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $perPage = 10;
    public $page = 1;
    public $hasMorePages = false;
    
    public function mount()
    {
        $this->loadNotifications();
    }
    
    public function loadNotifications()
    {
        $user = Auth::user();
        $offset = ($this->page - 1) * $this->perPage;
        
        // Get notifications with pagination
        $this->notifications = $user->notifications()
            ->latest()
            ->skip($offset)
            ->take($this->perPage + 1) // Take one extra to check if there are more pages
            ->get();
            
        // Check if there are more pages
        if ($this->notifications->count() > $this->perPage) {
            $this->hasMorePages = true;
            $this->notifications = $this->notifications->take($this->perPage);
        } else {
            $this->hasMorePages = false;
        }
        
        $this->unreadCount = $user->unreadNotifications()->count();
    }
    
    public function loadMore()
    {
        $this->page++;
        $this->loadNotifications();
    }
    
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }
    
    #[On('notification.received')]
    public function handleNewNotification()
    {
        $this->loadNotifications();
    }
    
    public function render()
    {
        return view('livewire.notifications-page');
    }
}