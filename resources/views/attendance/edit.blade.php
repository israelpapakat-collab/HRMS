<x-app-layout>
    <x-slot name="header">Edit Attendance</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('attendance.update', $attendanceRecord) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" value="{{ old('date', $attendanceRecord->date->format('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clock In</label>
                    <input type="time" name="clock_in" value="{{ old('clock_in', $attendanceRecord->clock_in?->format('H:i')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clock Out</label>
                    <input type="time" name="clock_out" value="{{ old('clock_out', $attendanceRecord->clock_out?->format('H:i')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @foreach(['present','absent','late','half_day','on_leave'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $attendanceRecord->status) == $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('notes', $attendanceRecord->notes) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('attendance.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
