<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $code): bool
    {
        return $this->roles->contains('code', $code);
    }

    public static function rolePermissionsByCode(): array
    {
        return [
            'admin' => ['*'],
            'hr' => [
                'manage-employees', 'view-attendance', 'manage-attendance',
                'view-leaves', 'manage-leaves', 'approve-leave',
                'view-payroll', 'manage-payroll',
                'view-evaluations', 'manage-evaluations',
                'view-warnings', 'manage-warnings',
                'manage-companies', 'view-documents', 'manage-documents',
                'view-logs', 'view-reports', 'manage-users',
            ],
            'company-manager' => [
                'manage-employees', 'view-attendance', 'manage-attendance',
                'view-leaves', 'manage-leaves', 'approve-leave',
                'view-payroll', 'manage-payroll',
                'view-evaluations', 'manage-evaluations',
                'view-warnings', 'manage-warnings',
                'view-documents', 'manage-documents',
            ],
            'department-manager' => [
                'view-employees', 'manage-employees', 'view-attendance', 'manage-attendance',
                'view-leaves', 'approve-leave',
                'view-evaluations', 'manage-evaluations',
                'view-warnings', 'view-documents',
            ],
            'employee' => [
                'view-leaves', 'view-attendance', 'view-payroll',
            ],
        ];
    }

    public static function roleHasPermission(string $roleCode, string $permission): bool
    {
        if ($roleCode === 'admin') {
            return true;
        }

        return in_array($permission, self::rolePermissionsByCode()[$roleCode] ?? [], true);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        foreach ($this->roles as $role) {
            if (self::roleHasPermission($role->code, $permission)) {
                return true;
            }
        }

        return false;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function isSystemAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isHrManager(): bool
    {
        return $this->hasRole('hr');
    }

    public function isCompanyManager(): bool
    {
        return $this->hasRole('company-manager');
    }

    public function isDepartmentManager(): bool
    {
        return $this->hasRole('department-manager');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    /**
     * Company IDs a manager/HR/system admin can see data for.
     * Admin & HR see all companies. Company managers see their assigned companies.
     */
    public function accessibleCompanyIds(): array
    {
        if ($this->isSystemAdmin() || $this->isHrManager()) {
            return Company::query()->pluck('id')->all();
        }

        if ($this->isCompanyManager()) {
            return $this->companies()->pluck('companies.id')->all();
        }

        return [];
    }

    /**
     * Collection of companies this user may manage/see, for form dropdowns.
     */
    public function accessibleCompanies()
    {
        if ($this->isSystemAdmin() || $this->isHrManager()) {
            return Company::where('is_active', true)->orderBy('name')->get();
        }

        if ($this->isCompanyManager()) {
            return Company::whereIn('id', $this->accessibleCompanyIds())->where('is_active', true)->orderBy('name')->get();
        }

        if ($this->isDepartmentManager()) {
            return Company::where('is_active', true)->orderBy('name')->get();
        }

        return collect();
    }

    /**
     * Employee query scoped to what this user may view, based on role.
     */
    public function accessibleEmployees(): Builder
    {
        $emp = Employee::query();

        if ($this->isSystemAdmin() || $this->isHrManager()) {
            return $emp;
        }

        if ($this->isCompanyManager()) {
            return $emp->whereIn('company_id', $this->accessibleCompanyIds());
        }

        if ($this->isDepartmentManager()) {
            return $emp->where('department_name', $this->department_name);
        }

        if ($this->isEmployee()) {
            return $emp->where(function (Builder $q) {
                $q->where('user_id', $this->id);

                if (!empty($this->email)) {
                    $q->orWhereRaw('LOWER(email) = ?', [strtolower($this->email)]);
                }
            });
        }

        return $emp->whereRaw('1 = 0');
    }

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Link this user account to their employee record so they can view their
     * own payroll/attendance data. Attempts, in order of confidence:
     * 1) an existing user_id link, 2) a matching employee email, 3) a unique
     * first+last name match. Returns the linked employee, or null.
     */
    public function linkToEmployeeAccount(): ?Employee
    {
        if (!$this->hasRole('employee')) {
            return null;
        }

        if ($employee = Employee::where('user_id', $this->id)->first()) {
            return $employee;
        }

        if (!empty($this->email)) {
            $employee = Employee::whereNull('user_id')
                ->whereRaw('LOWER(email) = ?', [strtolower($this->email)])
                ->first();

            if ($employee) {
                $employee->update(['user_id' => $this->id]);
                return $employee;
            }
        }

        return $this->linkToEmployeeByName();
    }

    /**
     * Fall back to linking by name, but only when exactly one unassigned
     * employee matches, to avoid guessing between duplicate names.
     */
    private function linkToEmployeeByName(): ?Employee
    {
        $name = trim($this->name);
        if ($name === '') {
            return null;
        }

        $space = strrpos($name, ' ');
        $first = $space === false ? $name : substr($name, 0, $space);
        $last = $space === false ? '' : substr($name, $space + 1);

        $query = Employee::whereNull('user_id')
            ->whereRaw('LOWER(first_name) = ?', [strtolower($first)]);

        if ($last !== '') {
            $query->whereRaw('LOWER(last_name) = ?', [strtolower($last)]);
        }

        $matches = $query->get();

        if ($matches->count() === 1) {
            $employee = $matches->first();
            $employee->update(['user_id' => $this->id]);
            return $employee;
        }

        if ($matches->isEmpty()) {
            return null;
        }

        $withPayroll = Payroll::whereIn('employee_id', $matches->pluck('employee_id'))
            ->distinct()
            ->pluck('employee_id');

        if ($withPayroll->count() === 1) {
            $employee = $matches->firstWhere('employee_id', $withPayroll->first());
            if ($employee) {
                $employee->update(['user_id' => $this->id]);
                return $employee;
            }
        }

        return null;
    }

    public function processedPayrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'processed_by');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    public function issuedWarningLetters(): HasMany
    {
        return $this->hasMany(WarningLetter::class, 'issued_by');
    }

    protected function getLogName(): string
    {
        return $this->name ?: $this->email ?: $this->getKey();
    }
}
