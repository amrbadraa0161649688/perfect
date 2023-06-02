<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class purchaseJournalDtsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $show_customers = false;
        $cc_customer_required = false;
        $show_employees = false;
        $cc_employees_required = false;
        $show_suppliers = false;
        $cc_supplier_required = false;
        $show_branches = false;
        $cc_branch_required = false;
        $show_trucks = false;
        $cc_trucks_required = false;

        if ($this->cc_customer_id) {
            $show_customers = true;
            $cc_customer_required = true;

        } elseif ($this->cc_employee_id) {
            $show_employees = true;
            $cc_employees_required = true;

        } elseif ($this->cc_supplier_id) {
            $show_suppliers = true;
            $cc_supplier_required = true;

        } elseif ($this->cc_branch_id) {
            $show_branches = true;
            $cc_branch_required = true;

        } elseif ($this->cc_truck_id) {
            $show_trucks = true;
            $cc_trucks_required = true;
        }

        return [
            'journal_dt_id' => $this->journal_dt_id,
            'cc_branch_id' => $this->cc_branch_id ? $this->cc_branch_id : null,
            'cc_car_id' => $this->cc_car_id ? $this->cc_car_id : null,
            'cc_employee_id' => $this->cc_employee_id ? $this->cc_employee_id : null,
            'cc_customer_id' => $this->cc_customer_id ? $this->cc_customer_id : null,
            'cc_supplier_id' => $this->cc_supplier_id ? $this->cc_supplier_id : null,
            'cost_center_type_id' => $this->costCenterType->system_code,
            'account_id' => $this->account_id,
            'show_customers' => $show_customers,
            'cc_customer_required' => $cc_customer_required,
            'show_employees' => $show_employees,
            'cc_employees_required' => $cc_employees_required,
            'show_suppliers' => $show_suppliers,
            'cc_supplier_required' => $cc_supplier_required,
            'show_branches' => $show_branches,
            'cc_branch_required' => $cc_branch_required,
            'show_trucks' => $show_trucks,
            'cc_trucks_required' => $cc_trucks_required,
            'cc_truck_id' => $this->cc_car_id ? $this->cc_car_id : null

        ];
    }
}
