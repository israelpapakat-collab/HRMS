<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Employee Details</span>
            @if(Auth::user()->hasPermission('view-employees'))
            <a href="{{ route('employees.index', request('department') ? ['department' => request('department')] : []) }}" class="text-sm text-blue-600 hover:text-blue-800">Back to employees</a>
            @endif
        </div>
    </x-slot>

    {{-- Profile Header Card --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 flex flex-wrap items-center gap-5 border-b border-gray-100">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-blue-500/20">
                {{ substr($employee->full_name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-bold text-gray-900">{{ $employee->full_name }}</h2>
                <p class="text-sm text-gray-500">{{ $employee->position_title ?? 'No position' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium">
                    <i class="fa-solid fa-building"></i> {{ $employee->company_name ?? '--' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-sky-50 text-sky-700 text-xs font-medium">
                    <i class="fa-solid fa-users"></i> {{ $employee->department_name ?? '--' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-medium">
                    <i class="fa-solid fa-briefcase"></i> {{ $employee->position_title ?? '--' }}
                </span>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Gender</p>
                <p class="font-semibold text-gray-900">{{ $employee->gender ?? '--' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date of Birth</p>
                <p class="font-semibold text-gray-900">{{ $employee->date_of_birth?->format('M d, Y') ?? '--' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Marital Status</p>
                <p class="font-semibold text-gray-900">{{ $employee->marital_status ?? '--' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Start Date</p>
                <p class="font-semibold text-gray-900">{{ $employee->start_date?->format('M d, Y') ?? '--' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Employment Details --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-blue-600"></i> Employment
            </h3>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Company</dt>
                    <dd class="font-medium text-gray-900">{{ $employee->company_name ?? '--' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Department</dt>
                    <dd class="font-medium text-gray-900">{{ $employee->department_name ?? '--' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Position</dt>
                    <dd class="font-medium text-gray-900">{{ $employee->position_title ?? '--' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Office Location</dt>
                    <dd class="font-medium text-gray-900">{{ $employee->office_location ?? '--' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Annual Salary</dt>
                    <dd class="font-medium text-gray-900">K{{ number_format((float) $employee->annual_salary, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Hourly Rate</dt>
                    <dd class="font-medium text-gray-900">K{{ number_format((float) $employee->hourly_rate, 2) }}</dd>
                </div>
            </dl>
        </div>

        {{-- Leave Balances --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-emerald-600"></i> Leave Balances
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Annual Leave</span>
                        <span class="font-bold text-gray-900">{{ $employee->annual_leave_balance }} days</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(100, ($employee->annual_leave_balance ?? 0) * 10) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Sick Leave</span>
                        <span class="font-bold text-gray-900">{{ $employee->sick_leave_balance }} days</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-sky-500 h-full rounded-full" style="width: {{ min(100, ($employee->sick_leave_balance ?? 0) * 10) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Leave Requests --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-amber-600"></i> Recent Leave Requests
            </h3>
            @forelse($employee->leaveRequests->sortByDesc('created_at')->take(4) as $leave)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900 capitalize">{{ $leave->leave_type }}</p>
                        <p class="text-xs text-gray-500">{{ $leave->start_date?->format('M d') }} - {{ $leave->end_date?->format('M d, Y') }}</p>
                    </div>
                    @if($leave->status === 'pending')
                        <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-medium">Pending</span>
                    @elseif($leave->status === 'approved')
                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium">Approved</span>
                    @else
                        <span class="px-2 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-medium">Rejected</span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-6">No leave requests yet.</p>
            @endforelse
        </div>
    </div>

    @if(Auth::user()->hasPermission('manage-employees'))
    <div class="mt-6 flex items-center gap-3">
        <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-pen"></i> Edit Employee
        </a>
        <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Delete {{ $employee->full_name }}? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 bg-rose-600 text-white px-5 py-2 rounded-lg hover:bg-rose-700 transition-colors">
                <i class="fa-solid fa-trash-can"></i> Delete Employee
            </button>
        </form>
    </div>
    @endif
</x-app-layout>