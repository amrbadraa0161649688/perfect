<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class purchaseInvoiceResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'company_id' => $this->company_id,
            'company_name_ar' => $this->company->company_name_ar,
            'company_name_en' => $this->company->company_name_en,
            'acc_period_id' => $this->acc_period_id,
            'invoice_date' => Carbon::parse($this->invoice_date)->format('Y-m-d'),
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'customer_tax_no' => $this->customer_tax_no,
            'customer_phone' => $this->customer_phone,
            'invoice_due_date' => Carbon::parse($this->invoice_due_date)->format('Y-m-d'),
            'supply_date' => Carbon::parse($this->supply_date)->format('Y-m-d'),
            'po_number' => $this->po_number,
            'gr_number' => $this->gr_number,
            'invoice_notes' => $this->invoice_notes,
            'payment_tems' => $this->payment_tems,


        ];
    }
}
