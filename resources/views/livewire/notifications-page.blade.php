<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-zinc-700">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Notifications</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        You have {{ $unreadCount }} unread notification{{ $unreadCount != 1 ? 's' : '' }}
                    </p>
                </div>
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mark all as read
                    </button>
                @endif
            </div>

            <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($notifications as $notification)
                    <div wire:key="notification-{{ $notification->id }}"
                        class="px-4 py-5 sm:px-6 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if (isset($notification->data['sender_id']))
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm text-gray-700 dark:text-gray-300 font-semibold">
                                        {{ substr($notification->data['sender_name'] ?? '', 0, 1) }}
                                    </div>
                                @else
                                    <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notification->data['sender_name'] ?? 'System' }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="mt-1">
                                    @if (isset($notification->data['content']))
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $notification->data['content'] }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $notification->data['message'] ?? 'Sent you a notification' }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-2 flex">
                                    @if (isset($notification->data['message_id']))
                                        <a href="{{ route('chat') }}"
                                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            View message
                                        </a>
                                    @endif
                                    @unless ($notification->read_at)
                                        <button wire:click="markAsRead('{{ $notification->id }}')" 
                                            class="ml-4 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            Mark as read
                                        </button>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            You don't have any notifications yet.
                        </p>
                    </div>
                @endforelse
            </div>

            @if ($hasMorePages)
                <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-zinc-700">
                    <button wire:click="loadMore"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-zinc-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Load more
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>