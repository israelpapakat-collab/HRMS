<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use LogsActivity;

    protected $primaryKey = 'department_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'department_name',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'department_id', 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_name', 'department_name');
    }
}
