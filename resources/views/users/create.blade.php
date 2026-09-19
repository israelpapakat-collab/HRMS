<x-app-layout>
    <x-slot name="header">Add User Account</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                    <select name="role" id="role-select" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Select role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->code }}" {{ old('role') == $role->code ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="company-field" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Company</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Select company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="department-field" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Department</label>
                <select name="department_name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->department_name }}" {{ old('department_name') == $department->department_name ? 'selected' : '' }}>{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Create User</button>
                <a href="{{ route('users.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role-select');
        const companyField = document.getElementById('company-field');
        const departmentField = document.getElementById('department-field');

        function toggleScope() {
            const role = roleSelect.value;
            companyField.classList.toggle('hidden', role !== 'company-manager');
            departmentField.classList.toggle('hidden', role !== 'department-manager');
        }
        roleSelect.addEventListener('change', toggleScope);
        toggleScope();
    </script>
</x-app-layout>
