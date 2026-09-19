<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $companies = [
            ['name' => 'X-Finance', 'code' => 'CMP1'],
            ['name' => 'D8ta Limited', 'code' => 'CMP2'],
            ['name' => 'Earthwexs', 'code' => 'CMP3'],
            ['name' => 'B & L D8ta Retail', 'code' => 'CMP4'],
            ['name' => 'Wireless Technology', 'code' => 'CMP5'],
            ['name' => 'rent LTD', 'code' => 'CMP6'],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(['code' => $company['code']], $company);
        }

        $this->seedUser('System Admin', 'system.admin@d8ta.com.pg', 'admin');
        $this->seedUser('HR Manager', 'hr.manager@d8ta.com.pg', 'hr');
        $this->seedUser('Company Manager', 'company.manager@d8ta.com.pg', 'company-manager', 'CMP3');
        $this->seedUser('Department Manager', 'department.manager@d8ta.com.pg', 'department-manager');
        $this->seedUser('Employee', 'employee@d8ta.com.pg', 'employee');

        $this->seedUser('Supervisor', 'supervisor@d8ta.com.pg', 'department-manager', 'CMP1');
    }

    private function seedUser(string $name, string $email, string $roleCode, ?string $companyCode = null): void
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt('p@ssw0rd'),
            ]
        );

        $role = Role::where('code', $roleCode)->first();
        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        if ($companyCode) {
            $company = Company::where('code', $companyCode)->first();
            if ($company) {
                $user->companies()->syncWithoutDetaching([$company->id]);
            }
        }
    }
}
