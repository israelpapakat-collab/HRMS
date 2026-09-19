<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'employee_id' => $this->employee_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'marital_status' => $this->marital_status,
            'department_name' => $this->department_name,
            'position_title' => $this->position_title,
            'annual_salary' => $this->annual_salary,
            'hourly_rate' => $this->hourly_rate,
            'office_location' => $this->office_location,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'annual_leave_balance' => $this->annual_leave_balance,
            'sick_leave_balance' => $this->sick_leave_balance,
        ];
    }
}
