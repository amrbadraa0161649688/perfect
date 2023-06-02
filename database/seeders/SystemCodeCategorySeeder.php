<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemCodeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_code_categories')->truncate();
        $data = [
            [
                'sys_category_id' => 2,
                'sys_category_name_ar' => 'انواع الاجازات'
                , 'sys_category_name_en' => 'vacation',
                'sys_category_type' => 'main',
                'sys_category_app' => 1
            ],
            [
                'sys_category_id' => 4,
                'sys_category_name_ar' => ' حاله الموظف'
                , 'sys_category_name_en' => 'emp_status',
                'sys_category_type' => 'type',
                'sys_category_app' => 1
            ]

        ];
        DB::table('system_code_categories')->insert($data);
    }
}
