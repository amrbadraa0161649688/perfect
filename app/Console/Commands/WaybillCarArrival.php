<?php

namespace App\Console\Commands;

use App\Models\CompanyGroup;
use App\Models\Notification;
use App\Models\UsersPermissionsRol;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Console\Command;

class WaybillCarArrival extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waybills:arrive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if waybills is not added to trip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companies_group = CompanyGroup::get();

        foreach ($companies_group as $company_group) {

            $users = \App\Models\User::where('company_group_id', $company_group->company_group_id)
                ->whereIn('user_id', UsersPermissionsRol::where('rols_id', 6)
                    ->pluck('user_id')->toArray())->get();

            $waybills = WaybillHd::where('waybill_type_id', 4)
                ->where('company_group_id', $company_group->company_group_id)
                ->whereDoesntHave('trip')->whereDate('waybill_delivery_expected', '<=',
                    Carbon::now()->addDays(2))->get();

            foreach ($waybills as $waybill) {
                foreach ($users as $user) {
                    Notification::create([
                        'company_group_id' => $company_group->company_group_id,
                        'company_id' => $waybill->company_id,
                        'notification_type' => 'waybill-car',
                        'notification_app_type' => 88,
                        'notification_user_id' => $user->user_id,
                        'notification_email_send' => $user->user_email,
                        'notification_status' => 0,
                        'notifiable_id' => $waybill->waybill_id,
                        'notification_data' => 'اشعار تاخر بوليصه شحن رقم ' . '-' . $waybill->waybill_code . '-' . ' الي ' . $waybill->locTo->system_code_name_ar
                            . ' تاريخ الوصول المتوقع ' . $waybill->waybill_delivery_expected,
                    ]);
                }
            }


            \Log::info($waybills);

        }
    }
}
