<x-app-layout>
    <x-slot name="header">Users &amp; Access</x-slot>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between flex-wrap gap-2">
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 flex-wrap">
                <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}"
                       class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:border-blue-500">
                <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->code }}" {{ request('role') == $role->code ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="text-gray-900 hover:text-gray-700 text-sm">Clear</a>
                @endif
            </form>
            <div class="flex gap-2">
                <a href="{{ route('users.roles') }}" class="bg-gray-100 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-200">Roles &amp; Permissions</a>
                <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Add User</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Access Scope</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 mr-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @php
                                    $isCM = $user->roles->contains('code', 'company-manager');
                                    $isDM = $user->roles->contains('code', 'department-manager');
                                @endphp
                                @if($isCM)
                                    {{ $user->companies->pluck('name')->implode(', ') ?: '--' }}
                                @elseif($isDM)
                                    {{ $user->department_name ?: '--' }}
                                @else
                                    <span class="text-gray-400">All</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->employee->first()->full_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($user->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                @if(!$user->isSystemAdmin())
                                    <form method="POST" action="{{ route('users.toggleActive', $user) }}" class="inline" onsubmit="return confirm('Toggle this account status?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }} mr-3">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user account?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-900">No user accounts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
