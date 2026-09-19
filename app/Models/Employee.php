<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use LogsActivity;
    protected $primaryKey = 'employee_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'company_id',
        'login_enabled',
        'email',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'marital_status',
        'department_name',
        'position_title',
        'annual_salary',
        'hourly_rate',
        'office_location',
        'start_date',
        'annual_leave_balance',
        'sick_leave_balance',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'start_date' => 'date',
            'annual_salary' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'annual_leave_balance' => 'integer',
            'sick_leave_balance' => 'integer',
            'login_enabled' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyNameAttribute(): ?string
    {
        if ($this->relationLoaded('company') && $this->company) {
            return $this->company->name;
        }

        if ($this->company_id) {
            return $this->company->name ?? null;
        }

        $department = trim((string) $this->department_name);
        if ($department === '') {
            return null;
        }

        static $companiesByName = null;
        if ($companiesByName === null) {
            $companiesByName = Company::query()->get()
                ->mapWithKeys(fn (Company $c) => [strtolower(trim($c->name)) => $c->name]);
        }

        return $companiesByName[strtolower($department)] ?? $department;
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'employee_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_id', 'employee_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'employee_id', 'employee_id');
    }

    public function warningLetters(): HasMany
    {
        return $this->hasMany(WarningLetter::class, 'employee_id', 'employee_id');
    }

    public function getFullNameAttribute(): string
    {
        return preg_replace('/\s+/', ' ', trim("{$this->first_name} {$this->middle_name} {$this->last_name}"));
    }

    /**
     * Ensure this employee has a login account, creating and linking one if
     * needed, so their payroll records display on their dashboard once HR
     * processes them. Tries, in order: 1) an existing user_id link, 2) a
     * matching employee email, 3) a unique/plausible name match, and if none
     * exist, creates a fresh employee-role account with a generated email and
     * the default password. Returns the linked user, or null.
     */
    public function ensureUserAccount(?string $defaultPassword = null): ?User
    {
        $defaultPassword ??= 'p@ssw0rd';

        if ($this->user_id && ($user = $this->user) && $user->hasRole('employee')) {
            $this->update(['login_enabled' => true]);
            return $user;
        }

        if ($user = $this->linkToUserAccount()) {
            $this->update(['login_enabled' => true]);
            return $user;
        }

        $user = User::create([
            'name' => $this->full_name,
            'email' => $this->uniqueAccountEmail(),
            'password' => $defaultPassword,
            'is_active' => true,
        ]);
        $user->roles()->attach(Role::where('code', 'employee')->firstOrFail());

        $this->update([
            'user_id' => $user->id,
            'email' => $this->email ?: $user->email,
            'login_enabled' => true,
        ]);

        return $user;
    }

    public function uniqueAccountEmail(): string
    {
        $base = strtolower(trim((string) $this->email));

        if ($base === '' || str_ends_with($base, '@dblsite.local')) {
            $slug = preg_replace('/[^a-z0-9]+/', '.', strtolower(trim("{$this->first_name} {$this->last_name}")));
            $slug = trim($slug, '.');
            $base = ($slug !== '' ? $slug : 'employee') . '@gmail.com';
        }

        $email = $base;
        $i = 2;

        while (User::whereRaw('LOWER(email) = ?', [strtolower($email)])->exists()) {
            [$prefix, $suffix] = array_pad(explode('@', $base, 2), 2, '');
            $email = $prefix . $i . ($suffix !== '' ? '@' . $suffix : '');
            $i++;
        }

        return $email;
    }

    /**
     * Link this employee to an existing employee-role user account so the
     * employee can view payroll records once HR processes them. Tries, in
     * order of confidence: 1) an existing user_id link, 2) a matching email,
     * 3) a unique name match. Returns the linked user, or null.
     */
    public function linkToUserAccount(): ?User
    {
        if ($this->user_id) {
            return $this->user;
        }

        if (!empty($this->email)) {
            $user = User::whereRaw('LOWER(email) = ?', [strtolower($this->email)])->first();

            if ($user && $user->hasRole('employee')) {
                $this->update(['user_id' => $user->id]);
                return $user;
            }
        }

        $name = strtolower(trim("{$this->first_name} {$this->last_name}"));
        if ($name === '') {
            return null;
        }

        $matches = User::whereHas('roles', fn ($q) => $q->where('code', 'employee'))
            ->whereRaw('LOWER(name) = ?', [$name])
            ->get();

        if ($matches->count() === 1) {
            $user = $matches->first();
            $this->update(['user_id' => $user->id]);
            return $user;
        }

        return null;
    }

    protected function getLogName(): string
    {
        return $this->full_name ?: $this->getKey();
    }

    protected function getLogDescription(string $action, array $changed = []): string
    {
        $name = $this->getLogName();

        if ($action === 'created') {
            return "Added employee {$name}";
        }

        if ($action === 'deleted') {
            return "Deleted employee {$name}";
        }

        $parts = [];

        if (array_key_exists('annual_salary', $changed)) {
            $newSalary = number_format((float) $changed['annual_salary'], 2);
            $oldSalary = $this->getOriginal('annual_salary') !== null
                ? number_format((float) $this->getOriginal('annual_salary'), 2)
                : null;
            $parts[] = "salary " . ($oldSalary !== null ? "from {$oldSalary} to {$newSalary}" : "to {$newSalary}");
        }

        if (array_key_exists('login_enabled', $changed)) {
            $parts[] = $changed['login_enabled'] ? 'enabled login access' : 'deactivated login access';
        }

        if (array_key_exists('department_name', $changed)) {
            $parts[] = "department to {$changed['department_name']}";
        }

        if (array_key_exists('position_title', $changed)) {
            $parts[] = "position to {$changed['position_title']}";
        }

        if (!$parts) {
            $parts[] = implode(', ', array_map(fn ($f) => str_replace('_', ' ', $f), array_keys($changed)));
        }

        return "Updated employee {$name}: " . implode(', ', $parts);
    }
}
