<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_types')->truncate();
        $data = [
            [
                'name_en'=>'AssetsM',
                'name_ar'=>'أصول',
                'code'=>'d',
            ],
            [
                'name_en'=>'Liabilities',
                'name_ar'=>'خصوم',
                'code'=>'c',

            ],
            [
                'name_en'=>'Revenue',
                'name_ar'=>'إيرادات',
                'code'=>'c',
            ],
            [
                'name_en'=>'Expenses',
                'name_ar'=>'مصروفات',
                'code'=>'d',

            ],

        ];

		DB::table('main_types')->insert($data);
    }
}
