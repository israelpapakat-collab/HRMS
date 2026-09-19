<x-app-layout>
    <x-slot name="header">Department Report</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $departments->count() }} departments</p>
            <div class="flex gap-2">
                <a href="{{ route('reports.departments', ['export' => 'csv']) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">CSV</a>
                <a href="{{ route('reports.departments', ['export' => 'pdf']) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">PDF</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Total Employees</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $dept->department_name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $dept->employees_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-8 text-center text-gray-900">No departments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
