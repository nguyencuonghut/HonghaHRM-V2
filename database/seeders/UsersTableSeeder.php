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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'name' => 'Tạ Văn Toại',
                'email' => 'gd@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'name' => 'Hoàng Thị Ngọc Anhs',
                'email' => 'tdv@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'name' => 'Phạm Thị Thơm',
                'email' => 'ns@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        ));
    }
}
