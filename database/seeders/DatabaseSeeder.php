<?php

namespace Database\Seeders;

use App\Models\Degree;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            DepartmentsTableSeeder::class,
            DivisionsTableSeeder::class,
            PositionsTableSeeder::class,
            UserDepartmentsTableSeeder::class,
            MethodsTableSeeder::class,
            ChannelsTableSeeder::class,
            ProvincesTableSeeder::class,
            DistrictsTableSeeder::class,
            CommunesTableSeeder::class,
            SchoolsTableSeeder::class,
            DegreesTableSeeder::class,
            ContractTypesTableSeeder::class,
            OnTypesTableSeeder::class,
            OffTypesTableSeeder::class,
            DocTypesTableSeeder::class,
            InsuranceTypesTableSeeder::class,
            RegimeTypesTableSeeder::class,
            WelfareTypesTableSeeder::class,
            EmployeesTableSeeder::class,
            EmployeeSchoolsTableSeeder::class,
            WorksTableSeeder::class,
            DepartmentManagersTableSeeder::class,
            DivisionManagersTableSeeder::class,
            DepartmentVicesTableSeeder::class,
        ]);
    }
}
