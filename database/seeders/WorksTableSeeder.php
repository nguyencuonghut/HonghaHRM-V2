<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class WorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('works')->delete();

        DB::table('works')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'contract_code' => '312/2014/HH-HĐLĐ',
                    'employee_id' => 1,
                    'position_id' => 69,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2014-01-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'contract_code' => '312/2019/HH-HĐLĐ',
                    'employee_id' => 1,
                    'position_id' => 19,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2019-08-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'contract_code' => '912/2016/HH-HĐLĐ',
                    'employee_id' => 2,
                    'position_id' => 40,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2016-09-12',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'contract_code' => '1992/2020/HH-HĐLĐ',
                    'employee_id' => 3,
                    'position_id' => 41,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2020-02-01',
                    'end_date' => '2022-06-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'contract_code' => '1992/2022/HH-HĐLĐ',
                    'employee_id' => 3,
                    'position_id' => 39,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2022-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'contract_code' => '2142/2021/HH-HĐLĐ',
                    'employee_id' => 4,
                    'position_id' => 63,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2021-03-01',
                    'end_date' => '2024-04-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'contract_code' => '2142/2024/HH-HĐLĐ',
                    'employee_id' => 4,
                    'position_id' => 78,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2024-04-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'contract_code' => '2199/2022/HH-HĐLĐ',
                    'employee_id' => 5,
                    'position_id' => 63,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2022-05-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'contract_code' => '468/2015/HH-HĐLĐ',
                    'employee_id' => 6,
                    'position_id' => 79,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2015-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'contract_code' => '468/2019/HH-HĐLĐ',
                    'employee_id' => 6,
                    'position_id' => 20,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2019-07-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'contract_code' => '353/2012/HH-HĐLĐ',
                    'employee_id' => 7,
                    'position_id' => 66,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2012-08-01',
                    'end_date' => '2023-10-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'employee_id' => 7,
                    'contract_code' => '353/2023/HH-HĐLĐ',
                    'position_id' => 80,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2023-08-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'contract_code' => '2159/2023/HH-HĐLĐ',
                    'employee_id' => 8,
                    'position_id' => 66,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2023-09-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'contract_code' => '226/2011/HH-HĐLĐ',
                    'employee_id' => 9,
                    'position_id' => 66,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2011-10-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'contract_code' => '240/2011/HH-HĐLĐ',
                    'employee_id' => 10,
                    'position_id' => 66,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2011-11-01',
                    'end_date' => '2020-04-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'contract_code' => '240/2020/HH-HĐLĐ',
                    'employee_id' => 10,
                    'position_id' => 82,
                    'status' => 'Off',
                    'on_type_id' => 4,
                    'start_date' => '2020-04-01',
                    'end_date' => '2022-12-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'contract_code' => '240/2022/HH-HĐLĐ',
                    'employee_id' => 10,
                    'position_id' => 59,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2022-12-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'contract_code' => '240/2024/HH-HĐLĐ',
                    'employee_id' => 10,
                    'position_id' => 81,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2024-03-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'contract_code' => '233/2011/HH-HĐLĐ',
                    'employee_id' => 11,
                    'position_id' => 68,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2011-04-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'contract_code' => '275/2011/HH-HĐLĐ',
                    'employee_id' => 12,
                    'position_id' => 68,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2011-05-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'contract_code' => '248/2011/HH-HĐLĐ',
                    'employee_id' => 13,
                    'position_id' => 43,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2011-06-01',
                    'end_date' => '2020-04-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'contract_code' => '248/2019/HH-HĐLĐ',
                    'employee_id' => 13,
                    'position_id' => 84,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2020-04-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'contract_code' => '252/2011/HH-HĐLĐ',
                    'employee_id' => 14,
                    'position_id' => 43,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2011-05-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'contract_code' => '554/2013/HH-HĐLĐ',
                    'employee_id' => 15,
                    'position_id' => 68,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2013-06-01',
                    'end_date' => '2022-010-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'contract_code' => '554/2022/HH-HĐLĐ',
                    'employee_id' => 15,
                    'position_id' => 43,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2022-10-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'contract_code' => '868/2016/HH-HĐLĐ',
                    'employee_id' => 16,
                    'position_id' => 84,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2016-07-01',
                    'end_date' => '2022-02-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26 =>
                array (
                    'id' => 27,
                    'contract_code' => '868/2023/HH-HĐLĐ',
                    'employee_id' => 16,
                    'position_id' => 77,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2022-02-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            27 =>
                array (
                    'id' => 28,
                    'contract_code' => '557/2015/HH-HĐLĐ',
                    'employee_id' => 17,
                    'position_id' => 13,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2015-08-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            28 =>
                array (
                    'id' => 29,
                    'contract_code' => '001/2003/HH-HĐLĐ',
                    'employee_id' => 18,
                    'position_id' => 15,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2003-09-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            29 =>
                array (
                    'id' => 30,
                    'contract_code' => '598/2015/HH-HĐLĐ',
                    'employee_id' => 19,
                    'position_id' => 57,
                    'status' => 'Off',
                    'on_type_id' => 2,
                    'start_date' => '2015-10-01',
                    'end_date' => '2020-04-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            30 =>
                array (
                    'id' => 31,
                    'contract_code' => '598/2020/HH-HĐLĐ',
                    'employee_id' => 19,
                    'position_id' => 71,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2020-04-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            31 =>
                array (
                    'id' => 32,
                    'contract_code' => '1821/2015/HH-HĐLĐ',
                    'employee_id' => 20,
                    'position_id' => 72,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2015-11-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            32 =>
                array (
                    'id' => 33,
                    'contract_code' => '1631/2018/HH-HĐLĐ',
                    'employee_id' => 21,
                    'position_id' => 17,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2018-12-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            33 =>
                array (
                    'id' => 34,
                    'contract_code' => '090/2019/HH-HĐLĐ',
                    'employee_id' => 22,
                    'position_id' => 21,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2019-02-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            34 =>
                array (
                    'id' => 35,
                    'contract_code' => '326/2022/HH-HĐLĐ',
                    'employee_id' => 23,
                    'position_id' => 75,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2022-03-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            35 =>
                array (
                    'id' => 36,
                    'contract_code' => '326/2015/HH-HĐLĐ',
                    'employee_id' => 23,
                    'position_id' => 63,
                    'status' => 'Off',
                    'on_type_id' => 4,
                    'start_date' => '2015-04-01',
                    'end_date' => '2022-03-01',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            36 =>
                array (
                    'id' => 37,
                    'contract_code' => '594/2016/HH-HĐLĐ',
                    'employee_id' => 24,
                    'position_id' => 44,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2016-05-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            37 =>
                array (
                    'id' => 38,
                    'contract_code' => '353/2024/HH-HĐLĐ',
                    'employee_id' => 7,
                    'position_id' => 85,
                    'status' => 'On',
                    'on_type_id' => 4,
                    'start_date' => '2024-11-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            38 =>
                array (
                    'id' => 39,
                    'contract_code' => '481/2024/HH-HĐLĐ',
                    'employee_id' => 25,
                    'position_id' => 86,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2024-03-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            39 =>
                array (
                    'id' => 40,
                    'contract_code' => '444/2013/HH-HĐLĐ',
                    'employee_id' => 26,
                    'position_id' => 46,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2013-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            40 =>
                array (
                    'id' => 41,
                    'contract_code' => '170/2013/HH-HĐLĐ',
                    'employee_id' => 27,
                    'position_id' => 83,
                    'status' => 'On',
                    'on_type_id' => 2,
                    'start_date' => '2013-06-01',
                    'end_date' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
