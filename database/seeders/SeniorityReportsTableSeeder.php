<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeniorityReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seniority_reports')->delete();

        DB::table('seniority_reports')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'employee_id' => 1,
                    'contract_id' => 1,
                    'formal_contract_start_date' => '2014-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'employee_id' => 2,
                    'contract_id' => 3,
                    'formal_contract_start_date' => '2016-09-12',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'employee_id' => 3,
                    'contract_id' => 4,
                    'formal_contract_start_date' => '2020-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'employee_id' => 4,
                    'contract_id' => 6,
                    'formal_contract_start_date' => '2021-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'employee_id' => 5,
                    'contract_id' => 8,
                    'formal_contract_start_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'employee_id' => 6,
                    'contract_id' => 9,
                    'formal_contract_start_date' => '2015-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'employee_id' => 7,
                    'contract_id' => 11,
                    'formal_contract_start_date' => '2012-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'employee_id' => 8,
                    'contract_id' => 13,
                    'formal_contract_start_date' => '2023-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'employee_id' => 9,
                    'contract_id' => 14,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'employee_id' => 10,
                    'contract_id' => 15,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'employee_id' => 11,
                    'contract_id' => 19,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'employee_id' => 12,
                    'contract_id' => 20,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'employee_id' => 13,
                    'contract_id' => 21,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'employee_id' => 14,
                    'contract_id' => 23,
                    'formal_contract_start_date' => '2011-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'employee_id' => 15,
                    'contract_id' => 24,
                    'formal_contract_start_date' => '2013-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'employee_id' => 16,
                    'contract_id' => 26,
                    'formal_contract_start_date' => '2016-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'employee_id' => 17,
                    'contract_id' => 28,
                    'formal_contract_start_date' => '2015-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'employee_id' => 18,
                    'contract_id' => 29,
                    'formal_contract_start_date' => '2003-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'employee_id' => 19,
                    'contract_id' => 30,
                    'formal_contract_start_date' => '2015-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'employee_id' => 20,
                    'contract_id' => 32,
                    'formal_contract_start_date' => '2015-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'employee_id' => 21,
                    'contract_id' => 33,
                    'formal_contract_start_date' => '2018-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'employee_id' => 22,
                    'contract_id' => 34,
                    'formal_contract_start_date' => '2019-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'employee_id' => 23,
                    'contract_id' => 35,
                    'formal_contract_start_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'employee_id' => 24,
                    'contract_id' => 37,
                    'formal_contract_start_date' => '2016-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'employee_id' => 25,
                    'contract_id' => 39,
                    'formal_contract_start_date' => '2024-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'employee_id' => 26,
                    'contract_id' => 40,
                    'formal_contract_start_date' => '2013-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26 =>
                array (
                    'id' => 27,
                    'employee_id' => 27,
                    'contract_id' => 41,
                    'formal_contract_start_date' => '2013-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            27 =>
                array (
                    'id' => 28,
                    'employee_id' => 28,
                    'contract_id' => 42,
                    'formal_contract_start_date' => '2011-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            28 =>
                array (
                    'id' => 29,
                    'employee_id' => 29,
                    'contract_id' => 43,
                    'formal_contract_start_date' => '2015-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            29 =>
                array (
                    'id' => 30,
                    'employee_id' => 30,
                    'contract_id' => 44,
                    'formal_contract_start_date' => '2010-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            30 =>
                array (
                    'id' => 31,
                    'employee_id' => 31,
                    'contract_id' => 45,
                    'formal_contract_start_date' => '2010-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            31 =>
                array (
                    'id' => 32,
                    'employee_id' => 32,
                    'contract_id' => 46,
                    'formal_contract_start_date' => '2010-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            32 =>
                array (
                    'id' => 33,
                    'employee_id' => 33,
                    'contract_id' => 47,
                    'formal_contract_start_date' => '2015-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            33 =>
                array (
                    'id' => 34,
                    'employee_id' => 34,
                    'contract_id' => 48,
                    'formal_contract_start_date' => '2022-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
