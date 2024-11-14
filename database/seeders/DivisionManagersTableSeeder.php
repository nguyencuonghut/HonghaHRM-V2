<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('division_managers')->delete();

        DB::table('division_managers')->insert(array (
            0 => array (
                'id' => 1,
                'division_id' => 5,
                'manager_id' => 13,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'division_id' => 20,
                'manager_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'division_id' => 21,
                'manager_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'division_id' => 25,
                'manager_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
