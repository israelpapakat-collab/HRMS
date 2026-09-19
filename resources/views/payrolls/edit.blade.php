<x-app-layout>
    <x-slot name="header">Edit Payroll</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('payrolls.update', $payroll) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee *</label>
                <select name="employee_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}" @selected(old('employee_id', $payroll->employee_id) == $emp->employee_id)>{{ $emp->full_name }}</option>
                    @endforeach
                </select>
                @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Before Tax Adds/Deds</label>
                    <input type="number" step="0.01" name="before_tax_add_ded" value="{{ old('before_tax_add_ded', $payroll->before_tax_add_ded) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">After Tax Adds/Deds</label>
                    <input type="number" step="0.01" name="after_tax_add_ded" value="{{ old('after_tax_add_ded', $payroll->after_tax_add_ded) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taxable</label>
                    <input type="number" step="0.01" name="taxable" value="{{ old('taxable', $payroll->taxable) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax</label>
                    <input type="number" step="0.01" name="tax" value="{{ old('tax', $payroll->tax) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Basic Salary *</label>
                    <input type="number" step="0.01" name="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gross Pay *</label>
                    <input type="number" step="0.01" name="gross_pay" value="{{ old('gross_pay', $payroll->gross_pay) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Deductions</label>
                    <input type="number" step="0.01" name="total_deductions" value="{{ old('total_deductions', $payroll->total_deductions) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Net Pay *</label>
                    <input type="number" step="0.01" name="net_pay" value="{{ old('net_pay', $payroll->net_pay) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="draft" @selected(old('status', $payroll->status) == 'draft')>Draft</option>
                        <option value="processed" @selected(old('status', $payroll->status) == 'processed')>Processed</option>
                        <option value="paid" @selected(old('status', $payroll->status) == 'paid')>Paid</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rate per Hour</label>
                        <input type="number" step="0.01" name="rate_per_hour" value="{{ old('rate_per_hour', $payroll->rate_per_hour) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dependents</label>
                        <input type="number" name="dependents" value="{{ old('dependents', $payroll->dependents) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('payrolls.index') }}" class="text-gray-900 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>