<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('contracts')->delete();

        DB::table('contracts')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'employee_id' => 1,
                    'position_id' => 69,
                    'code' => '312/01/2014/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2014-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'employee_id' => 1,
                    'position_id' => 19,
                    'code' => '312/01/2019/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2019-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'employee_id' => 2,
                    'position_id' => 40,
                    'code' => '912/09/2016/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2016-09-12',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'employee_id' => 3,
                    'position_id' => 41,
                    'code' => '1992/01/2020/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2020-01-01',
                    'end_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'employee_id' => 3,
                    'position_id' => 39,
                    'code' => '1992/01/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2022-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'employee_id' => 4,
                    'position_id' => 63,
                    'code' => '2142/01/2021/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2021-01-01',
                    'end_date' => '2024-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'employee_id' => 4,
                    'position_id' => 78,
                    'code' => '2142/01/2024/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'employee_id' => 5,
                    'position_id' => 63,
                    'code' => '2199/01/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2022-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'employee_id' => 6,
                    'position_id' => 79,
                    'code' => '468/01/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2015-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'employee_id' => 6,
                    'position_id' => 20,
                    'code' => '468/01/2019/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2019-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'employee_id' => 7,
                    'position_id' => 66,
                    'code' => '353/01/2012/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2012-01-01',
                    'end_date' => '2023-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'employee_id' => 7,
                    'position_id' => 80,
                    'code' => '353/01/2023/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2023-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'employee_id' => 8,
                    'position_id' => 66,
                    'code' => '2159/01/2023/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2023-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'employee_id' => 9,
                    'position_id' => 66,
                    'code' => '226/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2011-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'employee_id' => 10,
                    'position_id' => 66,
                    'code' => '240/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2011-01-01',
                    'end_date' => '2020-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'employee_id' => 10,
                    'position_id' => 82,
                    'code' => '240/01/2020/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2020-01-01',
                    'end_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'employee_id' => 10,
                    'position_id' => 59,
                    'code' => '240/01/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2022-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'employee_id' => 10,
                    'position_id' => 81,
                    'code' => '240/01/2024/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'employee_id' => 11,
                    'position_id' => 68,
                    'code' => '233/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2011-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'employee_id' => 12,
                    'position_id' => 68,
                    'code' => '275/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2011-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'employee_id' => 13,
                    'position_id' => 43,
                    'code' => '248/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2011-01-01',
                    'end_date' => '2020-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'employee_id' => 13,
                    'position_id' => 84,
                    'code' => '248/01/2019/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2019-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'employee_id' => 14,
                    'position_id' => 43,
                    'code' => '252/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2011-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'employee_id' => 15,
                    'position_id' => 68,
                    'code' => '554/01/2013/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2013-01-01',
                    'end_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'employee_id' => 15,
                    'position_id' => 43,
                    'code' => '554/01/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2022-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'employee_id' => 16,
                    'position_id' => 84,
                    'code' => '868/01/2016/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2016-01-01',
                    'end_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26 =>
                array (
                    'id' => 27,
                    'employee_id' => 16,
                    'position_id' => 77,
                    'code' => '868/01/2023/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2023-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            27 =>
                array (
                    'id' => 28,
                    'employee_id' => 17,
                    'position_id' => 13,
                    'code' => '557/01/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2015-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            28 =>
                array (
                    'id' => 29,
                    'employee_id' => 18,
                    'position_id' => 15,
                    'code' => '001/01/2003/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2003-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            29 =>
                array (
                    'id' => 30,
                    'employee_id' => 19,
                    'position_id' => 57,
                    'code' => '598/01/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2015-01-01',
                    'end_date' => '2020-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            30 =>
                array (
                    'id' => 31,
                    'employee_id' => 19,
                    'position_id' => 71,
                    'code' => '598/01/2020/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2020-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            31 =>
                array (
                    'id' => 32,
                    'employee_id' => 20,
                    'position_id' => 72,
                    'code' => '1821/01/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2015-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            32 =>
                array (
                    'id' => 33,
                    'employee_id' => 21,
                    'position_id' => 17,
                    'code' => '1631/01/2018/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2018-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            33 =>
                array (
                    'id' => 34,
                    'employee_id' => 22,
                    'position_id' => 21,
                    'code' => '090/01/2019/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2019-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            34 =>
                array (
                    'id' => 35,
                    'employee_id' => 23,
                    'position_id' => 75,
                    'code' => '326/01/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2022-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            35 =>
                array (
                    'id' => 36,
                    'employee_id' => 23,
                    'position_id' => 63,
                    'code' => '326/01/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'Off',
                    'start_date' => '2015-01-01',
                    'end_date' => '2022-01-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            36 =>
                array (
                    'id' => 37,
                    'employee_id' => 24,
                    'position_id' => 44,
                    'code' => '594/01/2016/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2016-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            37 =>
                array (
                    'id' => 38,
                    'employee_id' => 7,
                    'position_id' => 85,
                    'code' => '353/01/2024/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            38 =>
                array (
                    'id' => 39,
                    'employee_id' => 25,
                    'position_id' => 86,
                    'code' => '481/01/2024/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            39 =>
                array (
                    'id' => 40,
                    'employee_id' => 26,
                    'position_id' => 46,
                    'code' => '444/01/2013/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2013-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            40 =>
                array (
                    'id' => 41,
                    'employee_id' => 27,
                    'position_id' => 83,
                    'code' => '170/01/2013/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2013-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            41 =>
                array (
                    'id' => 42,
                    'employee_id' => 28,
                    'position_id' => 27,
                    'code' => '166/01/2011/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2011-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            42 =>
                array (
                    'id' => 43,
                    'employee_id' => 29,
                    'position_id' => 26,
                    'code' => '846/06/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2015-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            43 =>
                array (
                    'id' => 44,
                    'employee_id' => 30,
                    'position_id' => 34,
                    'code' => '279/06/2010/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2010-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            44 =>
                array (
                    'id' => 45,
                    'employee_id' => 31,
                    'position_id' => 34,
                    'code' => '273/06/2010/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2010-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            45 =>
                array (
                    'id' => 46,
                    'employee_id' => 32,
                    'position_id' => 87,
                    'code' => '163/06/2010/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2010-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            46 =>
                array (
                    'id' => 47,
                    'employee_id' => 33,
                    'position_id' => 60,
                    'code' => '998/06/2015/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2015-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            47 =>
                array (
                    'id' => 48,
                    'employee_id' => 34,
                    'position_id' => 45,
                    'code' => '2173/06/2022/HH-HĐLĐ',
                    'contract_type_id' => 2,
                    'file_path' => 'dist/employee_contract/sample_contract.pdf',
                    'status' => 'On',
                    'start_date' => '2022-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
