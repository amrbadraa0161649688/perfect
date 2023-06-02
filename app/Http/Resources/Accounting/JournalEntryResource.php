<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'user_statement' => $this->user_statement ?? '',
            'general_statement' => $this->general_statement ?? '',

            'journal_entry_no' => $this->when($this->journal_entry_no,$this->journal_entry_no ),
            'doc_no' => $this->doc_no ?? '',
            'file_no' => $this->file_no ?? '',
            'debit' => intVal($this->debit),
            'credit' => intVal($this->credit),
            
           
            'entry_status' =>  new EntryStatusResource ($this->entryStatus),

            'company' =>  new CompanyWithoutSubsidiaryResource ($this->company),
            'subsidiary' =>  new SubsidiaryResource ($this->subsidiary),
            'branch' =>  new BranchWithoutCompanyResource ($this->branch),

            'accounting-entry' =>  new AccountingEntryResource ($this->accountingEntry),
            'account-period' =>  new AccountPeriodResource ($this->accountPeriod),
            'date' =>  $this->date ?? '',

            'journal_details' =>  JournalDetailResource::collection($this->journalDetails),

            
        ];
    }
}
