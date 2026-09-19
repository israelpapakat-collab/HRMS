<x-app-layout>
    <x-slot name="header">Documents</x-slot>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between flex-wrap gap-2">
            <form method="GET" action="{{ route('documents.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" placeholder="Search documents or employee..." value="{{ request('search') }}"
                       class="border border-gray-300 rounded-lg px-4 py-2 w-72 focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                @if(request('search'))
                    <a href="{{ route('documents.index') }}" class="text-gray-900 hover:text-gray-700 text-sm">Clear</a>
                @endif
            </form>
            @if(Auth::user()->hasPermission('manage-documents'))
            <a href="{{ route('documents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Upload Document</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Uploaded By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $document->name }}</div>
                                <div class="text-xs text-gray-500">{{ $document->description ?? $document->original_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $document->employee->full_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($document->file_size >= 1048576)
                                    {{ number_format($document->file_size / 1048576, 1) }} MB
                                @else
                                    {{ number_format($document->file_size / 1024, 0) }} KB
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $document->uploader->name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $document->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-800 mr-3">Download</a>
                                @if(Auth::user()->hasPermission('manage-documents'))
                                <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-900">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $documents->links() }}
        </div>
    </div>
</x-app-layout>
