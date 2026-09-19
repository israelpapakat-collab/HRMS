<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');

        $query = LeaveRequest::with('employee')
            ->whereIn('employee_id', $employeeIds);

        if ($filter = $request->get('filter')) {
            $query->where('status', $filter);
        }

        $leaves = $query->latest()->paginate(10);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'leave_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'purpose' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        $validated['status'] = 'pending';

        $leave = LeaveRequest::create($validated);

        \App\Models\AppNotification::createForRoles(
            ['hr', 'company-manager'],
            'leave_request',
            'New leave request',
            "{$employee->full_name} submitted a {$validated['leave_type']} leave request starting " . \Illuminate\Support\Carbon::parse($validated['start_date'])->format('d M') . '.',
            route('leaves.index', ['filter' => 'pending'])
        );

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $this->authorizeScope($leaveRequest);
        $leaveRequest->update(['status' => 'approved']);

        $employee = $leaveRequest->employee;
        if ($employee && $employee->user_id) {
            \App\Models\AppNotification::createForUser(
                $employee->user_id,
                'leave_approved',
                'Leave approved',
                "Your {$leaveRequest->leave_type} leave request was approved.",
                route('leaves.index')
            );
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $this->authorizeScope($leaveRequest);
        $leaveRequest->update(['status' => 'rejected']);

        $employee = $leaveRequest->employee;
        if ($employee && $employee->user_id) {
            \App\Models\AppNotification::createForUser(
                $employee->user_id,
                'leave_rejected',
                'Leave rejected',
                "Your {$leaveRequest->leave_type} leave request was rejected.",
                route('leaves.index')
            );
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request rejected.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $this->authorizeScope($leaveRequest);
        $leaveRequest->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave request deleted.');
    }

    private function authorizeScope(LeaveRequest $leaveRequest): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($leaveRequest->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this leave request.');
    }
}
