<div>
    <x-slot name="title">System Notifications</x-slot>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                System Notifications
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage automated alerts and warehouse system messages.</p>
        </div>
        <div>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Mark All As Read
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden relative">
        @if($notifications->count() > 0)
            <div class="flex flex-col lg:flex-row h-[700px]">
                
                <!-- Inbox List -->
                <div class="w-full lg:w-2/3 border-r border-gray-200 dark:border-slate-700 flex flex-col h-full">
                    <ul class="divide-y divide-gray-200 dark:divide-slate-700 overflow-y-auto flex-1">
                        @foreach($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                $severity = $data['severity'] ?? 'info';
                                
                                $bgClass = $isUnread ? 'bg-blue-50/50 dark:bg-blue-900/10' : 'bg-white dark:bg-slate-800';
                                
                                // Severity Icon Logic
                                if ($severity === 'error') {
                                    $iconColor = 'text-red-500';
                                    $iconBg = 'bg-red-100 dark:bg-red-900/30';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                } elseif ($severity === 'warning') {
                                    $iconColor = 'text-yellow-500';
                                    $iconBg = 'bg-yellow-100 dark:bg-yellow-900/30';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
                                } else {
                                    $iconColor = 'text-blue-500';
                                    $iconBg = 'bg-blue-100 dark:bg-blue-900/30';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                }
                            @endphp
                            
                            <li>
                                <button wire:click="viewDetails('{{ $notification->id }}')" class="w-full text-left px-4 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ $bgClass }} {{ $selectedNotification && $selectedNotification->id === $notification->id ? 'bg-gray-100 dark:bg-slate-700 border-l-4 border-blue-500' : 'border-l-4 border-transparent' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <span class="h-10 w-10 rounded-full flex items-center justify-center {{ $iconBg }}">
                                                <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                                            </span>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium {{ $isUnread ? 'text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
                                                    {{ $data['title'] ?? 'System Alert' }}
                                                </p>
                                                <div class="ml-2 flex-shrink-0 flex">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">
                                                    {{ $data['message'] ?? 'No additional details provided.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                        {{ $notifications->links('pagination::tailwind') }}
                    </div>
                </div>

                <!-- Detail Drawer / Slide Over (Desktop) -->
                <div class="hidden lg:block lg:w-1/3 bg-gray-50 dark:bg-slate-800/50 overflow-y-auto">
                    @if($selectedNotification)
                        @php
                            $data = $selectedNotification->data;
                            $severity = $data['severity'] ?? 'info';
                            $badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
                            if ($severity === 'error') $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                            if ($severity === 'warning') $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300';
                        @endphp
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }} uppercase tracking-wider">
                                    {{ $severity }}
                                </span>
                                <button wire:click="closeDetails" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $data['title'] ?? 'System Alert' }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ $selectedNotification->created_at->format('l, F j, Y g:i A') }}</p>
                            
                            <div class="prose prose-sm dark:prose-invert mb-8">
                                <p class="text-gray-700 dark:text-gray-300">{{ $data['message'] ?? 'No message.' }}</p>
                            </div>

                            @if(isset($data['action_url']))
                                <div class="mb-8">
                                    <a href="{{ $data['action_url'] }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        {{ $data['action_text'] ?? 'View Details' }}
                                    </a>
                                </div>
                            @endif

                            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Raw Metadata Context</h4>
                                <div class="bg-gray-100 dark:bg-slate-900 rounded-md p-4 overflow-x-auto">
                                    <pre class="text-xs text-gray-800 dark:text-gray-300 font-mono"><code>{{ json_encode($data['context'] ?? $data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center p-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>Select a notification to view its details and raw context.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="py-16 text-center text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">All Caught Up!</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You don't have any system notifications at this time.</p>
            </div>
        @endif
    </div>
</div>
