<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['code' => 'admin'], [
            'name' => 'System Administrator',
            'description' => 'Technical and system administration only',
        ]);
        Role::updateOrCreate(['code' => 'hr'], [
            'name' => 'HR Manager',
            'description' => 'Manages employees and HR data across all companies',
        ]);
        Role::updateOrCreate(['code' => 'company-manager'], [
            'name' => 'Company Manager',
            'description' => 'Manages their assigned company',
        ]);
        Role::updateOrCreate(['code' => 'department-manager'], [
            'name' => 'Department Manager',
            'description' => 'Manages their department/team',
        ]);
        Role::updateOrCreate(['code' => 'employee'], [
            'name' => 'Employee',
            'description' => 'Access to their own information',
        ]);
    }
}
