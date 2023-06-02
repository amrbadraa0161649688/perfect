<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->truncate();

        $type = Account::create([
            'code'=>'1-0-0-0',
            'name'=>'أصول',
            'nature'=>'d',
            'search_code'=>'A'
        ]);
        $type->update(['main_type_id'=>$type->id]);

        $type = Account::create([
            'code'=>'2-0-0-0',
            'name'=>'خصوم',
            'nature'=>'c',
            'search_code'=>'L'
        ]);
        $type->update(['main_type_id'=>$type->id]);

        $type = Account::create([
            'code'=>'3-0-0-0',
            'name'=>'إيرادات',
            'nature'=>'c',
            'search_code'=>'R'
        ]);
        $type->update(['main_type_id'=>$type->id]);

        $type = Account::create([
            'code'=>'4-0-0-0',
            'name'=>'مصروفات',
            'nature'=>'d',
            'search_code'=>'E'
        ]);
        $type->update(['main_type_id'=>$type->id]);
    }
}
