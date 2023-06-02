<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App;

use Facades\App\Classes\Responder;

use App\Models\Master\AccountingEntry;

use App\Http\Resources\Accounting\AccountingEntryResource;


class AccountingEntryController extends Controller
{
    public function index(){

        $accountingEntry = AccountingEntryResource::collection(
                                AccountingEntry::all());
        return response($accountingEntry);

    }

    public function show($id){


        $data = AccountingEntry::find($id);
        if(is_null($data)){
            return response(trans('data.notExist-account_entry'),404);
        }

        $res = new  AccountingEntryResource($data);
        return response($res);
    }

    public function store(Request $request){


        $this->validate($request,$this->rules(),$this->messages());

        $accountingEntry = AccountingEntry::create($request->all());

        return Responder::setData(new AccountingEntryResource($accountingEntry))->setMessage(trans('forms.created'))->setStatus(201)->respond();
    }

    public function update(Request $request,$id){


        $accountingEntry=AccountingEntry::where('id',$id)->first();

        if(is_null($accountingEntry)){
            return response(trans('data.notExist-account_entry'),404);
        }

        $this->validate($request,$this->rules(true,$accountingEntry),$this->messages());

        $accountingEntry->update($request->all());

        return Responder::setData(new AccountingEntryResource($accountingEntry))->setMessage(trans('forms.updated'))->setStatus(201)->respond();
    }


    protected function rules($is_update = false,$accountingEntry = null){

        $rules =  [
            'name'=>'required|unique:accounting_entries,name',
            'desc'=>'nullable|max:1000',


        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|unique:accounting_entries,name,'.$accountingEntry->id,
                'desc'=>'nullable|max:1000',
            ]);
        }
        return $rules;


    }

    protected function messages(){

        return  [


            'name.required'=> App::isLocale('en') ? 'Name is Required':'يجب ادخال الاسم',
            'name.unique'=> App::isLocale('en') ? 'Name is already exists':'الاسم مودجود مسبقا',

            // 'desc.required'=> App::isLocale('en') ? 'Description is Required':'يجب ادخال المحتوي',
            'desc.max'=> App::isLocale('en') ? 'Description is Should be less Than 500':'يجب الا يزيدالمحتوي عن 500',


        ];
    }
}
