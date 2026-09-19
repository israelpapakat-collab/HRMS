<x-app-layout>
    <x-slot name="header">Approved Leaves Report</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $leaves->count() }} approved leaves</p>
            <div class="flex gap-2">
                <a href="{{ route('reports.leaves', ['export' => 'csv']) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">CSV</a>
                <a href="{{ route('reports.leaves', ['export' => 'pdf']) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">PDF</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Start</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">End</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Purpose</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $leave->employee->full_name ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->leave_type }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $leave->purpose ?? '--' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-900">No approved leaves found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
