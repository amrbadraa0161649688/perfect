<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entry_status')->truncate();
        $data = [
            [
                'name_en'=>'',
                'name_ar'=>'مرحل',
            ],
            [
                'name_en'=>'',
                'name_ar'=>'غير مرحل',
             
            ],
            [
                'name_en'=>'',
                'name_ar'=>'تحت الانشاء',
            ],
           
           
        ];

		DB::table('entry_status')->insert($data);
    }
}
