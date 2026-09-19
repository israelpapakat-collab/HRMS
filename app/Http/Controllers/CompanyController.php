<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::query()
            ->addSelect([
                'employees_count' => \App\Models\Employee::query()
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('employees.company_id', 'companies.id')
                    ->orWhereRaw('LOWER(employees.department_name) = LOWER(companies.name)'),
            ])
            ->paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:30|unique:companies,code',
            'description' => 'nullable|string',
        ]);

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:30', Rule::unique('companies', 'code')->ignore($company->id)],
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $company->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
