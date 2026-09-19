<x-app-layout>
    <x-slot name="header">Payslip</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8 border-t-4 border-blue-600">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">PAYSLIP</h2>
            </div>

            <div class="border-t border-b border-gray-200 py-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-900">Employee</p>
                        <p class="font-semibold">{{ $payroll->employee->full_name ?? '--' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">Status</p>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $payroll->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $payroll->status == 'processed' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ ucfirst($payroll->status) }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm text-gray-900">Rate per Hour</p>
                        <p class="font-semibold">K{{ number_format($payroll->rate_per_hour, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">Dependents</p>
                        <p class="font-semibold">{{ $payroll->dependents }}</p>
                    </div>
                </div>
            </div>

            <table class="w-full mb-4">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-sm font-medium text-gray-900">Description</th>
                        <th class="text-right py-2 text-sm font-medium text-gray-900">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-700">Basic Salary</td>
                        <td class="py-3 text-right text-gray-700">K{{ number_format($payroll->basic_salary, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-700">Gross Pay</td>
                        <td class="py-3 text-right text-gray-700">K{{ number_format($payroll->gross_pay, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-700">Before Tax Adds/Deds</td>
                        <td class="py-3 text-right {{ $payroll->before_tax_add_ded < 0 ? 'text-red-600' : ($payroll->before_tax_add_ded > 0 ? 'text-green-600' : 'text-gray-700') }}">
                            @if($payroll->before_tax_add_ded < 0)- @endif K{{ number_format(abs($payroll->before_tax_add_ded), 2) }}
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-700">Taxable</td>
                        <td class="py-3 text-right text-gray-700">K{{ number_format($payroll->taxable, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-red-600">Tax</td>
                        <td class="py-3 text-right text-red-600">- K{{ number_format($payroll->tax, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-700">After Tax Adds/Deds</td>
                        <td class="py-3 text-right {{ $payroll->after_tax_add_ded < 0 ? 'text-red-600' : ($payroll->after_tax_add_ded > 0 ? 'text-green-600' : 'text-gray-700') }}">
                            @if($payroll->after_tax_add_ded < 0)- @endif K{{ number_format(abs($payroll->after_tax_add_ded), 2) }}
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-red-600">Total Deductions</td>
                        <td class="py-3 text-right text-red-600">- K{{ number_format($payroll->total_deductions, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300">
                        <td class="py-3 font-bold text-gray-800">Net Pay</td>
                        <td class="py-3 text-right font-bold text-lg text-gray-800">K{{ number_format($payroll->net_pay, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="flex justify-center">
                <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Print Payslip</button>
            </div>
        </div>
    </div>
</x-app-layout>
