<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use LogsActivity;

    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'evaluation_date',
        'rating',
        'comments',
        'goals',
        'next_review_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'rating' => 'decimal:1',
            'next_review_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
