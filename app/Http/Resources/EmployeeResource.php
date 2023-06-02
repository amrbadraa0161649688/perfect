<?php

namespace App\Http\Resources;

use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestDt;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $query = EmployeeRequestDt::where('emp_id', $this->emp_id)->where('emp_request_type_id',
            SystemCode::where('system_code', 46009)->first()->system_code_id)->first();

        if ($this->status->system_code == 40001) {
            return [
                'emp_name_full_ar' => $this->emp_name_full_ar,
                'emp_name_full_en' => $this->emp_name_full_en,
                'certificate' => $this->certificates,
                'nationality_name_ar' => $this->nationality->system_code_name_ar,
                'nationality_name_en' => $this->nationality->system_code_name_en,
                'branch' => $this->branch,
                'job' => $this->contractActive ? $this->contractActive->job : '',
                'division' => $this->contractActive ? $this->contractActive->job->division : '',
                'address' => $this->emp_current_address,
                'manager' => [
                    //     'name_ar' => $this->manager->emp_name_full_ar,
                    //     'name_en' => $this->manager->emp_name_full_en,
                ],
                'nationality' => $this->nationality,
                'emp_social_status' => $this->socialStatus,
                'emp_birthday' => $this->emp_birthday,
                'emp_identity' => $this->emp_identity,
                'emp_current_address' => $this->emp_current_address,
                'last_panel_action_date' => $this->last_panel_action_date,
                'total_ancestors' => $this->total_ancestors,
                'contract_start_date' => $this->contractActive ? $this->contractActive->emp_contract_start_date : '',
                'contract_end_date' => $this->contractActive ? $this->contractActive->emp_contract_end_date : ''

            ];
        } else {
            return [
                'branch' => $this->branch,
                'job' => $this->contractActive ? $this->contractActive->job : '',
                'division' => $this->contractActive ? $this->contractActive->job->division : '',
                'address' => $this->emp_current_address,
                'emp_direct_date' => $this->emp_direct_date,
//              'last_vacation_date' => isset($query) ? $query->emp_request_end_date : 0,
                'last_vacation_date' => $this->emp_last_vacation_start ? $this->emp_last_vacation_start->format('Y-m-d') : 0,
                'basic_salary' => $this->basic_salary,
                'emp_name_full_ar' => $this->emp_name_full_ar,
                'emp_name_full_en' => $this->emp_name_full_en,
                'emp_name_1_ar' => $this->emp_name_1_ar,
                'emp_name_2_ar' => $this->emp_name_2_ar,
                'emp_name_3_ar' => $this->emp_name_3_ar,
                'emp_name_4_ar' => $this->emp_name_4_ar,
                'emp_name_1_en' => $this->emp_name_1_en,
                'emp_name_2_en' => $this->emp_name_2_en,
                'emp_name_3_en' => $this->emp_name_3_en,
                'emp_name_4_en' => $this->emp_name_4_en,
                'emp_default_company_id' => $this->emp_default_company_id,
                'emp_default_branch_id' => $this->emp_default_branch_id,
                'emp_work_start_date' => Carbon::parse($this->emp_work_start_date)->format('Y-m-d'),
                'pending_vacation' => $this->pendingVacations,
                'manager' => [
                    //     'name_ar' => $this->manager->emp_name_full_ar,
                    //     'name_en' => $this->manager->emp_name_full_en,
                ],
                'nationality' => $this->nationality,
                'emp_social_status' => $this->socialStatus,
                'emp_birthday' => $this->emp_birthday,
                'emp_identity' => $this->emp_identity,
                'emp_current_address' => $this->emp_current_address,
                'last_panel_action_date' => $this->last_panel_action_date,
                'total_ancestors' => $this->total_ancestors,
                'contract_start_date' => $this->contractActive ? $this->contractActive->emp_contract_start_date : '',
                'contract_end_date' => $this->contractActive ? $this->contractActive->emp_contract_end_date : ''
            ];
        }

    }
}
