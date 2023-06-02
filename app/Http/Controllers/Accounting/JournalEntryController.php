<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App;
use Carbon\Carbon;

use Facades\App\Classes\Responder;
use App\Rules\DateInPeriod;


use App\Models\Master\Company;
use App\Models\Master\Subsidiary;
use App\Models\Master\Branch;
use App\Models\Master\AccountingEntry;
use App\Models\Master\AccountPeriod;
use App\Models\Master\JournalEntry;
use App\Models\Master\JournalDetail;

use App\Http\Resources\Accounting\JournalEntryResource;
use App\Http\Resources\Accounting\JournalDetailResource;
use App\Http\Resources\Accounting\BranchWithoutCompanyResource;
use App\Http\Resources\Accounting\SubsidiaryResource;
use App\Http\Resources\Accounting\AccountPeriodResource;

use App\Filters\JournalEntry\IndexFilter;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{

    public function index(){


        $pageination = JournalEntry::filter(new IndexFilter(request()))
        ->with(['subsidiary','branch','accountingEntry','accountPeriod','entryStatus'])
        ->paginate(30);
        return $this->getPaginationData($pageination,JournalEntry::class);

    }


    public function show($id){

        $item = JournalEntry::find($id);
        if(is_null($item)){
             return response(trans('data.notExist-journal_entry'),404);
        }

        $res = [
            'id'=>$item->id,
            'subsidiary_id'=>$item->subsidiary_id ?? '',
            'subsidiary_name'=>optional($item->subsidiary)->name ?? '',
            'branch_id'=>$item->branch_id ?? '',
            'branch_name'=>optional($item->branch)->name ?? '',
            'accounting_entry_name'=>optional($item->accountingEntry)->name ?? '',
            'accounting_entry_id'=>$item->accounting_entry_id ?? '',
            'account_period_id'=>$item->account_period_id ?? '',
            'account_period_name'=>optional($item->accountPeriod)->year .'/'.optional($item->accountPeriod)->month,
            'doc_no' => $item->doc_no ?? '',
            'file_no' => $item->file_no ?? '',
            'journal_entry_no'=>$item->journal_entry_no ?? '',
            'date'=>$item->date ?? '',
            'debit'=>0,
            'credit'=>0,
            'statement'=>'',
            'general_statement'=>$item->general_statement ?? '',
            'entry_status_name'=>optional($item->entryStatus)->name_ar ?? '',
            'entry_status_id'=>$item->entry_status_id ?? '',
            'cost_center_id'=> '',
            'details'=>$item->journalDetails->map(function($detail){
                return [
                    'id'=>$detail->id,
                    'account_id'=>$detail->account_id ?? '',
                    'account_name'=>optional($detail->account)->getAccountCodeName(),
                    'cost_center_id'=>$detail->cost_center_id ?? '',
                    'cost_center_name'=>optional($detail->costCenter)->name ?? '',
                    'statement'=>$detail->statement ?? '',
                    'debit'=>$detail->debit ?? 0,
                    'credit'=>$detail->credit ?? 0,
                ];
            })

        ];
        return response($res);
    }


    public function store(Request $request){
        $this->validate($request,$this->rules(),$this->messages());
        $data = $request->only([
            'company_id',
            'subsidiary_id',
            'branch_id',
            'accounting_entry_id',
            'account_period_id',
            'date',
            'doc_no',
            'file_no',
            'general_statement',
        ]);

        DB::transaction(function () use($data,$request){
            $journalEntry = JournalEntry::create($data);
            foreach ($request->input('details',[]) as $value) {
                $detail['account_id'] = $value['account_id'];
                $detail['cost_center_id'] = $value['cost_center_id'];
                $detail['statement'] = $value['statement'];
                $detail['debit'] = $value['debit'] ?? 0;
                $detail['credit'] = $value['credit'] ?? 0;
                $detail['balance'] = floatval($detail['debit']) - floatval($detail['credit']);
                $journalEntry->journalDetails()->create($detail);
            }
            $debit=$journalEntry->journalDetails()->sum('debit');
            $credit=$journalEntry->journalDetails()->sum('credit');
            $balance=  $debit - $credit;

            $conunt = JournalEntry::count();
            $padding = str_pad($conunt+1,6,0,STR_PAD_LEFT);
            if(!is_null($journalEntry->subsidiary_id)){
                $padding = optional($journalEntry->subsidiary)->prefix .'-'. $padding;
            }else{
                $padding = optional($journalEntry->company)->prefix .'-'. $padding;
            }
            $journalEntry->update([
                'journal_entry_no'=>$padding,
                'balance'=>$balance,
                'debit'=>$debit,
                'credit'=>$credit,
                'entry_status_id'=>$balance == 0 ? 2 : 3
            ]);
        });

        return response(trans('forms.created'),201);
    }


    public function update(Request $request,$id){

        $journalEntry=JournalEntry::where('id',$id)->first();

        if(is_null($journalEntry)){
             return response(trans('data.notExist-journal_entry'),404);
        }

        // if($journalEntry->company_id !=$request->company_id){
        //     return response(trans('data.notcompanyupdated-journal'),404);
        // }

        // if($journalEntry->subsidiary_id !=$request->subsidiary_id){
        //     return response(trans('data.notsubsidiaryupdated-journal'),404);
        // }

        // if($journalEntry->branch_id !=$request->branch_id){
        //     return response(trans('data.notbranchupdated-journal'),404);
        // }

        $this->validate($request,$this->rules(true,$journalEntry),$this->messages());
        $data = $request->only([
            'company_id',
            'subsidiary_id',
            'accounting_entry_id',
            'account_period_id',
            'branch_id',
            'date',
            'doc_no',
            'file_no',
            'general_statement',
        ]);
        DB::transaction(function () use($data,$request,$journalEntry){
            $journalEntry->update($data);
            $createDetails = collect($request->input('details',[]))
            ->filter(function($item){
                return $item['id'] == 0;
            });
            foreach ($createDetails as $value) {
                $detail['account_id'] = $value['account_id'];
                $detail['cost_center_id'] = $value['cost_center_id'];
                $detail['statement'] = $value['statement'];
                $detail['debit'] = $value['debit'] ?? 0;
                $detail['credit'] = $value['credit'] ?? 0;
                $detail['balance'] = floatval($detail['debit']) - floatval($detail['credit']);
                $journalEntry->journalDetails()->create($detail);
            }

            $editDetails = collect($request->input('details',[]))
            ->filter(function($item){
                return $item['id'] !== 0;
            });
            foreach ($editDetails as $value) {
                // $detail['account_id'] = $value['account_id'];
                // $detail['cost_center_id'] = $value['cost_center_id'];
                $detail['statement'] = $value['statement'];
                $detail['debit'] = $value['debit'] ?? 0;
                $detail['credit'] = $value['credit'] ?? 0;
                $detail['balance'] = floatval($detail['debit']) - floatval($detail['credit']);
                $journalEntry->journalDetails()->where('id',$value['id'])->update($detail);
            }

            $deleteDetails = collect($request->input('details',[]))
            ->filter(function($item){
                return isset($item['delete']) && $item['delete'] == '1';
            });
            foreach ($deleteDetails as $value) {
                $journalEntry->journalDetails()->where('id',$value['id'])->delete();
            }
            $debit=$journalEntry->journalDetails()->sum('debit');
            $credit=$journalEntry->journalDetails()->sum('credit');
            $balance=  $debit - $credit;
            if($balance == 0 &&  $request->input('change_status') == '1'){
                $entry_status_id = 1;
            }else{
                $entry_status_id = $balance == 0 ? 2 : 3;
            }

            $journalEntry->update([
                'balance'=>$balance,
                'debit'=>$debit,
                'credit'=>$credit,
                'entry_status_id'=>$entry_status_id
            ]);
        });

        return response(trans('forms.updated'),204);
    }

    public function journalEntryNo(Request $request){

        $lastjournal=JournalEntry::whereNotNull('journal_entry_no')->latest()->first();

        if($lastjournal){
            $lastjournal=$lastjournal->journal_entry_no;
            $journal_entry_no=explode("-",$lastjournal);
            $journal_entry_no=str_pad((++$journal_entry_no[2]) ,6, '0', STR_PAD_LEFT);
        }

        if($request->company_id){
            $company=Company::find($request->company_id);
            if(!$company->serial_per_company){
                $prefix=$company->prefix;
            }else{
                $subsidiary=Subsidiary::find($request->subsidiary_id);
                $prefix=$subsidiary->prefix;
            }

        }

        $year=Carbon::parse($request->date)->year;

       return "$prefix-$year-$journal_entry_no";
    }

    public function getBranches(Request $request){

        $data = Branch::where('company_id',$request->company_id)->first();
        if(is_null($data)){
             return response(trans('data.notExist-branch'),404);
        }
        $res = BranchWithoutCompanyResource::collection($data);
        return Responder::setData($res)->respond();
    }


    public function getAccountPeriod(){

        $data = AccountPeriod::where('is_active',true)->get();
        $res =   AccountPeriodResource::collection($data);
        return Responder::setData($res)->respond();
    }

    public function getSubsidiary(Request $request){

        $data = Subsidiary::where('parent_id',$request->company_id)->first();
        if(is_null($data)){
             return response(trans('data.notExist-subsidiary'),404);
        }
        $res = SubsidiaryResource::collection($data);
        return Responder::setData($res)->respond();
    }


    protected function rules($is_update = false,$journalEntry = null){

        $rules =  [
            'company_id'=>'required|exists:companies,id',
            'subsidiary_id'=>'nullable|exists:subsidiaries,id',
            'branch_id'=>'nullable|exists:branches,id',
            'accounting_entry_id'=>'nullable|exists:accounting_entries,id',
            'account_period_id'=>'required|exists:periods,id',

            // 'journal_entry_no'=>'required|unique:journal_entries,journal_entry_no',
            'doc_no'=>'nullable',
            'file_no'=>'nullable',

            'user_statement'=>'nullable|max:1500',
            'general_statement'=>'required|max:1500',

            //'date'=>['required','date','date_format:Y-m-d',new DateInPeriod(\request()->account_period_id)],
            //
            'details.*.account_id'=>'required|exists:accounts,id',
            'details.*.cost_center_id'=>'nullable|exists:cost_centers,id',
            'details.*.statement'=>'required|max:1500',
            'details.*.debit'=>'nullable|min:0|numeric',
            'details.*.credit'=>'nullable|min:0|numeric',

        ];
        if($is_update){
            $rules = array_merge($rules, [
                // 'journal_entry_no'=>'required|unique:journal_entries,journal_entry_no,'.$journalEntry->id,

            ]);
        }
        return $rules;


    }


    public function messages(){

        return  [

            'company_id.required'=> App::isLocale('en') ? 'Company is Required':'يجب اختيار الشركة',
            'company_id.exists'=> App::isLocale('en') ? 'Company is Invaild':' الشركة غير صحيحة',

            'subsidiary_id.required'=> App::isLocale('en') ? 'Subsidiary is Required':'يجب اختيار الشركة الفرعية',
            'subsidiary_id.exists'=> App::isLocale('en') ? 'Subsidiary is Invaild':'الشركة الفرعية غير صحيحة',

            'branch_id.required'=> App::isLocale('en') ? 'Branch is Required':'يجب اختيار الفرع',
            'branch_id.exists'=> App::isLocale('en') ? 'Branch is Invaild':' الفرع غير صحيحة',

            'accounting_entry_id.required'=> App::isLocale('en') ? 'Accounting Entry Entry is Required':'يجب اختيار نوع اليومية',
            'accounting_entry_id.exists'=> App::isLocale('en') ? 'Accounting Entry Entry is Invaild':'نوع اليومية غير صحيحة',

            'account_period_id.required'=> App::isLocale('en') ? 'Accounting Period is Required':'يجب اختيارالفترةالمحاسبية',
            'account_period_id.exists'=> App::isLocale('en') ? 'Accounting Period is Invaild':'الفترةالمحاسبيةغيرصحيحة',

            // 'journal_entry_no.required'=> App::isLocale('en') ? 'Journal Entry No is Required':'يجب ادخال رقم القيد',
            // 'journal_entry_no.unique'=> App::isLocale('en') ? 'Journal Entry No is already exists':'رقم القيد مودجود مسبقا',
            'doc_no.required'=> App::isLocale('en') ? 'Doc No. is Required':'يجب ادخال رقم المستند',
            'file_no.required'=> App::isLocale('en') ? 'File No. is Required':'يجب ادخال رقم الملف',

            'general_statement.max'=> App::isLocale('en') ? 'General Statement Must be no more than 1500 characters':'البيان العام للقيدالايزيد عن 1500 حرف',
            'user_statement.max'=> App::isLocale('en') ? 'User Statement Must be no more than 1500 characters':'بيان المستخدم يجب الايزيد عن 1500 حرف',

            'date.required'=>App::isLocale('en') ? 'Date  is required':'يجب ادخال التاريخ',
            'date.date'=>App::isLocale('en') ? 'Date Not In Correct Formate':'صيغة التاريخ غير صحيحة',
            'date.date_format'=>App::isLocale('en') ? 'Date Not In Correct Formate':'صيغة التاريخ غير صحيحة',

            //
            'details.*.account_id.required'=> App::isLocale('en') ? 'Account is Required':'يجب اختيار الحساب',
            'details.*.account_id.exists'=> App::isLocale('en') ? 'Account is Invaild':' الحساب غير صحيحة',

            'details.*.cost_center_id.required'=> App::isLocale('en') ? 'Cost Center is Required':'يجب اختيار مركز التكلفة',
            'details.*.cost_center_id.exists'=> App::isLocale('en') ? 'Cost Center is Invaild':' مركز التكلفة غير صحيحة',

            'details.*.statement.max'=> App::isLocale('en') ? 'Statement Must be no more than 1500 characters':'البيان يجب الايزيد عن 1500 حرف',

            'details.*.debit.min'=>App::isLocale('en') ? 'Debit Must be positive Number':'يجب ان يكون اكبر من الصفر',
            'details.*.debit.numeric'=>App::isLocale('en') ? 'Debit Accept Only  Number':'يجب ان يكون رقم',

            'details.*.credit.min'=>App::isLocale('en') ? 'Credit Must be positive Number':'يجب ان يكون اكبر من الصفر',
            'details.*.credit.numeric'=>App::isLocale('en') ? 'Credit Accept Only  Number':'يجب ان يكون رقم',

        ];
    }

}
