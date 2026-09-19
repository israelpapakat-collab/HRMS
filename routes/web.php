<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\WarningLetterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/modules', [DashboardController::class, 'modules'])->name('modules');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('permission:view-employees,manage-employees');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:manage-employees');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:manage-employees');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show')->middleware('permission:view-employees,manage-employees');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:manage-employees');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:manage-employees');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:manage-employees');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:manage-settings');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:manage-settings');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:manage-settings');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:manage-settings');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:manage-settings');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:manage-settings');

    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index')->middleware('permission:manage-settings');
    Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create')->middleware('permission:manage-settings');
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store')->middleware('permission:manage-settings');
    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit')->middleware('permission:manage-settings');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update')->middleware('permission:manage-settings');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy')->middleware('permission:manage-settings');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index')->middleware('permission:manage-companies');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create')->middleware('permission:manage-companies');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store')->middleware('permission:manage-companies');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit')->middleware('permission:manage-companies');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update')->middleware('permission:manage-companies');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy')->middleware('permission:manage-companies');

    Route::get('/leaves', [LeaveRequestController::class, 'index'])->name('leaves.index')->middleware('permission:view-leaves,approve-leave');
    Route::get('/leaves/create', [LeaveRequestController::class, 'create'])->name('leaves.create')->middleware('permission:view-leaves');
    Route::post('/leaves', [LeaveRequestController::class, 'store'])->name('leaves.store')->middleware('permission:view-leaves');
    Route::post('/leaves/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leaves.approve')->middleware('permission:approve-leave');
    Route::post('/leaves/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leaves.reject')->middleware('permission:approve-leave');
    Route::delete('/leaves/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leaves.destroy')->middleware('permission:manage-leaves');

    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index')->middleware('permission:view-payroll,manage-payroll');
    Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create')->middleware('permission:manage-payroll');
    Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store')->middleware('permission:manage-payroll');
    Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show')->middleware('permission:view-payroll,manage-payroll');
    Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit')->middleware('permission:manage-payroll');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update')->middleware('permission:manage-payroll');
    Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy')->middleware('permission:manage-payroll');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
    Route::get('/reports/employees', [ReportController::class, 'employees'])->name('reports.employees')->middleware('permission:view-reports');
    Route::get('/reports/departments', [ReportController::class, 'departments'])->name('reports.departments')->middleware('permission:view-reports');
    Route::get('/reports/leaves', [ReportController::class, 'leaves'])->name('reports.leaves')->middleware('permission:view-reports');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index')->middleware('permission:view-logs');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index')->middleware('permission:view-attendance,manage-attendance');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create')->middleware('permission:view-attendance,manage-attendance');
    Route::get('/attendance/{attendanceRecord}', [AttendanceController::class, 'show'])->name('attendance.show')->middleware('permission:view-attendance,manage-attendance');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:view-attendance,manage-attendance');
    Route::get('/attendance/{attendanceRecord}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit')->middleware('permission:manage-attendance');
    Route::put('/attendance/{attendanceRecord}', [AttendanceController::class, 'update'])->name('attendance.update')->middleware('permission:manage-attendance');
    Route::delete('/attendance/{attendanceRecord}', [AttendanceController::class, 'destroy'])->name('attendance.destroy')->middleware('permission:manage-attendance');

    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index')->middleware('permission:view-evaluations,manage-evaluations');
    Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create')->middleware('permission:manage-evaluations');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store')->middleware('permission:manage-evaluations');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show')->middleware('permission:view-evaluations,manage-evaluations');

    Route::get('/warning-letters', [WarningLetterController::class, 'index'])->name('warning-letters.index')->middleware('permission:view-warnings,manage-warnings');
    Route::get('/warning-letters/create', [WarningLetterController::class, 'create'])->name('warning-letters.create')->middleware('permission:manage-warnings');
    Route::post('/warning-letters', [WarningLetterController::class, 'store'])->name('warning-letters.store')->middleware('permission:manage-warnings');
    Route::get('/warning-letters/{warningLetter}/edit', [WarningLetterController::class, 'edit'])->name('warning-letters.edit')->middleware('permission:manage-warnings');
    Route::put('/warning-letters/{warningLetter}', [WarningLetterController::class, 'update'])->name('warning-letters.update')->middleware('permission:manage-warnings');
    Route::delete('/warning-letters/{warningLetter}', [WarningLetterController::class, 'destroy'])->name('warning-letters.destroy')->middleware('permission:manage-warnings');

    Route::get('/documents', [EmployeeDocumentController::class, 'index'])->name('documents.index')->middleware('permission:view-documents,manage-documents');
    Route::get('/documents/create', [EmployeeDocumentController::class, 'create'])->name('documents.create')->middleware('permission:manage-documents');
    Route::post('/documents', [EmployeeDocumentController::class, 'store'])->name('documents.store')->middleware('permission:manage-documents');
    Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download')->middleware('permission:view-documents,manage-documents');
    Route::delete('/documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('documents.destroy')->middleware('permission:manage-documents');

    Route::get('/users', [UserAccountController::class, 'index'])->name('users.index')->middleware('permission:manage-users');
    Route::get('/users/create', [UserAccountController::class, 'create'])->name('users.create')->middleware('permission:manage-users');
    Route::post('/users', [UserAccountController::class, 'store'])->name('users.store')->middleware('permission:manage-users');
    Route::get('/users/{user}/edit', [UserAccountController::class, 'edit'])->name('users.edit')->middleware('permission:manage-users');
    Route::put('/users/{user}', [UserAccountController::class, 'update'])->name('users.update')->middleware('permission:manage-users');
    Route::patch('/users/{user}/toggle-active', [UserAccountController::class, 'toggleActive'])->name('users.toggleActive')->middleware('permission:manage-users');
    Route::delete('/users/{user}', [UserAccountController::class, 'destroy'])->name('users.destroy')->middleware('permission:manage-users');
    Route::get('/roles', [UserAccountController::class, 'roles'])->name('users.roles')->middleware('permission:manage-users');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:manage-settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:manage-settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
