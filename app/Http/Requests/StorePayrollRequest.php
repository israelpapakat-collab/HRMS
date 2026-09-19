<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'status' => 'nullable|string|in:draft,processed,paid',
        ];
    }
}
