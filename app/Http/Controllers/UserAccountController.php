<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'employee', 'companies']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleCode = $request->get('role')) {
            $query->whereHas('roles', fn ($r) => $r->where('code', $roleCode));
        }

        $users = $query->orderBy('name')->paginate(15)->appends(request()->query());
        $roles = Role::where('is_active', true)->orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();

        return view('users.create', compact('roles', 'companies', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:100',
            'role' => 'required|exists:roles,code',
            'company_id' => 'nullable|exists:companies,id',
            'department_name' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department_name' => $validated['role'] === 'department-manager' ? $validated['department_name'] : null,
            'is_active' => true,
        ]);

        $user->roles()->sync([Role::where('code', $validated['role'])->first()->id]);

        if ($validated['role'] === 'company-manager' && !empty($validated['company_id'])) {
            $user->companies()->sync([$validated['company_id']]);
        }

        return redirect()->route('users.index')->with('success', "User account '{$user->name}' created successfully.");
    }

    public function edit(User $user, $tab = 'account')
    {
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();

        return view('users.edit', compact('user', 'roles', 'companies', 'departments', 'tab'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|exists:roles,code',
            'company_id' => 'nullable|exists:companies,id',
            'department_name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|max:100',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department_name' => $validated['role'] === 'department-manager' ? ($validated['department_name'] ?? null) : null,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->roles()->sync([Role::where('code', $validated['role'])->first()->id]);

        if ($validated['role'] === 'company-manager') {
            $user->companies()->sync(!empty($validated['company_id']) ? [$validated['company_id']] : []);
        } else {
            $user->companies()->detach();
        }

        return redirect()->route('users.edit', $user)->with('success', 'User account updated successfully.');
    }

    public function toggleActive(User $user)
    {
        if ($user->isSystemAdmin() && $user->is_active) {
            return back()->with('error', 'You cannot deactivate a System Administrator account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'User account activated.' : 'User account deactivated.');
    }

    public function destroy(User $user)
    {
        if ($user->isSystemAdmin()) {
            return back()->with('error', 'You cannot delete a System Administrator account.');
        }

        Employee::where('user_id', $user->id)->update(['user_id' => null, 'login_enabled' => false]);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User account deleted.');
    }

    public function roles()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('users.roles', compact('roles'));
    }
}
