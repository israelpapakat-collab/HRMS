<x-app-layout>
    <x-slot name="header">Edit Warning Letter</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('warning-letters.update', $warningLetter) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', $warningLetter->issue_date->format('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach(['verbal','written','final'] as $t)
                            <option value="{{ $t }}" @selected(old('type', $warningLetter->type) == $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="description" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('description', $warningLetter->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Action Taken</label>
                <textarea name="action_taken" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('action_taken', $warningLetter->action_taken) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="active" @selected(old('status', $warningLetter->status) == 'active')>Active</option>
                    <option value="resolved" @selected(old('status', $warningLetter->status) == 'resolved')>Resolved</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('warning-letters.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
