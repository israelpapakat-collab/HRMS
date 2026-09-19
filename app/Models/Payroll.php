<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use LogsActivity;
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'gross_pay',
        'rate_per_hour',
        'dependents',
        'before_tax_add_ded',
        'taxable',
        'tax',
        'after_tax_add_ded',
        'total_deductions',
        'net_pay',
        'status',
        'processed_by',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'gross_pay' => 'decimal:2',
            'before_tax_add_ded' => 'decimal:2',
            'taxable' => 'decimal:2',
            'tax' => 'decimal:2',
            'after_tax_add_ded' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_pay' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function payrollDeductions(): HasMany
    {
        return $this->hasMany(PayrollDeduction::class);
    }

    protected function getLogName(): string
    {
        $employee = $this->employee;
        if ($employee) {
            return $employee->full_name ?: $employee->getKey();
        }

        return $this->employee_id ?: $this->getKey();
    }

    protected function getLogDescription(string $action, array $changed = []): string
    {
        $name = $this->getLogName();

        if ($action === 'created') {
            $net = isset($this->net_pay) ? number_format((float) $this->net_pay, 2) : null;

            return "Created payslip for {$name}" . ($net !== null ? " (net: {$net})" : '');
        }

        if ($action === 'deleted') {
            return "Deleted payslip for {$name}";
        }

        $parts = [];
        if (array_key_exists('net_pay', $changed)) {
            $parts[] = 'net pay to ' . number_format((float) $changed['net_pay'], 2);
        }
        if (array_key_exists('status', $changed)) {
            $parts[] = "status to {$changed['status']}";
        }
        if (!$parts) {
            $parts[] = implode(', ', array_map(fn ($f) => str_replace('_', ' ', $f), array_keys($changed)));
        }

        return "Updated payslip for {$name}: " . implode(', ', $parts);
    }
}
