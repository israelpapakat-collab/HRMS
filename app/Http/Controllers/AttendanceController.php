<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');
        $records = AttendanceRecord::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->latest()->paginate(15);

        return view('attendance.index', compact('records'));
    }

    public function show(AttendanceRecord $attendanceRecord)
    {
        $this->authorizeScope($attendanceRecord);

        return view('attendance.show', [
            'attendanceRecord' => $attendanceRecord->load('employee'),
        ]);
    }

    public function create()
    {
        $employees = Employee::query();

        if (auth()->user()->isEmployee()) {
            $employees->whereIn('user_id', [auth()->id()]);
        }

        $employees = $employees->orderBy('first_name')->get();

        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string',
        ]);

        if (auth()->user()->isEmployee()) {
            $employee = Employee::where('user_id', auth()->id())->firstOrFail();
            $validated['employee_id'] = $employee->employee_id;
        } else {
            Employee::findOrFail($validated['employee_id']);
        }

        if ($validated['clock_in'] && $validated['clock_out']) {
            $in = \Carbon\Carbon::parse($validated['clock_in']);
            $out = \Carbon\Carbon::parse($validated['clock_out']);
            $validated['total_hours'] = round($out->diffInMinutes($in) / 60, 2);
        }

        AttendanceRecord::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $validated['date'],
            ],
            $validated
        );

        return redirect()->route('attendance.index')->with('success', 'Attendance record saved.');
    }

    public function edit(AttendanceRecord $attendanceRecord)
    {
        $this->authorizeScope($attendanceRecord);
        $employees = auth()->user()->accessibleEmployees()->orderBy('first_name')->get();

        return view('attendance.edit', compact('attendanceRecord', 'employees'));
    }

    public function update(Request $request, AttendanceRecord $attendanceRecord)
    {
        $this->authorizeScope($attendanceRecord);

        $validated = $request->validate([
            'employee_id' => 'sometimes|required|exists:employees,employee_id',
            'date' => 'sometimes|required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'sometimes|required|string|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->accessibleEmployees()->findOrFail($validated['employee_id'] ?? $attendanceRecord->employee_id);

        if (isset($validated['clock_in']) && isset($validated['clock_out'])) {
            $in = \Carbon\Carbon::parse($validated['clock_in']);
            $out = \Carbon\Carbon::parse($validated['clock_out']);
            $validated['total_hours'] = $out->diffInMinutes($in) / 60;
        }

        $attendanceRecord->update($validated);

        return redirect()->route('attendance.index')->with('success', 'Attendance record updated.');
    }

    public function destroy(AttendanceRecord $attendanceRecord)
    {
        $this->authorizeScope($attendanceRecord);
        $attendanceRecord->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted.');
    }

    private function authorizeScope(AttendanceRecord $record): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($record->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this attendance record.');
    }
}
