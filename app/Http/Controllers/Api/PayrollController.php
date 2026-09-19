<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index()
    {
        $user = request()->user();

        if ($user->hasRole('employee')) {
            $employeeIds = $user->employee->pluck('employee_id');
            $payrolls = Payroll::with('employee')->whereIn('employee_id', $employeeIds)->get();
        } else {
            $payrolls = Payroll::with('employee')->get();
        }

        return PayrollResource::collection($payrolls);
    }

    public function store(StorePayrollRequest $request)
    {
        $payroll = Payroll::create($request->validated());

        return new PayrollResource($payroll->load('employee'));
    }
}
