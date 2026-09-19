<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $department = $request->get('department');

        if ($user->isDepartmentManager() && $department) {
            $query = Employee::where('department_name', $department);
        } else {
            $query = $user->accessibleEmployees();
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                   ->orWhereRaw("first_name || ' ' || COALESCE(middle_name || ' ', '') || last_name like ?", ["%{$search}%"]);
            });
        }

        if ($department) {
            $query->where('department_name', $department);
        }

        $employees = $query->with('company')->paginate(10)->appends(request()->query());

        $departmentFilter = $request->get('department');

        return view('employees.index', compact('employees', 'departmentFilter'));
    }

    public function show(Employee $employee)
    {
        $this->authorizeScopeOrView($employee);

        $employee->load(['company', 'leaveRequests']);

        return view('employees.show', compact('employee'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $companies = auth()->user()->accessibleCompanies();
        $departments = Department::orderBy('department_name')->get();

        return view('employees.create', compact('roles', 'companies', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|string|max:50',
            'department_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'annual_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'office_location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'login_enabled' => 'sometimes|boolean',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::requiredIf($request->boolean('login_enabled')),
                Rule::unique('users', 'email'),
            ],
            'role' => [
                'nullable',
                'string',
                Rule::requiredIf($request->boolean('login_enabled')),
                Rule::exists('roles', 'code'),
            ],
            'account_company_id' => 'nullable|exists:companies,id',
            'account_department' => 'nullable|string|max:255',
        ]);

        $employee = Employee::create([
            ...collect($validated)->except(['login_enabled', 'email', 'role', 'account_company_id', 'account_department'])->toArray(),
            'login_enabled' => $request->boolean('login_enabled'),
            'email' => $request->boolean('login_enabled') ? $validated['email'] : null,
        ]);

        \App\Models\AppNotification::createForRoles(
            ['hr'],
            'employee_added',
            'New employee added',
            "{$employee->full_name} was added to the system (" . ($employee->company->name ?? 'No company') . ').',
            route('employees.index')
        );

        if ($request->boolean('login_enabled')) {
            $this->createLoginAccount($employee, $validated);
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $this->authorizeScope($employee);

        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $companies = auth()->user()->accessibleCompanies();
        $departments = Department::orderBy('department_name')->get();

        return view('employees.edit', compact('employee', 'roles', 'companies', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorizeScope($employee);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|string|max:50',
            'department_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'annual_salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'office_location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'login_enabled' => 'sometimes|boolean',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->user_id),
            ],
            'role' => [
                'nullable',
                'string',
                Rule::exists('roles', 'code'),
            ],
            'account_company_id' => 'nullable|exists:companies,id',
            'account_department' => 'nullable|string|max:255',
        ]);

        $employee->update([
            ...collect($validated)->except(['login_enabled', 'email', 'role', 'account_company_id', 'account_department'])->toArray(),
            'login_enabled' => $request->boolean('login_enabled'),
        ]);

        if ($request->boolean('login_enabled')) {
            $this->updateLoginAccount($employee, $validated);
        } else {
            if ($employee->user) {
                $employee->user->roles()->detach();
                $employee->user->delete();
                $employee->update(['user_id' => null, 'email' => null]);
            }
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorizeScope($employee);

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    private function createLoginAccount(Employee $employee, array $validated): void
    {
        $user = User::create([
            'name' => trim("{$validated['first_name']} {$validated['last_name']}"),
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(16)),
            'department_name' => $validated['account_department'] ?? null,
        ]);
        $user->roles()->attach(Role::where('code', $validated['role'])->first());

        $this->assignAccountScope($user, $validated['role'], $validated);

        $employee->update(['user_id' => $user->id]);
    }

    private function updateLoginAccount(Employee $employee, array $validated): void
    {
        $user = $employee->user;
        if (!$user) {
            $user = User::create([
                'name' => trim("{$validated['first_name']} {$validated['last_name']}"),
                'email' => $validated['email'] ?? $employee->email,
                'password' => Hash::make(Str::random(16)),
                'department_name' => $validated['account_department'] ?? null,
            ]);
            $employee->update(['user_id' => $user->id, 'email' => $validated['email'] ?? $employee->email]);
        } else {
            if (!empty($validated['email']) && $validated['email'] !== $user->email) {
                $user->update(['email' => $validated['email'], 'name' => trim("{$validated['first_name']} {$validated['last_name']}")]);
            }
            $user->update(['department_name' => $validated['account_department'] ?? $user->department_name]);

            if (!empty($validated['role'])) {
                $user->roles()->sync([Role::where('code', $validated['role'])->first()->id]);
            }
        }

        $this->assignAccountScope($user, $validated['role'], $validated);

        if ($employee->email !== ($validated['email'] ?? $employee->email)) {
            $employee->update(['email' => $validated['email'] ?? $employee->email]);
        }
    }

    private function assignAccountScope(User $user, string $role, array $validated): void
    {
        if ($role === 'company-manager' && !empty($validated['account_company_id'])) {
            $user->companies()->sync([$validated['account_company_id']]);
        }

        if ($role === 'department-manager' && !empty($validated['account_department'])) {
            $user->update(['department_name' => $validated['account_department']]);
        }
    }

    private function authorizeScope(Employee $employee): void
    {
        $user = auth()->user();

        if ($user->isDepartmentManager()) {
            abort_unless($user->hasPermission('manage-employees'), 403, 'You do not have access to this employee.');
            return;
        }

        $accessible = $user->accessibleEmployees()->whereKey($employee->getKey())->exists();

        abort_unless($accessible, 403, 'You do not have access to this employee.');
    }

    private function authorizeScopeOrView(Employee $employee): void
    {
        $user = auth()->user();

        if ($user->isDepartmentManager() && $user->hasPermission('view-employees')) {
            return;
        }

        $this->authorizeScope($employee);
    }
}
