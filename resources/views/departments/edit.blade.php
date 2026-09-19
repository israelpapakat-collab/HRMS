<x-app-layout>
    <x-slot name="header">Edit Department</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-lg">
        <form method="POST" action="{{ route('departments.update', $department) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                <input type="text" name="department_name" value="{{ old('department_name', $department->department_name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('department_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('departments.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
