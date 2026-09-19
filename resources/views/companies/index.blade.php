<x-app-layout>
    <x-slot name="header">Companies</x-slot>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <p class="text-gray-900">{{ $companies->total() }} companies</p>
            <a href="{{ route('companies.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Add Company</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employees</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($companies as $company)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $company->code }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $company->description ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $company->employees_count }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($company->is_active)
                                    <span class="text-green-700">Active</span>
                                @else
                                    <span class="text-red-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline" onsubmit="return confirm('Delete this company?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-900">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $companies->links() }}
        </div>
    </div>
</x-app-layout>
