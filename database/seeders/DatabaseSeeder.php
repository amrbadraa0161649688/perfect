<?php

namespace Database\Seeders;

use App\Models\ApplicationsMenu;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        ApplicationsMenu::create([
            [
                'app_menu_id' => 2,
                'app_menu_code' => 1001,
                'app_menu_name_ar' => 'الشركات',
                'app_menu_name_en' => 'companies',
                'app_menu_url' => 'mainCompanies',
            ],
            [
                'app_menu_id' => 3,
                'app_menu_code' => 1001,
                'app_menu_name_ar' => 'الهياكل الاداريه',
                'app_menu_name_en' => 'Administrative structure',
                'app_menu_url' => 'administrativeStructures',
            ],
            [
                'app_menu_id' => 5,
                'app_menu_code' => 1001,
                'app_menu_name_ar' => 'صلاحيات الوظائف',
                'app_menu_name_en' => 'Job Permissions',
                'app_menu_url' => 'jobPermissions',
            ],
            [
                'app_menu_id' => 25,
                'app_menu_code' => 1002,
                'app_menu_name_ar' => ' بيانات المستخدمين',
                'app_menu_name_en' => 'users',
                'app_menu_url' => 'users',
            ]

        ]);

        $this->call(MainTypeSeeder::class);
        $this->call(EntryStatusSeeder::class);
        $this->call(AccountingEntrySeeder::class);
       // $this->call(CurrencySeeder::class);
        $this->call(PeriodStatusSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(SystemCodeCategorySeeder::class);

    }
}
