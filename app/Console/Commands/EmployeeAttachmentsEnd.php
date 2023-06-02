<?php

namespace App\Console\Commands;

use App\Mail\employeeAttachmentMail;
use App\Mail\NotificationMail;
use App\Mail\usersAttachmentMail;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use App\Models\UsersPermissionsRol;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmployeeAttachmentsEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notification to employee and users';

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
        //$company = session('company') ? session('company') : auth()->user()->company;

        $companies_group = CompanyGroup::get();


        foreach ($companies_group as $company_group) {
            $users = User::where('company_group_id', $company_group->company_group_id)
                ->whereIn('user_id', UsersPermissionsRol::where('rols_id', 3)
                    ->pluck('user_id')->toArray())->get();

            \Log::info("Cron is working fine!");

            $employees_id = Employee::where('company_group_id', $company_group->company_group_id)
                ->pluck('emp_id')->toArray();

            $attachments = Attachment::where('app_menu_id', 8)
                ->whereIn('transaction_id', $employees_id)
                ->whereDate('expire_date', '<=', Carbon::now()->addDays(30))
                ->whereDate('expire_date', '>=', Carbon::now())->get();

            \Log::info($attachments);

            //  $employees = Employee::whereIn('emp_id', $attachments->pluck('transaction_id')->toArray())->get();

            foreach ($attachments as $k => $attachment) {
                $employee = Employee::where('emp_id', $attachment->transaction_id)->first();
                $company = Company::where('company_id', $employee->emp_default_company_id)->first();

                $attachment_type = $attachment->attachmentType->system_code_name_ar;
                $attachment_link = asset('Files/' . $attachment->attachment_file_url);

                if ($employee->emp_is_user_application && $employee->user) {
                    Notification::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'notification_type' => 'attachment',
                        'notification_app_type' => $attachment->app_menu_id,
                        'notification_user_id' => $employee->user->user_id,
                        'notification_email_send' => $employee->emp_email_work,
                        'notification_status' => 0,
                        'notifiable_id' => $attachment->attachment_id,
                        'notification_data' => $attachment->attachmentType->system_code_name_ar . ' للموظف ' . '-' . $employee->emp_name_full_ar . '-' . ' علي وشك الانتهاء ',
                    ]);


                    \Mail::to($employee->emp_email_work)
                        ->send(new employeeAttachmentMail($employee->companyGroup->main_email, $employee, $attachment_type, $attachment_link,
                            'صلاحيه المستند الخاص بكم علي وشك الانتهاء'));

                }

                foreach ($users as $user) {

                    Notification::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'notification_type' => 'attachment',
                        'notification_app_type' => $attachment->app_menu_id,
                        'notification_user_id' => $user->user_id,
                        'notification_email_send' => $user->user_email,
                        'notification_status' => 0,
                        'notifiable_id' => $attachment->attachment_id,
                        'notification_data' => $attachment->attachmentType->system_code_name_ar . ' للموظف ' . '-' . $employee->emp_name_full_ar . '-' . ' علي وشك الانتهاء ',
                    ]);

                    \Mail::to($user->user_email)
                        ->send(new usersAttachmentMail($employee, $user->companyGroup->main_email, $attachment_type, $attachment_link, 'صلاحيه المستند للموظف علي وشك الانتهاء'));

                }
            }

        }


        \Log::info("Cron is working fine!");


    }
}
