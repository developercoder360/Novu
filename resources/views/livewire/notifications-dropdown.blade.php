<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <button wire:click="toggleDropdown"
        class="relative p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-zinc-900"></span>
        @endif
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        @click.outside="open = false"
        style="max-height: 80vh; overflow-y: auto;">
        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        Mark all as read
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-y-auto">
            @forelse($notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                    class="px-4 py-3 hover:bg-gray-100 dark:hover:bg-zinc-700 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if (isset($notification->data['sender_id']))
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs text-gray-700 dark:text-gray-300 font-semibold">
                                    {{ substr($notification->data['sender_name'] ?? '', 0, 1) }}
                                </div>
                            @else
                                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">{{ $notification->data['sender_name'] ?? 'System' }}</span>
                                @if (isset($notification->data['content']))
                                    <span class="ml-1">sent you a message</span>
                                @else
                                    <span
                                        class="ml-1">{{ $notification->data['message'] ?? 'sent a notification' }}</span>
                                @endif
                            </p>
                            @if (isset($notification->data['content']))
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    {{ $notification->data['content'] }}
                                </p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @unless ($notification->read_at)
                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <span class="sr-only">Mark as read</span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="mt-1">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2 border-t border-gray-200 dark:border-zinc-700">
            <a href="{{ route('notifications') }}"
                class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                View all notifications
            </a>
        </div>
    </div>
</div>
