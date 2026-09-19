<x-app-layout>
    <x-slot name="header">Edit Position</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-lg">
        <form method="POST" action="{{ route('positions.update', $position) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Position Title *</label>
                <input type="text" name="position_title" value="{{ old('position_title', $position->position_title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('position_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('positions.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
