<x-app-layout>
    <x-slot name="header">Roles &amp; Permissions</x-slot>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-900">Role-based access control across all companies.</p>
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Back to users</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($roles as $role)
                <div class="border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                        <span class="text-xs text-gray-500">{{ $role->users_count }} user(s)</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">{{ $role->description }}</p>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Access</p>
                        <div class="flex flex-wrap gap-1.5">
                            @php
                                $perms = [
                                    'Employee records' => \App\Models\User::roleHasPermission($role->code, 'manage-employees') || \App\Models\User::roleHasPermission($role->code, 'view-employees'),
                                    'Attendance' => \App\Models\User::roleHasPermission($role->code, 'view-attendance'),
                                    'Leave' => \App\Models\User::roleHasPermission($role->code, 'view-leaves'),
                                    'Approve leave' => \App\Models\User::roleHasPermission($role->code, 'approve-leave'),
                                    'Payroll' => \App\Models\User::roleHasPermission($role->code, 'view-payroll'),
                                    'Performance' => \App\Models\User::roleHasPermission($role->code, 'view-evaluations'),
                                    'Documents' => \App\Models\User::roleHasPermission($role->code, 'view-documents'),
                                    'Warnings' => \App\Models\User::roleHasPermission($role->code, 'view-warnings'),
                                    'Companies' => \App\Models\User::roleHasPermission($role->code, 'manage-companies'),
                                    'Reports' => \App\Models\User::roleHasPermission($role->code, 'view-reports'),
                                    'Audit Logs' => \App\Models\User::roleHasPermission($role->code, 'view-logs'),
                                ];
                            @endphp
                            @foreach($perms as $label => $allowed)
                                <span class="px-2 py-1 text-xs rounded-full {{ $allowed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="fa-solid {{ $allowed ? 'fa-lock-open' : 'fa-lock' }} mr-1 {{ $allowed ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
