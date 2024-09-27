<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->delete();

        DB::table('departments')->insert(array (
            0 => array (
                'id' => 1,
                'name' => 'Ban lãnh đạo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'name' => 'Phòng Kiểm Soát Nội Bộ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'name' => 'Phòng Hành Chính Nhân Sự',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'name' => 'Phòng Kế Toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'name' => 'Phòng Kinh Doanh',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            5 => array (
                'id' => 6,
                'name' => 'Phòng Kho',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            6 => array (
                'id' => 7,
                'name' => 'Phòng Bảo Trì',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            7 => array (
                'id' => 8,
                'name' => 'Phòng Sản Xuất',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            8 => array (
                'id' => 9,
                'name' => 'Ban Bảo Vệ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            9 => array (
                'id' => 10,
                'name' => 'Phòng Trại',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            10 => array (
                'id' => 11,
                'name' => 'Phòng Chất Lượng',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            11 => array (
                'id' => 12,
                'name' => 'Phòng Kỹ Thuật',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            12 => array (
                'id' => 13,
                'name' => 'Phòng Thu Mua',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            13 => array (
                'id' => 14,
                'name' => 'Phòng Truyền Thông',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            14 => array (
                'id' => 15,
                'name' => 'Ban Dự Án',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            15 => array (
                'id' => 16,
                'name' => 'Ban Pháp Chế',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
