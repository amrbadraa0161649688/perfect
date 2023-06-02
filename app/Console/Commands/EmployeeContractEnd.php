<?php

namespace App\Console\Commands;

use App\Mail\employeeAttachmentMail;
use App\Mail\usersAttachmentMail;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\Notification;
use App\Models\User;
use App\Models\UsersPermissionsRol;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EmployeeContractEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notification of end of contracts';

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

//            $emp_ids = $company_group->employees->pluck('emp_id')->toArray();

            $users = User::where('company_group_id', $company_group->company_group_id)
                ->whereIn('user_id', UsersPermissionsRol::where('rols_id', 5)
                    ->pluck('user_id')->toArray())->get();


            $contracts = EmployeeContract::whereHas('employee', function ($query) use ($company_group) {
                $query->where('company_group_id', $company_group->company_group_id);
            })->whereDate('emp_contract_end_date', '<=', Carbon::now()->addDays(30))
                ->whereDate('emp_contract_end_date', '>=', Carbon::now())->get();


            //$employees = Employee::whereIn('emp_id', $contracts->pluck('emp_id')->toArray())->get();

            foreach ($contracts as $contract) {
                $employee = Employee::where('emp_id', $contract->emp_id)->first();
                $company = Company::where('company_id', $employee->emp_default_company_id)->first();

                if ($employee->emp_is_user_application) {
                    Notification::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'notification_type' => 'contract',
                        'notification_app_type' => 8, ///بيانات الموظفين
                        'notification_user_id' => $employee->user->user_id,
                        'notification_email_send' => $employee->emp_email_work,
                        'notification_status' => 0,
                        'notifiable_id' => $contract->emp_contract_id,
                        'notification_data' => 'العقد للموظف ' . '-' . $employee->emp_name_full_ar . '-' . ' علي وشك الانتهاء ',
                    ]);
                    \Mail::to($employee->emp_email_private)
                        ->send(new employeeAttachmentMail($employee->companyGroup->main_email, $employee, 'عقد', '', 'العقد الخاص بكم علي وشك الانتهاء'));

                }

                foreach ($users as $user) {

                    Notification::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'notification_type' => 'contract',
                        'notification_app_type' => 8,
                        'notification_user_id' => $user->user_id,
                        'notification_email_send' => $user->user_email,
                        'notification_status' => 0,
                        'notifiable_id' => $contract->emp_contract_id,
                        'notification_data' => ' العقد للموظف' . '-' . $employee->emp_name_full_ar . '-' . ' علي وشك الانتهاء ',
                    ]);

                    \Mail::to($user->user_email)
                        ->send(new usersAttachmentMail($employee, $user->companyGroup->main_email, 'عقد', '', 'صلاحيه العقد لقائمه الموظفين علي وشك الانتهاء'));

                }
            }
        }

        \Log::info("Cron is working fine fo contracts!");


    }
}
