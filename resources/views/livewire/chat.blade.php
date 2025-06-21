<div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar with user list -->
    <div class="w-1/4 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-y-auto">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Conversations</h2>
        </div>
        
        @if(count($users) > 0)
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)
                    <li wire:key="user-{{ $user->id }}" 
                        wire:click="selectUser({{ $user->id }})" 
                        class="flex items-center p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out
                        {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-50 dark:bg-gray-700' : '' }}">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold">
                            {{ $user->initials() }}
                        </div>
                        <div class="ml-3 flex-grow">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                        </div>
                        @if(isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0)
                            <div class="flex-shrink-0 bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                {{ $unreadCounts[$user->id] }}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                No conversations yet
            </div>
        @endif
    </div>
    
    <!-- Chat area -->
    <div class="flex-1 flex flex-col">
        @if($selectedUser)
            <!-- Chat header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold">
                    {{ $selectedUser->initials() }}
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $selectedUser->name }}</h3>
                </div>
            </div>
            
            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-100 dark:bg-gray-900" id="chat-messages">
                @foreach($messages as $message)
                    <div wire:key="message-{{ $message->id }}" 
                         class="mb-4 {{ $message->sender_id === auth()->id() ? 'flex justify-end' : 'flex justify-start' }}">
                        <div class="{{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-white' }} rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                            <div class="text-sm">
                                {{ $message->content }}
                            </div>
                            <div class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} flex items-center justify-end">
                                {{ $message->created_at->format('g:i A') }}
                                @if($message->sender_id === auth()->id())
                                    <span class="ml-1">
                                        @if($message->is_read)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Message input -->
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="sendMessage" class="flex items-center">
                    <input type="text" wire:model="message" 
                           class="flex-1 border border-gray-300 dark:border-gray-600 rounded-l-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Type your message...">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-r-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center bg-gray-100 dark:bg-gray-900">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-xl font-medium">Select a conversation</p>
                    <p class="mt-1">Choose a user from the sidebar to start chatting</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Auto-scroll to bottom of messages when they change
        Livewire.hook('morph.updated', ({ el }) => {
            if (el.id === 'chat-messages') {
                el.scrollTop = el.scrollHeight;
            }
        });
    });
</script>