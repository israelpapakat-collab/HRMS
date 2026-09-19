<x-app-layout>
    <x-slot name="header">Notifications</x-slot>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between flex-wrap gap-2">
            <div class="flex gap-2">
                <a href="{{ route('notifications.index') }}" class="px-3 py-1 rounded {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1 rounded {{ request('filter') == 'unread' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Unread ({{ $unreadCount }})</a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" class="px-3 py-1 rounded {{ request('filter') == 'read' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Read</a>
            </div>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">Mark all as read</button>
            </form>
            @endif
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 flex items-start gap-4 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50/40' }}">
                    <div class="mt-1 {{ $notification->is_read ? 'bg-gray-200 text-gray-500' : 'bg-blue-600 text-white' }} rounded-full w-8 h-8 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bell text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-900">{{ $notification->title }}</p>
                            <span class="text-xs text-gray-400 shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                    </div>
                    @unless($notification->is_read)
                    <form method="POST" action="{{ route('notifications.markRead', $notification) }}">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 shrink-0">
                            {{ $notification->url ? 'View' : 'Mark read' }}
                        </button>
                    </form>
                    @endunless
                </div>
            @empty
                <div class="p-8 text-center text-gray-900">No notifications.</div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
