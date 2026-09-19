<x-app-layout>
    <x-slot name="header">Warning Letters</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $warnings->total() }} records</p>
            <a href="{{ route('warning-letters.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Issue Warning</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Issued By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($warnings as $w)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $w->issue_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $w->employee->full_name ?? '--' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $w->type == 'verbal' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $w->type == 'written' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $w->type == 'final' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($w->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $w->issuedBy->name ?? '--' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $w->status == 'active' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($w->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('warning-letters.edit', $w) }}" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                <form method="POST" action="{{ route('warning-letters.destroy', $w) }}" class="inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-900">No warning letters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">{{ $warnings->links() }}</div>
    </div>
</x-app-layout>
