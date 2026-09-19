<x-app-layout>
    <x-slot name="header">Attendance Records</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $records->total() }} records</p>
            <a href="{{ route('attendance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Add Record</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Clock In</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Clock Out</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Hours</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($records as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $r->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $r->employee->full_name ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $r->clock_in?->format('H:i') ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $r->clock_out?->format('H:i') ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $r->total_hours ? number_format($r->total_hours, 1) . 'h' : '--' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $r->status == 'present' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $r->status == 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $r->status == 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $r->status == 'half_day' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $r->status == 'on_leave' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('attendance.show', $r) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-900">No attendance records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">{{ $records->links() }}</div>
    </div>
</x-app-layout>
