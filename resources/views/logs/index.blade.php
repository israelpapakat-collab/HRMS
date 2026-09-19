<x-app-layout>
    <x-slot name="header">Activity Logs</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 space-y-4">
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('logs.index') }}" class="px-3 py-1 rounded {{ !request('module') && !request('action') ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</a>
                <a href="{{ route('logs.index', ['module' => 'Employee']) }}" class="px-3 py-1 rounded {{ request('module') == 'Employee' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Employees</a>
                <a href="{{ route('logs.index', ['module' => 'LeaveRequest']) }}" class="px-3 py-1 rounded {{ request('module') == 'LeaveRequest' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Leaves</a>
                <a href="{{ route('logs.index', ['module' => 'Payroll']) }}" class="px-3 py-1 rounded {{ request('module') == 'Payroll' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Payroll</a>
                <a href="{{ route('logs.index', ['module' => 'User']) }}" class="px-3 py-1 rounded {{ request('module') == 'User' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">User Accounts</a>
                <a href="{{ route('logs.index', ['module' => 'Company']) }}" class="px-3 py-1 rounded {{ request('module') == 'Company' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Companies</a>
            </div>

            <div class="flex gap-2 flex-wrap items-center justify-between">
                <div class="flex gap-2">
                    <a href="{{ route('logs.index', ['action' => 'created']) }}" class="px-3 py-1 rounded {{ request('action') == 'created' ? 'bg-green-600 text-white' : 'bg-gray-100' }}">Added</a>
                    <a href="{{ route('logs.index', ['action' => 'updated']) }}" class="px-3 py-1 rounded {{ request('action') == 'updated' ? 'bg-blue-600 text-white' : 'bg-gray-100' }}">Updated</a>
                    <a href="{{ route('logs.index', ['action' => 'deleted']) }}" class="px-3 py-1 rounded {{ request('action') == 'deleted' ? 'bg-red-600 text-white' : 'bg-gray-100' }}">Deleted</a>
                </div>

                <form method="GET" action="{{ route('logs.index') }}" class="flex gap-2">
                    @if(request('module'))<input type="hidden" name="module" value="{{ request('module') }}">@endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user or description..."
                           class="border border-gray-300 rounded-lg px-4 py-2 w-72 focus:outline-none focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('logs.index', request()->except('search')) }}" class="text-gray-900 hover:text-gray-700 text-sm self-center">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Date/Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Module</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($log->user)
                                    <div class="text-gray-900">{{ $log->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->user->roles->pluck('name')->implode(', ') ?: 'No role' }}</div>
                                @else
                                    <div class="text-gray-400">System</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $log->action == 'created' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $log->action == 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $log->action == 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $log->action == 'approved' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $log->action == 'rejected' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $log->module }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $log->description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $log->ip_address ?? '--' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-900">No logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
