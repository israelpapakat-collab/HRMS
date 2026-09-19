<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'basic_salary' => $this->basic_salary,
            'gross_pay' => $this->gross_pay,
            'rate_per_hour' => $this->rate_per_hour,
            'dependents' => $this->dependents,
            'before_tax_add_ded' => $this->before_tax_add_ded,
            'taxable' => $this->taxable,
            'tax' => $this->tax,
            'after_tax_add_ded' => $this->after_tax_add_ded,
            'total_deductions' => $this->total_deductions,
            'net_pay' => $this->net_pay,
            'status' => $this->status,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
        ];
    }
}
