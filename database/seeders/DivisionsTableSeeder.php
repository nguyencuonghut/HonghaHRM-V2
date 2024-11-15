<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->delete();

        DB::table('divisions')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Tổ bốc xếp',
                    'department_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Tổ Mixer',
                    'department_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Bộ phận Admin',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Ban bảo vệ',
                    'department_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Phòng phân tích',
                    'department_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Kỹ thuật thị trường',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'KD gia súc gia cầm',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'name' => 'Kỹ thuật trại',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'name' => 'KD thủy sản',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'name' => 'Thuốc thú y',
                    'department_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'name' => 'SX gia súc gia cầm',
                    'department_id' => 6,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'name' => 'SX thủy sản',
                    'department_id' => 6,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'name' => 'Tổ bán hàng',
                    'department_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'name' => 'Bộ phận cân',
                    'department_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'name' => 'Tổ tạp vụ',
                    'department_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'name' => 'Tổ nấu ăn',
                    'department_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'name' => 'Tổ nguyên liệu',
                    'department_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'name' => 'Tổ trồng rau',
                    'department_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'name' => 'Tổ KCS nguyên liệu',
                    'department_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'name' => 'Tổ KCS thành phẩm thủy sản',
                    'department_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'name' => 'Tổ KCS thành phẩm gia súc',
                    'department_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'name' => 'Ban tài chính',
                    'department_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'name' => 'Bộ phận IT',
                    'department_id' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'name' => 'Tổ kiểm soát',
                    'department_id' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
