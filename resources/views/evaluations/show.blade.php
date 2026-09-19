<x-app-layout>
    <x-slot name="header">Performance Review</x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="border-b pb-4 mb-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-900">Employee</p>
                    <p class="font-semibold">{{ $evaluation->employee->full_name ?? '--' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-900">Date</p>
                    <p class="font-semibold">{{ $evaluation->evaluation_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-900">Rating</p>
            <p class="text-3xl font-bold {{ $evaluation->rating >= 7 ? 'text-green-600' : ($evaluation->rating >= 4 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ $evaluation->rating ?? 'N/A' }}/10
            </p>
        </div>

        @if($evaluation->comments)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700">Comments</p>
                <p class="text-gray-900">{{ $evaluation->comments }}</p>
            </div>
        @endif

        @if($evaluation->goals)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700">Goals</p>
                <p class="text-gray-900">{{ $evaluation->goals }}</p>
            </div>
        @endif

        @if($evaluation->next_review_date)
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-sm text-blue-700">Next Review: {{ $evaluation->next_review_date->format('M d, Y') }}</p>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('evaluations.index') }}" class="text-blue-600 hover:text-blue-800">Back to Reviews</a>
        </div>
    </div>
</x-app-layout>
