/**
 * This file handles real-time notifications using Laravel Echo
 */

document.addEventListener('livewire:initialized', () => {
    // Listen for notification events on the private user channel
    if (window.Echo && window.Laravel && window.Laravel.user) {
        const userId = window.Laravel.user.id;
        
        // Listen for notifications on the user's private channel
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                // Dispatch a Livewire event to update the notifications dropdown
                Livewire.dispatch('notification.received', { notification });
                
                // Show a browser notification if supported
                if ('Notification' in window && Notification.permission === 'granted') {
                    const title = notification.sender_name ? 
                        `New message from ${notification.sender_name}` : 
                        'New Notification';
                    
                    const options = {
                        body: notification.content || 'You have a new notification',
                        icon: '/favicon.ico',
                    };
                    
                    new Notification(title, options);
                }
            });
            
        // Listen for new messages on the user's private channel
        window.Echo.private(`messages.${userId}`)
            .listen('NewMessage', (e) => {
                // Dispatch a Livewire event to update the chat component
                Livewire.dispatch('new-message', { messageId: e.messageId });
            })
            .listen('MessagesRead', (e) => {
                // Dispatch a Livewire event to update read status
                Livewire.dispatch('messages-read', { userId: e.userId });
            });
    }
    
    // Request notification permission when the user interacts with the page
    const requestNotificationPermission = () => {
        if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
        
        // Remove the event listeners once permission is requested
        document.removeEventListener('click', requestNotificationPermission);
        document.removeEventListener('keydown', requestNotificationPermission);
    };
    
    document.addEventListener('click', requestNotificationPermission);
    document.addEventListener('keydown', requestNotificationPermission);
});