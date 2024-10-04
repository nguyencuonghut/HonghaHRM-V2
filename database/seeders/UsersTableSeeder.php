<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert(array (
            0 => array (
                'id' => 1,
                'name' => 'Nguyễn Văn Cường',
                'email' => 'nguyenvancuong@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 1,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'name' => 'Tạ Văn Toại',
                'email' => 'gd@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 2,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'name' => 'Hoàng Thị Ngọc Ánh',
                'email' => 'tdv@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'name' => 'Phạm Thị Thơm',
                'email' => 'ns@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 4,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'name' => 'Triệu Thị Hương',
                'email' => 'trieuthihuong@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            5 => array (
                'id' => 6,
                'name' => 'Lưu Văn Tuấn',
                'email' => 'luuvantuan@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            6 => array (
                'id' => 7,
                'name' => 'Nguyễn Văn Long',
                'email' => 'nguyenvanlong@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            7 => array (
                'id' => 8,
                'name' => 'Trần Tiến Dũng',
                'email' => 'trantiendung@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => 'Mở',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
