<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentVicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('department_vices')->delete();

        DB::table('department_vices')->insert(array (
            0 => array (
                'id' => 1,
                'department_id' => 4,
                'vice_id' => 27,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'department_id' => 15,
                'vice_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'department_id' => 14,
                'vice_id' => 33,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'department_id' => 11,
                'vice_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'department_id' => 9,
                'vice_id' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
