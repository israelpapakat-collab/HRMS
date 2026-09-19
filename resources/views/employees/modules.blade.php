<x-app-layout>
    <x-slot name="header">Modules</x-slot>

    {{-- Welcome Banner --}}
    <div class="mb-8 bg-gradient-to-r from-primary-600 via-primary-700 to-surface-900 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl shadow-primary-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-white/5 rounded-full translate-y-1/2"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h2>
            <p class="text-primary-200 text-sm max-w-xl">Choose a module below. Modules marked with a lock (🔒) are restricted and require additional permissions.</p>
        </div>
    </div>

    {{-- My Payslips --}}
    @if(Auth::user()->isEmployee() && Auth::user()->hasPermission('view-payroll'))
    <div class="bg-white rounded-2xl shadow-sm border border-surface-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-surface-900">My Payslips</h3>
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
                    @forelse(($recentPayrolls ?? collect()) as $payroll)
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

    {{-- Module Grid --}}
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
</x-app-layout>
