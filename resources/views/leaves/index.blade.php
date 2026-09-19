<x-app-layout>
    <x-slot name="header">Leave Requests</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('leaves.index') }}" class="px-3 py-1 rounded {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</a>
                <a href="{{ route('leaves.index', ['filter' => 'pending']) }}" class="px-3 py-1 rounded {{ request('filter') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200' }}">Pending</a>
                <a href="{{ route('leaves.index', ['filter' => 'approved']) }}" class="px-3 py-1 rounded {{ request('filter') == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">Approved</a>
                <a href="{{ route('leaves.index', ['filter' => 'rejected']) }}" class="px-3 py-1 rounded {{ request('filter') == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200' }}">Rejected</a>
            </div>
            <a href="{{ route('leaves.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Apply Leave</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->employee->full_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->leave_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $leave->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $leave->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $leave->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $leave->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($leave->status == 'pending')
                                    <form method="POST" action="{{ route('leaves.approve', $leave) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 mr-2">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('leaves.reject', $leave) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 mr-2">Reject</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 mr-2">--</span>
                                @endif
                                @if(auth()->user()->hasPermission('manage-leaves'))
                                    <form method="POST" action="{{ route('leaves.destroy', $leave) }}" class="inline" onsubmit="return confirm('Delete this leave request?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-900">No leave requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $leaves->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
