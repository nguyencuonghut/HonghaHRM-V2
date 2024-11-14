<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('department_managers')->delete();

        DB::table('department_managers')->insert(array (
            0 => array (
                'id' => 1,
                'department_id' => 1,
                'manager_id' => 18,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'department_id' => 2,
                'manager_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'department_id' => 3,
                'manager_id' => 19,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'department_id' => 4,
                'manager_id' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'department_id' => 5,
                'manager_id' => 21,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            5 => array (
                'id' => 6,
                'department_id' => 6,
                'manager_id' => 22,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            6 => array (
                'id' => 7,
                'department_id' => 7,
                'manager_id' => 23,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            7 => array (
                'id' => 8,
                'department_id' => 11,
                'manager_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            8 => array (
                'id' => 9,
                'department_id' => 15,
                'manager_id' => 16,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
