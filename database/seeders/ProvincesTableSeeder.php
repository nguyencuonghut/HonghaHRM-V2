<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->delete();

        DB::table('provinces')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'An Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Bà Rịa-Vũng Tàu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            2 =>
                array (
                    'id' => 3,
                    'name' => 'Bắc Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Bắc Kạn',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Bạc Liêu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Bắc Ninh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'Bến Tre',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'name' => 'Bình Định',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'name' => 'Bình Dương',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'name' => 'Bình Phước',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'name' => 'Bình Thuận',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'name' => 'Cà Mau',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'name' => 'Cần Thơ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'name' => 'Cao Bằng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'name' => 'Đà Nẵng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'name' => 'Đắk Lắk ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'name' => 'Đắk Nông',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'name' => 'Điện Biên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'name' => 'Đồng Nai',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'name' => 'Đồng Tháp',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'name' => 'Gia Lai',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'name' => 'Hà Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'name' => 'Hà Nam',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'name' => 'Hà Nội',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'name' => 'Hà Tĩnh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'name' => 'Hải Dương',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26 =>
                array (
                    'id' => 27,
                    'name' => 'Hải Phòng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            27 =>
                array (
                    'id' => 28,
                    'name' => 'Hậu Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            28 =>
                array (
                    'id' => 29,
                    'name' => 'TP. Hồ Chí Minh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            29 =>
                array (
                    'id' => 30,
                    'name' => 'Hòa Bình',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            30 =>
                array (
                    'id' => 31,
                    'name' => 'Hưng Yên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            31 =>
                array (
                    'id' => 32,
                    'name' => 'Khánh Hòa',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            32 =>
                array (
                    'id' => 33,
                    'name' => 'Kiên Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            33 =>
                array (
                    'id' => 34,
                    'name' => 'Kon Tum',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            34 =>
                array (
                    'id' => 35,
                    'name' => 'Lai Châu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            35 =>
                array (
                    'id' => 36,
                    'name' => 'Lâm Đồng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            36 =>
                array (
                    'id' => 37,
                    'name' => 'Lạng Sơn',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            37 =>
                array (
                    'id' => 38,
                    'name' => 'Lào Cai',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            38 =>
                array (
                    'id' => 39,
                    'name' => 'Long An',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            39 =>
                array (
                    'id' => 40,
                    'name' => 'Nam Định',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            40 =>
                array (
                    'id' => 41,
                    'name' => 'Nghệ An',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            41=>
                array (
                    'id' => 42,
                    'name' => 'Ninh Bình',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            42 =>
                array (
                    'id' => 43,
                    'name' => 'Ninh Thuận',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            43=>
                array (
                    'id' => 44,
                    'name' => 'Phú Thọ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            44 =>
                array (
                    'id' => 45,
                    'name' => 'Phú Yên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            45 =>
                array (
                    'id' => 46,
                    'name' => 'Quảng Bình',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            46 =>
                array (
                    'id' => 47,
                    'name' => 'Quảng Nam',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            47 =>
                array (
                    'id' => 48,
                    'name' => 'Quảng Ngãi',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            48 =>
                array (
                    'id' => 49,
                    'name' => 'Quảng Ninh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            49 =>
                array (
                    'id' => 50,
                    'name' => 'Quảng Trị',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            50 =>
                array (
                    'id' => 51,
                    'name' => 'Sóc Trăng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            51=>
                array (
                    'id' => 52,
                    'name' => 'Sơn La',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            52 =>
                array (
                    'id' => 53,
                    'name' => 'Tây Ninh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            53 =>
                array (
                    'id' => 54,
                    'name' => 'Thái Bình',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            54 =>
                array (
                    'id' => 55,
                    'name' => 'Thái Nguyên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            55 =>
                array (
                    'id' => 56,
                    'name' => 'Thanh Hóa',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            56 =>
                array (
                    'id' => 57,
                    'name' => 'Thừa Thiên - Huế',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            57 =>
                array (
                    'id' => 58,
                    'name' => 'Tiền Giang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            58 =>
                array (
                    'id' => 59,
                    'name' => 'Trà Vinh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            59 =>
                array (
                    'id' => 60,
                    'name' => 'Tuyên Quang',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            60 =>
                array (
                    'id' => 61,
                    'name' => 'Vĩnh Long',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            61 =>
                array (
                    'id' => 62,
                    'name' => 'Vĩnh Phúc',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            62 =>
                array (
                    'id' => 63,
                    'name' => 'Yên Bái',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
