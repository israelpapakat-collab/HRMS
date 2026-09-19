<x-app-layout>
    <x-slot name="header">Attendance Record</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8 border-t-4 border-blue-600">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Attendance Record</h2>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-900">Date</p>
                    <p class="font-semibold">{{ $attendanceRecord->date->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-900">Status</p>
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $attendanceRecord->status == 'present' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $attendanceRecord->status == 'absent' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $attendanceRecord->status == 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $attendanceRecord->status == 'half_day' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $attendanceRecord->status == 'on_leave' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $attendanceRecord->status)) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-b border-gray-200 py-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-900">Employee</p>
                        <p class="font-semibold">{{ $attendanceRecord->employee->full_name ?? '--' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">Total Hours</p>
                        <p class="font-semibold">{{ $attendanceRecord->total_hours ? number_format($attendanceRecord->total_hours, 1) . 'h' : '--' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm text-gray-900">Clock In</p>
                        <p class="font-semibold">{{ $attendanceRecord->clock_in?->format('H:i') ?? '--' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">Clock Out</p>
                        <p class="font-semibold">{{ $attendanceRecord->clock_out?->format('H:i') ?? '--' }}</p>
                    </div>
                </div>
                @if($attendanceRecord->notes)
                    <div class="mt-3">
                        <p class="text-sm text-gray-900">Notes</p>
                        <p class="font-semibold">{{ $attendanceRecord->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="flex justify-center">
                <a href="{{ route('attendance.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>