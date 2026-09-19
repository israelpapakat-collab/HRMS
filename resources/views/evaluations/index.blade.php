<x-app-layout>
    <x-slot name="header">Performance Reviews</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $evaluations->total() }} reviews</p>
            <a href="{{ route('evaluations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ New Review</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Evaluator</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Rating</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($evaluations as $ev)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $ev->evaluation_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $ev->employee->full_name ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $ev->evaluator->name ?? '--' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $ev->rating ?? '--' }}/10</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $ev->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($ev->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('evaluations.show', $ev) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-900">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">{{ $evaluations->links() }}</div>
    </div>
</x-app-layout>
