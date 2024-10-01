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
            0 => array (
                'id' => 1,
                'department_id' => 2,
                'name' => 'Bộ phận IT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'department_id' => 2,
                'name' => 'Nhóm Kiểm Soát',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'department_id' => 3,
                'name' => 'Bộ phận Hành Chính',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'department_id' => 3,
                'name' => 'Bộ phận Nhân Sự',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'department_id' => 3,
                'name' => 'Bộ phận Nhà Bếp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            5 => array (
                'id' => 6,
                'department_id' => 3,
                'name' => 'Bộ phận Tạp Vụ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            6 => array (
                'id' => 7,
                'department_id' => 3,
                'name' => 'Tổ Trồng Rau',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            7 => array (
                'id' => 8,
                'department_id' => 3,
                'name' => 'Tổ Lái Xe',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            8 => array (
                'id' => 9,
                'department_id' => 4,
                'name' => 'Ban Tài Chính',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            9 => array (
                'id' => 10,
                'department_id' => 4,
                'name' => 'Bộ phận Cân',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            10 => array (
                'id' => 11,
                'department_id' => 4,
                'name' => 'Tổ bán hàng',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            11 => array (
                'id' => 12,
                'department_id' => 5,
                'name' => 'Bộ phận Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            12 => array (
                'id' => 13,
                'department_id' => 5,
                'name' => 'Bộ phận KTTT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            13 => array (
                'id' => 14,
                'department_id' => 5,
                'name' => 'Bộ phận KTT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            14 => array (
                'id' => 15,
                'department_id' => 5,
                'name' => 'Bộ phận KD GSGC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            15 => array (
                'id' => 16,
                'department_id' => 5,
                'name' => 'Bộ phận KD Thủy Sản',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            16 => array (
                'id' => 17,
                'department_id' => 5,
                'name' => 'Bộ phận KD Thuốc Thú Y',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            17 => array (
                'id' => 18,
                'department_id' => 6,
                'name' => 'Tổ Trộn Mix',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            18 => array (
                'id' => 19,
                'department_id' => 6,
                'name' => 'Tổ Bốc Xếp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            19 => array (
                'id' => 20,
                'department_id' => 8,
                'name' => 'Bộ phận SX GSGC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            20 => array (
                'id' => 21,
                'department_id' => 8,
                'name' => 'Bộ phận SX Thủy Sản',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            21 => array (
                'id' => 22,
                'department_id' => 11,
                'name' => 'Phòng Lab',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            22 => array (
                'id' => 23,
                'department_id' => 11,
                'name' => 'Tổ KCS Thành Phẩm GSGC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            23 => array (
                'id' => 24,
                'department_id' => 11,
                'name' => 'Tổ KCS Thành Thủy Sản',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            24 => array (
                'id' => 25,
                'department_id' => 11,
                'name' => 'Tổ KCS Nguyên Liệu',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
