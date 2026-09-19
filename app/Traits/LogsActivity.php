<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created', $model->getLogDescription('created'));
        });

        static::updated(function ($model) {
            $changed = $model->getDirty();
            $original = array_intersect_key($model->getOriginal(), $changed);

            $model->logActivity('updated', $model->getLogDescription('updated', $changed), $original, $changed);
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getLogDescription('deleted'));
        });
    }

    public function logAction(string $action, string $description, ?array $old = null, ?array $new = null): void
    {
        $this->logActivity($action, $description, $old, $new);
    }

    protected function logActivity(string $action, string $description, ?array $old = null, ?array $new = null): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $this->getLogModule(),
            'description' => $description,
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
        ]);
    }

    protected function getLogModule(): string
    {
        return class_basename($this);
    }

    protected function getLogDescription(string $action, array $changed = []): string
    {
        $label = strtolower(class_basename($this));
        $name = method_exists($this, 'getLogName') ? $this->getLogName() : $this->getKey();

        if ($action === 'updated') {
            $fields = array_map(
                fn ($f) => str_replace('_', ' ', $f),
                array_keys($changed)
            );

            return ucfirst($label) . " {$name} changed: " . implode(', ', $fields);
        }

        return ucfirst($action) . ' ' . $label . ' ' . $name;
    }
}
