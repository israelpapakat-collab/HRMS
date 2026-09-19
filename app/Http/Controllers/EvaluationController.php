<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');
        $evaluations = Evaluation::with(['employee', 'evaluator'])
            ->whereIn('employee_id', $employeeIds)
            ->latest()->paginate(15);

        return view('evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');
        $employees = Employee::whereIn('employee_id', $employeeIds)->orderBy('first_name')->get();

        return view('evaluations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'evaluation_date' => 'required|date',
            'rating' => 'nullable|numeric|min:0|max:10',
            'comments' => 'nullable|string',
            'goals' => 'nullable|string',
            'next_review_date' => 'nullable|date',
        ]);

        $accessible = auth()->user()->accessibleEmployees()
            ->whereKey($validated['employee_id'])
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this employee.');

        $validated['evaluator_id'] = auth()->id();
        $validated['status'] = 'completed';

        Evaluation::create($validated);

        return redirect()->route('evaluations.index')->with('success', 'Performance review created.');
    }

    public function show(Evaluation $evaluation)
    {
        $this->authorizeScope($evaluation);
        $evaluation->load(['employee', 'evaluator']);

        return view('evaluations.show', compact('evaluation'));
    }

    private function authorizeScope(Evaluation $evaluation): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($evaluation->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this evaluation.');
    }
}
