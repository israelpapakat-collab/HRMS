<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function employees(Request $request)
    {
        $employees = Employee::all();

        if ($request->query('export') === 'csv') {
            return $this->exportCsv('employees.csv', [
                'ID', 'First Name', 'Middle Name', 'Last Name', 'Gender',
                'Department', 'Position', 'Annual Salary', 'Start Date',
            ], $employees->map(fn($e) => [
                $e->employee_id, $e->first_name, $e->middle_name, $e->last_name, $e->gender,
                $e->department_name ?? '', $e->position_title ?? '',
                $e->annual_salary, $e->start_date?->format('Y-m-d'),
            ])->toArray());
        }

        if ($request->query('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf-employees', compact('employees'));
            return $pdf->download('employees-report.pdf');
        }

        return view('reports.employees', compact('employees'));
    }

    public function departments(Request $request)
    {
        $departments = Department::withCount('employees')->get();

        if ($request->query('export') === 'csv') {
            return $this->exportCsv('departments.csv', [
                'Department', 'Total Employees',
            ], $departments->map(fn($d) => [
                $d->department_name, $d->employees_count,
            ])->toArray());
        }

        if ($request->query('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf-departments', compact('departments'));
            return $pdf->download('departments-report.pdf');
        }

        return view('reports.departments', compact('departments'));
    }

    public function leaves(Request $request)
    {
        $leaves = LeaveRequest::with('employee')
            ->where('status', 'approved')
            ->latest()
            ->get();

        if ($request->query('export') === 'csv') {
            return $this->exportCsv('approved-leaves.csv', [
                'ID', 'Employee', 'Leave Type', 'Start Date', 'End Date', 'Purpose', 'Status',
            ], $leaves->map(fn($l) => [
                $l->leave_id, $l->employee->full_name ?? '', $l->leave_type,
                $l->start_date?->format('Y-m-d'), $l->end_date?->format('Y-m-d'),
                $l->purpose, $l->status,
            ])->toArray());
        }

        if ($request->query('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf-leaves', compact('leaves'));
            return $pdf->download('approved-leaves-report.pdf');
        }

        return view('reports.leaves', compact('leaves'));
    }

    private function exportCsv(string $filename, array $headers, array $rows)
    {
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
