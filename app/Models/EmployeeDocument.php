<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use LogsActivity;

    protected $fillable = [
        'employee_id',
        'name',
        'description',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected function getLogName(): string
    {
        return $this->name ?: $this->getKey();
    }

    protected function getLogDescription(string $action, array $changed = []): string
    {
        if ($action === 'created') {
            return "Uploaded document {$this->name}";
        }

        if ($action === 'deleted') {
            return "Deleted document {$this->name}";
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
