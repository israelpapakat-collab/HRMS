<x-app-layout>
    <x-slot name="header">Employee Report</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $employees->count() }} employees</p>
            <div class="flex gap-2">
                <a href="{{ route('reports.employees', ['export' => 'csv']) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">CSV</a>
                <a href="{{ route('reports.employees', ['export' => 'pdf']) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">PDF</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Gender</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Salary</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Start Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $emp->full_name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $emp->gender ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $emp->department_name ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $emp->position_title ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">K{{ number_format($emp->annual_salary, 2) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $emp->start_date?->format('Y-m-d') ?? '--' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-900">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
