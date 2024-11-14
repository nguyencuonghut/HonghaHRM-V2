<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employee_schools')->delete();

        DB::table('employee_schools')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'degree_id' => 1,
                    'school_id' => 1,
                    'employee_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'degree_id' => 1,
                    'school_id' => 2,
                    'employee_id' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'degree_id' => 1,
                    'school_id' => 3,
                    'employee_id' => 3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'degree_id' => 1,
                    'school_id' => 4,
                    'employee_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'degree_id' => 1,
                    'school_id' => 5,
                    'employee_id' => 5,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'degree_id' => 1,
                    'school_id' => 6,
                    'employee_id' => 6,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'degree_id' => 1,
                    'school_id' => 6,
                    'employee_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'degree_id' => 2,
                    'school_id' => 7,
                    'employee_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'degree_id' => 2,
                    'school_id' => 2,
                    'employee_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'degree_id' => 1,
                    'school_id' => 2,
                    'employee_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'degree_id' => 2,
                    'school_id' => 6,
                    'employee_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'degree_id' => 2,
                    'school_id' => 3,
                    'employee_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'degree_id' => 1,
                    'school_id' => 3,
                    'employee_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'degree_id' => 2,
                    'school_id' => 3,
                    'employee_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'degree_id' => 2,
                    'school_id' => 4,
                    'employee_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'degree_id' => 1,
                    'school_id' => 2,
                    'employee_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'degree_id' => 1,
                    'school_id' => 2,
                    'employee_id' => 17,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'degree_id' => 1,
                    'school_id' => 3,
                    'employee_id' => 18,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'degree_id' => 1,
                    'school_id' => 4,
                    'employee_id' => 19,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'degree_id' => 1,
                    'school_id' => 6,
                    'employee_id' => 20,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'degree_id' => 2,
                    'school_id' => 8,
                    'employee_id' => 21,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'degree_id' => 1,
                    'school_id' => 3,
                    'employee_id' => 22,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'degree_id' => 1,
                    'school_id' => 5,
                    'employee_id' => 23,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'degree_id' => 1,
                    'school_id' => 7,
                    'employee_id' => 24,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'degree_id' => 1,
                    'school_id' => 4,
                    'employee_id' => 25,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'degree_id' => 8,
                    'school_id' => 4,
                    'employee_id' => 26,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26 =>
                array (
                    'id' => 27,
                    'degree_id' => 8,
                    'school_id' => 3,
                    'employee_id' => 27,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
