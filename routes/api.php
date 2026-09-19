<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\PayrollController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store'])->middleware('permission:manage-employees');
    Route::get('/departments/{department}', [DepartmentController::class, 'show']);
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->middleware('permission:manage-employees');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:manage-employees');

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store'])->middleware('permission:manage-employees');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->middleware('permission:manage-employees');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->middleware('permission:manage-employees');

    Route::get('/leaves', [LeaveRequestController::class, 'index']);
    Route::post('/leaves', [LeaveRequestController::class, 'store']);
    Route::get('/leaves/{leaveRequest}', [LeaveRequestController::class, 'show']);
    Route::put('/leaves/{leaveRequest}', [LeaveRequestController::class, 'update'])->middleware('permission:approve-leave');
    Route::delete('/leaves/{leaveRequest}', [LeaveRequestController::class, 'destroy']);

    Route::get('/payrolls', [PayrollController::class, 'index'])->middleware('permission:view-payroll');
    Route::post('/payrolls', [PayrollController::class, 'store'])->middleware('permission:view-payroll');
});
