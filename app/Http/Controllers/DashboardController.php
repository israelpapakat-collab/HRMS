<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\AttendanceRecord;
use App\Models\WarningLetter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');

        $totalEmployees = count($employeeIds);
        $totalDepartments = Department::count();
        $pendingLeaves = LeaveRequest::whereIn('employee_id', $employeeIds)->where('status', 'pending')->count();
        $monthlyPayroll = Payroll::whereIn('employee_id', $employeeIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('net_pay');

        $employeesByDepartment = Department::withCount('employees')
            ->orderBy('employees_count', 'desc')
            ->get();

        $recentLeaves = LeaveRequest::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayrolls = Payroll::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->latest()
            ->take(5)
            ->get();

        $todayAttendance = AttendanceRecord::whereIn('employee_id', $employeeIds)->whereDate('date', today())->count();
        $presentToday = AttendanceRecord::whereIn('employee_id', $employeeIds)->whereDate('date', today())
            ->where('status', 'present')
            ->count();

        $approvedLeaves = LeaveRequest::whereIn('employee_id', $employeeIds)->where('status', 'approved')->count();
        $rejectedLeaves = LeaveRequest::whereIn('employee_id', $employeeIds)->where('status', 'rejected')->count();
        $totalPayroll = Payroll::whereIn('employee_id', $employeeIds)->sum('net_pay');

        $activeWarnings = WarningLetter::whereIn('employee_id', $employeeIds)->count();

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $month = "strftime('%m', created_at)";
            $year = "strftime('%Y', created_at)";
        } else {
            $month = 'MONTH(created_at)';
            $year = 'YEAR(created_at)';
        }

        $monthlyPayrollTrend = Payroll::whereIn('employee_id', $employeeIds)
            ->select(
                DB::raw("$month as month"),
                DB::raw("$year as year"),
                DB::raw('SUM(net_pay) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $employeeGenderStats = Employee::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();

        $leaveTypeBreakdown = LeaveRequest::whereIn('employee_id', $employeeIds)
            ->select('leave_type', DB::raw('count(*) as total'))
            ->where('status', 'approved')
            ->groupBy('leave_type')
            ->get();

        $totalSalary = Employee::whereIn('employee_id', $employeeIds)->sum('annual_salary');

        $modules = $this->buildModules();

        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'pendingLeaves',
            'monthlyPayroll',
            'employeesByDepartment',
            'recentLeaves',
            'recentPayrolls',
            'todayAttendance',
            'presentToday',
            'approvedLeaves',
            'rejectedLeaves',
            'totalPayroll',
            'activeWarnings',
            'monthlyPayrollTrend',
            'employeeGenderStats',
            'leaveTypeBreakdown',
            'totalSalary',
            'modules'
        ));
    }

    /**
     * Render the module cards hub (linked from the "Employees" nav item).
     */
    public function modules()
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');

        $recentPayrolls = Payroll::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->latest()
            ->take(5)
            ->get();

        return view('employees.modules', [
            'modules' => $this->buildModules(),
            'recentPayrolls' => $recentPayrolls,
        ]);
    }

    /**
     * Build the list of modules shown in the hub. Each module carries an
     * `accessible` flag derived from the user's backend permissions. Locked
     * modules are rendered with a 🔒 / ✕ indicator and are NOT linked, so they
     * can never be opened without the matching permission (the target routes
     * are also guarded by the `permission` middleware).
     */
    private function buildModules(): array
    {
        $user = auth()->user();
        $can = fn (string $permission) => $user->hasPermission($permission);
        $any = fn (string ...$perms) => collect($perms)->contains(fn ($p) => $user->hasPermission($p));

        return [
            [
                'name' => 'My Information',
                'icon' => 'fa-solid fa-circle-user',
                'route' => route('profile.edit'),
                'accessible' => true,
                'description' => 'View and update your own profile details.',
            ],
            [
                'name' => 'Attendance',
                'icon' => 'fa-solid fa-clock',
                'route' => $any('view-attendance', 'manage-attendance') ? route('attendance.index') : null,
                'accessible' => $any('view-attendance', 'manage-attendance'),
                'description' => 'Record and review attendance.',
            ],
            [
                'name' => 'Leave',
                'icon' => 'fa-solid fa-calendar-days',
                'route' => $any('view-leaves', 'approve-leave') ? route('leaves.index') : null,
                'accessible' => $any('view-leaves', 'approve-leave'),
                'description' => 'Request and manage leave.',
            ],
            [
                'name' => 'Payroll',
                'icon' => 'fa-solid fa-coins',
                'route' => $any('view-payroll', 'manage-payroll') ? route('payrolls.index') : null,
                'accessible' => $any('view-payroll', 'manage-payroll'),
                'description' => 'View payslips and payroll records.',
            ],
            [
                'name' => 'Employee Records',
                'icon' => 'fa-solid fa-user-tie',
                'route' => $any('view-employees', 'manage-employees') ? route('employees.index') : null,
                'accessible' => $any('view-employees', 'manage-employees'),
                'description' => 'Manage employee records.',
            ],
            [
                'name' => 'Approve Leave',
                'icon' => 'fa-solid fa-check-double',
                'route' => $can('approve-leave') ? route('leaves.index', ['filter' => 'pending']) : null,
                'accessible' => $can('approve-leave'),
                'description' => 'Approve or reject pending leave.',
            ],
            [
                'name' => 'Performance',
                'icon' => 'fa-solid fa-star',
                'route' => $any('view-evaluations', 'manage-evaluations') ? route('evaluations.index') : null,
                'accessible' => $any('view-evaluations', 'manage-evaluations'),
                'description' => 'View evaluations and performance reviews.',
            ],
            [
                'name' => 'Documents',
                'icon' => 'fa-solid fa-folder-open',
                'route' => $any('view-documents', 'manage-documents') ? route('documents.index') : null,
                'accessible' => $any('view-documents', 'manage-documents'),
                'description' => 'Access and manage documents.',
            ],
            [
                'name' => 'Warnings',
                'icon' => 'fa-solid fa-triangle-exclamation',
                'route' => $any('view-warnings', 'manage-warnings') ? route('warning-letters.index') : null,
                'accessible' => $any('view-warnings', 'manage-warnings'),
                'description' => 'Review warning letters.',
            ],
            [
                'name' => 'Companies',
                'icon' => 'fa-solid fa-city',
                'route' => $can('manage-companies') ? route('companies.index') : null,
                'accessible' => $can('manage-companies'),
                'description' => 'Manage company information.',
            ],
            [
                'name' => 'Reports',
                'icon' => 'fa-solid fa-chart-simple',
                'route' => $can('view-reports') ? route('reports.index') : null,
                'accessible' => $can('view-reports'),
                'description' => 'Generate and view reports.',
            ],
            [
                'name' => 'Audit Logs',
                'icon' => 'fa-solid fa-clock-rotate-left',
                'route' => $can('view-logs') ? route('logs.index') : null,
                'accessible' => $can('view-logs'),
                'description' => 'Review system activity logs.',
            ],
        ];
    }
}
