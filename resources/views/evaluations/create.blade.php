<x-app-layout>
    <x-slot name="header">New Performance Review</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('evaluations.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee *</label>
                <select name="employee_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Select</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Review Date *</label>
                    <input type="date" name="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating (0-10)</label>
                    <input type="number" step="0.1" min="0" max="10" name="rating" value="{{ old('rating') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Comments</label>
                <textarea name="comments" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('comments') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Goals</label>
                <textarea name="goals" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('goals') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Review Date</label>
                <input type="date" name="next_review_date" value="{{ old('next_review_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Submit Review</button>
                <a href="{{ route('evaluations.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
