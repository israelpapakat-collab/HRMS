<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|string|max:50',
            'department_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'annual_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'office_location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'annual_leave_balance' => 'nullable|integer|min:0',
            'sick_leave_balance' => 'nullable|integer|min:0',
        ];
    }
}
