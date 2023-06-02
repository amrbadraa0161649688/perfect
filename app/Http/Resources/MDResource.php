<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MDResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'emp_variables_id' => $this->emp_variables_id,
            'emp_variables_id_dt' => $this->emp_variables_id_dt,
            'emp_variables_type_id' => $this->emp_variables_type, ////from system code
            'emp_id' => $this->emp_id,
//            'emp_variables_type' => $this->emp_variables_type,
            'emp_variables_hours' => $this->emp_variables_hours ? $this->emp_variables_hours : 0,
            'hours_valid' => true,
            'emp_variables_minutes' => $this->emp_variables_minutes ? $this->emp_variables_minutes : 0,
            'emp_variables_minutes_valid' => true,
            'emp_variables_days' => $this->emp_variables_days ? $this->emp_variables_days : 0,
            'days_valid' => true,
            'emp_variables_salary' => $this->emp_variables_salary ? $this->emp_variables_salary : 0,
            'emp_variables_factor' => $this->emp_variables_factor ? $this->emp_variables_factor : 0,
            'emp_variables_debit' => $this->emp_variables_debit ? $this->emp_variables_debit : 0,
            'emp_variables_credit' => $this->emp_variables_credit ? $this->emp_variables_credit : 0,
            'emp_variables_notes' => $this->emp_variables_notes,
            'emp_variables_main_type' => $this->emp_variables_main_type,
            'acc_period_id' => $this->acc_period_id,
            'branch' => $this->employee->branch

        ];
    }
}
