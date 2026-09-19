<x-app-layout>
    <x-slot name="header">Payroll History</x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Payroll Records</h3>
            @if(Auth::user()->hasPermission('manage-payroll'))
            <a href="{{ route('payrolls.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Generate Payroll</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Gross Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Taxable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Tax</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Net Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $payroll->employee->full_name ?? '--' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">K{{ number_format($payroll->gross_pay, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">K{{ number_format($payroll->taxable, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">K{{ number_format($payroll->tax, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">K{{ number_format($payroll->total_deductions, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">K{{ number_format($payroll->net_pay, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $payroll->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payroll->status == 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $payroll->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('payrolls.show', $payroll) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                                @if(Auth::user()->hasPermission('manage-payroll'))
                                <a href="{{ route('payrolls.edit', $payroll) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form method="POST" action="{{ route('payrolls.destroy', $payroll) }}" class="inline" onsubmit="return confirm('Delete this payroll record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-900">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $payrolls->links() }}
        </div>
    </div>
</x-app-layout>
