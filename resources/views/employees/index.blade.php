<x-app-layout>
    <x-slot name="header">Employees</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('employees.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Search employees..." value="{{ request('search') }}"
                           class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:border-blue-500">
                    @if(request('department'))
                        <input type="hidden" name="department" value="{{ request('department') }}">
                    @endif
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                    @if(request('search'))
                        <a href="{{ route('employees.index', request('department') ? ['department' => request('department')] : []) }}" class="text-gray-900 hover:text-gray-700 text-sm">Clear</a>
                    @endif
                </form>
                @if(request('department'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-emerald-50 text-emerald-700 rounded-lg">
                        <i class="fa-solid fa-building"></i>
                        {{ request('department') }} — {{ $employees->total() }} worker{{ $employees->total() === 1 ? '' : 's' }}
                        <a href="{{ route('employees.index', request('search') ? ['search' => request('search')] : []) }}" class="text-emerald-500 hover:text-emerald-700 ml-1" title="Clear department filter">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if(Auth::user()->hasPermission('manage-payroll'))
                <a href="{{ route('payrolls.create') }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">+ Generate Payroll</a>
                @endif
                @if(Auth::user()->hasPermission('manage-employees'))
                <a href="{{ route('employees.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Add Employee</a>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $employee->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->company_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->department_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->position_title ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    @if(Auth::user()->hasPermission('view-employees'))
                                    <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 hover:bg-sky-100 transition-colors" title="View">
                                        <i class="fa-solid fa-eye text-xs"></i><span class="text-xs font-medium">View</span>
                                    </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('manage-employees'))
                                    <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i><span class="text-xs font-medium">Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Delete {{ $employee->full_name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i><span class="text-xs font-medium">Delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-900">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>
