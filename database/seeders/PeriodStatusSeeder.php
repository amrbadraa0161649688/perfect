<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periods_statuses')->truncate();
        $data = [
            ['ar_name'=>'مرحل','en_name'=>''],
            ['ar_name'=>'غير مرحل','en_name'=>''],
        ];
        DB::table('periods_statuses')->insert($data);
    }
}
