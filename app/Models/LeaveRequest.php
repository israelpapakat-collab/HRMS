<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use LogsActivity;
    protected $primaryKey = 'leave_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'purpose',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    protected function getLogName(): string
    {
        $employee = $this->employee;
        if ($employee) {
            return trim($employee->full_name . " ({$this->leave_type})");
        }

        return $this->getKey();
    }

    protected function getLogDescription(string $action, array $changed = []): string
    {
        if ($action === 'updated' && isset($changed['status']) && in_array($changed['status'], ['approved', 'rejected'])) {
            $verb = $changed['status'] === 'approved' ? 'Approved' : 'Rejected';

            return "{$verb} leave for {$this->getLogName()}";
        }

        if ($action === 'created') {
            return "Created leave request for {$this->getLogName()}";
        }

        $label = strtolower(class_basename($this));
        $name = $this->getLogName();

        if ($action === 'updated') {
            $fields = array_map(fn ($f) => str_replace('_', ' ', $f), array_keys($changed));

            return ucfirst($label) . " {$name} changed: " . implode(', ', $fields);
        }

        return ucfirst($action) . ' ' . $label . ' ' . $name;
    }
}
