<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use LogsActivity;

    protected $primaryKey = 'position_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'position_title',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'position_title', 'position_title');
    }
}
