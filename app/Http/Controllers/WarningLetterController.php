<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WarningLetter;
use Illuminate\Http\Request;

class WarningLetterController extends Controller
{
    public function index()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');
        $warnings = WarningLetter::with(['employee', 'issuedBy'])
            ->whereIn('employee_id', $employeeIds)
            ->latest()->paginate(15);

        return view('warning-letters.index', compact('warnings'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('warning-letters.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'issue_date' => 'required|date',
            'type' => 'required|string|in:verbal,written,final',
            'description' => 'required|string',
            'action_taken' => 'nullable|string',
        ]);

        Employee::findOrFail($validated['employee_id']);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'active';

        WarningLetter::create($validated);

        return redirect()->route('warning-letters.index')->with('success', 'Warning letter issued.');
    }

    public function edit(WarningLetter $warningLetter)
    {
        $this->authorizeScope($warningLetter);
        $employees = Employee::orderBy('first_name')->get();

        return view('warning-letters.edit', compact('warningLetter', 'employees'));
    }

    public function update(Request $request, WarningLetter $warningLetter)
    {
        $this->authorizeScope($warningLetter);

        $validated = $request->validate([
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'issue_date' => 'sometimes|required|date',
            'type' => 'sometimes|required|string|in:verbal,written,final',
            'description' => 'sometimes|required|string',
            'action_taken' => 'nullable|string',
            'status' => 'sometimes|required|string|in:active,resolved',
        ]);

        Employee::findOrFail($validated['employee_id'] ?? $warningLetter->employee_id);

        $warningLetter->update($validated);

        return redirect()->route('warning-letters.index')->with('success', 'Warning letter updated.');
    }

    public function destroy(WarningLetter $warningLetter)
    {
        $this->authorizeScope($warningLetter);
        $warningLetter->delete();

        return redirect()->route('warning-letters.index')->with('success', 'Warning letter deleted.');
    }

    private function authorizeScope(WarningLetter $warningLetter): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($warningLetter->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this warning letter.');
    }
}
