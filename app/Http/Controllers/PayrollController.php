<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');
        $payrolls = Payroll::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->latest()->paginate(10);

        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = auth()->user()->accessibleEmployees()->orderBy('first_name')->get();

        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'basic_salary' => 'required|numeric|min:0',
            'gross_pay' => 'required|numeric|min:0',
            'rate_per_hour' => 'nullable|numeric|min:0',
            'dependents' => 'nullable|integer|min:0',
            'taxable' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_deductions' => 'nullable|numeric|min:0',
            'net_pay' => 'required|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        $this->authorizeEmployee($validated['employee_id']);

        $account = $employee->ensureUserAccount();

        $validated['before_tax_add_ded'] ??= 0;
        $validated['rate_per_hour'] ??= 0;
        $validated['dependents'] ??= 0;
        $validated['taxable'] ??= 0;
        $validated['tax'] ??= 0;
        $validated['after_tax_add_ded'] ??= 0;
        $validated['total_deductions'] ??= 0;
        $validated['status'] = 'processed';
        $validated['processed_by'] = auth()->id();
        $validated['processed_at'] = now();

        Payroll::create($validated);

        $message = 'Payroll generated successfully.';
        if ($account && $account->wasRecentlyCreated) {
            $message .= " A login was created for {$employee->full_name} ({$account->email}) — password: password — so they can view this on their dashboard.";
        }

        return redirect()->route('payrolls.index')->with('success', $message);
    }

    public function show(Payroll $payroll)
    {
        $this->authorizeScope($payroll);
        $payroll->load('employee');

        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $this->authorizeScope($payroll);
        $employees = auth()->user()->accessibleEmployees()->orderBy('first_name')->get();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $this->authorizeScope($payroll);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'basic_salary' => 'required|numeric|min:0',
            'gross_pay' => 'required|numeric|min:0',
            'rate_per_hour' => 'nullable|numeric|min:0',
            'dependents' => 'nullable|integer|min:0',
            'before_tax_add_ded' => 'nullable|numeric',
            'taxable' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'after_tax_add_ded' => 'nullable|numeric',
            'total_deductions' => 'nullable|numeric|min:0',
            'net_pay' => 'required|numeric|min:0',
            'status' => 'required|in:draft,processed,paid',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        $this->authorizeEmployee($validated['employee_id']);

        $account = $employee->ensureUserAccount();

        $validated['before_tax_add_ded'] ??= 0;
        $validated['rate_per_hour'] ??= 0;
        $validated['dependents'] ??= 0;
        $validated['taxable'] ??= 0;
        $validated['tax'] ??= 0;
        $validated['after_tax_add_ded'] ??= 0;
        $validated['total_deductions'] ??= 0;

        $payroll->update($validated);

        $message = 'Payroll updated successfully.';
        if ($account && $account->wasRecentlyCreated) {
            $message .= " A login was created for {$employee->full_name} ({$account->email}) — password: password — so they can view this on their dashboard.";
        }

        return redirect()->route('payrolls.index')->with('success', $message);
    }

    public function destroy(Payroll $payroll)
    {
        $this->authorizeScope($payroll);
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    private function authorizeScope(Payroll $payroll): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($payroll->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this payroll record.');
    }

    private function authorizeEmployee(int $employeeId): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($employeeId)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this employee.');
    }
}
