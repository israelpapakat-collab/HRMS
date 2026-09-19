<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome Banner --}}
    <div class="mb-8 bg-gradient-to-r from-primary-600 via-primary-700 to-surface-900 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl shadow-primary-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-white/5 rounded-full translate-y-1/2"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-primary-200 text-sm max-w-xl">Here's what's happening with your organization today. Monitor employees, track attendance, and manage operations.</p>
        </div>
    </div>

    {{-- Department Manager Module Cards --}}
    @if(Auth::user()->isDepartmentManager())
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-surface-900">My Modules</h3>
            <a href="{{ route('modules') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($modules as $module)
                @if($module['accessible'])
                    <a href="{{ $module['route'] }}"
                       class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 hover:shadow-lg hover:border-emerald-200 hover:-translate-y-0.5 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i class="{{ $module['icon'] }} text-white text-lg"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                <i class="fa-solid fa-check text-[10px]"></i> Open
                            </span>
                        </div>
                        <h3 class="font-bold text-surface-900 mb-1">{{ $module['name'] }}</h3>
                        <p class="text-sm text-surface-500">{{ $module['description'] }}</p>
                    </a>
                @else
                    <div class="bg-surface-100/60 rounded-2xl border border-dashed border-surface-300 p-6 opacity-60 grayscale cursor-not-allowed select-none"
                         title="You do not have permission to access this module.">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-surface-300 flex items-center justify-center shadow-inner">
                                <i class="fa-solid fa-lock text-surface-500 text-lg"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold text-surface-500 bg-surface-200 px-2.5 py-1 rounded-lg" title="Locked">
                                <i class="fa-solid fa-lock text-[10px]"></i> Locked
                            </span>
                        </div>
                        <h3 class="font-bold text-surface-500 mb-1 flex items-center gap-2">
                            {{ $module['name'] }}
                            <span class="text-surface-400" title="Locked">🔒</span>
                        </h3>
                        <p class="text-sm text-surface-400">{{ $module['description'] }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 hover:shadow-lg hover:border-primary-200 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-surface-500 text-sm font-medium mb-1">Total Employees</p>
                    <p class="text-3xl font-bold text-surface-900">{{ $totalEmployees }}</p>
                    <p class="text-xs text-surface-400 mt-1">{{ $employeesByDepartment->count() }} departments</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 hover:shadow-lg hover:border-emerald-200 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-surface-500 text-sm font-medium mb-1">Present Today</p>
                    <p class="text-3xl font-bold text-surface-900">{{ $presentToday }}</p>
                    <p class="text-xs text-surface-400 mt-1">{{ $todayAttendance }} total records</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 hover:shadow-lg hover:border-amber-200 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-surface-500 text-sm font-medium mb-1">Pending Leaves</p>
                    <p class="text-3xl font-bold text-surface-900">{{ $pendingLeaves }}</p>
                    <p class="text-xs text-surface-400 mt-1">{{ $approvedLeaves }} approved, {{ $rejectedLeaves }} rejected</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-calendar-clock text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 hover:shadow-lg hover:border-rose-200 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-surface-500 text-sm font-medium mb-1">Monthly Payroll</p>
                    <p class="text-3xl font-bold text-surface-900">K{{ number_format($monthlyPayroll, 0) }}</p>
                    <p class="text-xs text-surface-400 mt-1">K{{ number_format($totalSalary, 0) }} annual</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-coins text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Payslips --}}
    @if(Auth::user()->hasPermission('view-payroll'))
    <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-surface-900">@if(Auth::user()->isEmployee()) My Payslips @else Recent Payslips @endif</h3>
                <p class="text-sm text-surface-500">Latest payroll details processed for you</p>
            </div>
            <a href="{{ route('payrolls.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider">
                        <th class="pb-3 pr-4">Payroll</th>
                        <th class="pb-3 pr-4">Gross Pay</th>
                        <th class="pb-3 pr-4">Taxable</th>
                        <th class="pb-3 pr-4">Tax</th>
                        <th class="pb-3 pr-4">Deductions</th>
                        <th class="pb-3 pr-4">Net Pay</th>
                        <th class="pb-3 pr-4">Status</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse($recentPayrolls as $payroll)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-rose-400 to-rose-600 rounded-lg flex items-center justify-center text-white text-xs shadow-lg shadow-rose-500/20">
                                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-surface-700">{{ $payroll->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3 pr-4 text-sm text-surface-600">K{{ number_format($payroll->gross_pay, 2) }}</td>
                            <td class="py-3 pr-4 text-sm text-surface-600">K{{ number_format($payroll->taxable, 2) }}</td>
                            <td class="py-3 pr-4 text-sm text-rose-600">- K{{ number_format($payroll->tax, 2) }}</td>
                            <td class="py-3 pr-4 text-sm text-rose-600">- K{{ number_format($payroll->total_deductions, 2) }}</td>
                            <td class="py-3 pr-4 text-sm font-bold text-surface-800">K{{ number_format($payroll->net_pay, 2) }}</td>
                            <td class="py-3 pr-4">
                                @if($payroll->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                @elseif($payroll->status === 'processed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Processed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface-100 text-surface-600 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-surface-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-sm">
                                <a href="{{ route('payrolls.show', $payroll) }}" class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium">
                                    View<i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-surface-400">
                                <i class="fa-solid fa-file-invoice-dollar text-2xl mb-2"></i>
                                <p class="text-sm">No payslips yet. Your payslips will appear here once HR processes your payroll.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Employees by Department --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-surface-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-surface-900">Employees by Department</h3>
                    <p class="text-sm text-surface-500">Distribution across departments</p>
                </div>
                <a href="{{ route('departments.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @foreach($employeesByDepartment as $dept)
                    @php
                        $maxCount = $employeesByDepartment->max('employees_count');
                        $percentage = $maxCount > 0 ? ($dept->employees_count / $maxCount) * 100 : 0;
                        $gradients = [
                            'linear-gradient(90deg, #818cf8, #4f46e5)',
                            'linear-gradient(90deg, #34d399, #059669)',
                            'linear-gradient(90deg, #fbbf24, #d97706)',
                            'linear-gradient(90deg, #fb7185, #e11d48)',
                            'linear-gradient(90deg, #a78bfa, #7c3aed)',
                            'linear-gradient(90deg, #22d3ee, #0891b2)',
                            'linear-gradient(90deg, #fb923c, #ea580c)',
                            'linear-gradient(90deg, #2dd4bf, #0d9488)',
                        ];
                        $gradient = $gradients[$loop->index % count($gradients)];
                    @endphp
                    <a href="{{ route('employees.index', ['department' => $dept->department_name]) }}" class="flex items-center gap-4 group">
                    <div class="w-32 shrink-0">
                        <p class="text-sm font-medium text-surface-700 truncate group-hover:text-primary-600">{{ $dept->department_name }}</p>
                    </div>
                    <div class="flex-1 bg-surface-100 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%; background: {{ $gradient }}"></div>
                    </div>
                    <div class="w-16 text-right flex items-center justify-end gap-1.5">
                        <span class="text-sm font-bold text-surface-800 group-hover:text-primary-600">{{ $dept->employees_count }}</span>
                        <i class="fa-solid fa-arrow-right text-xs text-surface-300 group-hover:text-primary-500"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Gender & Payroll Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6">
            <h3 class="text-lg font-bold text-surface-900 mb-4">Quick Overview</h3>
            <div class="space-y-5">
                <div class="bg-surface-50 rounded-xl p-4">
                    <p class="text-sm text-surface-500 mb-2 font-medium">Gender Distribution</p>
                    <div class="flex justify-between items-center">
                        @php
                            $maleCount = $employeeGenderStats->filter(fn ($item) => strtolower($item->gender ?? '') === 'male')->sum('total');
                            $femaleCount = $employeeGenderStats->filter(fn ($item) => strtolower($item->gender ?? '') === 'female')->sum('total');
                            $totalG = $maleCount + $femaleCount;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-primary-500"></span>
                                <span class="text-sm text-surface-600">Male</span>
                                <span class="text-sm font-bold text-surface-800">{{ $maleCount }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="text-sm text-surface-600">Female</span>
                                <span class="text-sm font-bold text-surface-800">{{ $femaleCount }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex h-2 rounded-full overflow-hidden mt-2 bg-surface-200">
                        <div class="bg-primary-500 h-full transition-all" style="width: {{ $totalG > 0 ? ($maleCount / $totalG) * 100 : 0 }}%"></div>
                        <div class="bg-rose-400 h-full transition-all" style="width: {{ $totalG > 0 ? ($femaleCount / $totalG) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="bg-surface-50 rounded-xl p-4">
                    <p class="text-sm text-surface-500 mb-2 font-medium">Payroll Summary</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-600">Monthly</span>
                            <span class="font-bold text-surface-800">K{{ number_format($monthlyPayroll, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-600">Annual Budget</span>
                            <span class="font-bold text-surface-800">K{{ number_format($totalSalary, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-600">Active Warnings</span>
                            <span class="font-bold text-rose-600">{{ $activeWarnings }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('employees.create') }}" class="flex items-center justify-between bg-gradient-to-r from-primary-500 to-primary-700 text-white rounded-xl p-4 hover:from-primary-600 hover:to-primary-800 transition-all duration-200 shadow-lg shadow-primary-500/20">
                    <div>
                        <p class="font-semibold text-sm">Add New Employee</p>
                        <p class="text-primary-200 text-xs">Register a new team member</p>
                    </div>
                    <i class="fa-solid fa-plus text-white text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Leaves & Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Recent Leave Requests --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-surface-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-surface-900">Recent Leave Requests</h3>
                    <p class="text-sm text-surface-500">Latest pending and approved leaves</p>
                </div>
                <a href="{{ route('leaves.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-surface-500 uppercase tracking-wider">
                            <th class="pb-3 pr-4">Employee</th>
                            <th class="pb-3 pr-4">Type</th>
                            <th class="pb-3 pr-4">Duration</th>
                            <th class="pb-3 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($recentLeaves as $leave)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($leave->employee->full_name ?? 'N/A', 0, 2) }}
                                        </div>
                                        <span class="text-sm font-medium text-surface-700">{{ $leave->employee->full_name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="text-sm text-surface-600 capitalize">{{ $leave->leave_type }}</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="text-sm text-surface-600">
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4">
                                    @if($leave->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @elseif($leave->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-surface-400">
                                    <i class="fa-solid fa-calendar-xmark text-2xl mb-2"></i>
                                    <p class="text-sm">No leave requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6">
            <h3 class="text-lg font-bold text-surface-900 mb-6">Quick Actions</h3>
            <div class="space-y-3">
                @if(Auth::user()->hasPermission('manage-employees'))
                <a href="{{ route('employees.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-primary-50 border border-primary-100 hover:bg-primary-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-surface-800">Add Employee</p>
                        <p class="text-xs text-surface-500">Register a new team member</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-primary-300 ml-auto text-sm"></i>
                </a>
                @endif

                @if(Auth::user()->hasPermission('view-leaves'))
                <a href="{{ route('leaves.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-surface-800">Apply Leave</p>
                        <p class="text-xs text-surface-500">Submit a leave request</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-emerald-300 ml-auto text-sm"></i>
                </a>
                @endif

                @if(Auth::user()->hasPermission('manage-payroll'))
                <a href="{{ route('payrolls.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-amber-50 border border-amber-100 hover:bg-amber-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-surface-800">Generate Payroll</p>
                        <p class="text-xs text-surface-500">Process monthly payroll</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-amber-300 ml-auto text-sm"></i>
                </a>
                @endif

                @if(Auth::user()->hasPermission('manage-attendance'))
                <a href="{{ route('attendance.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-violet-50 border border-violet-100 hover:bg-violet-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-surface-800">Record Attendance</p>
                        <p class="text-xs text-surface-500">Log employee attendance</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-violet-300 ml-auto text-sm"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Bottom Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-5 flex items-center gap-4 animate-slide-up animate-delay-100">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-building text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900">{{ $totalDepartments }}</p>
                <p class="text-xs text-surface-500">Departments</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-5 flex items-center gap-4 animate-slide-up animate-delay-200">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-check-double text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900">{{ $todayAttendance }}</p>
                <p class="text-xs text-surface-500">Attendance Today</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-5 flex items-center gap-4 animate-slide-up animate-delay-300">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900">{{ $approvedLeaves }}</p>
                <p class="text-xs text-surface-500">Approved Leaves</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-5 flex items-center gap-4 animate-slide-up animate-delay-400">
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900">{{ $activeWarnings }}</p>
                <p class="text-xs text-surface-500">Active Warnings</p>
            </div>
        </div>
    </div>
</x-app-layout>
