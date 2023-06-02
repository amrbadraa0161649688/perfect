<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App;
use Facades\App\Classes\Responder;

use App\Models\Master\CostCenter;
//
use App\Http\Resources\Accounting\CostCenterResource;
use App\Filters\Account\IndexFilter;
use Illuminate\Support\Facades\DB;

class CostCenterController extends Controller
{
    public function index(){

        $pageination = CostCenter::filter(new IndexFilter(request()))
        ->with(['accounts'])->paginate(30);
        return $this->getPaginationData($pageination,CostCenter::class);
    }

    public function show($id){


        $data = CostCenter::find($id);

        if(is_null($data)){
            return response(trans('data.notExist-costCenter'),404);
        }

        $res = new  CostCenterResource($data);
        return response($res);
    }

    public function store(Request $request){


        $this->validate($request,$this->rules(),$this->messages());
        DB::transaction(function () use($request){
            $costCenter = CostCenter::create($request->all());
            $costCenter->accounts()->sync($request->input('accounts',[]));
        });

        return response(trans('forms.created'),201);
    }

    public function update(Request $request,$id){

        $costCenter=CostCenter::where('id',$id)->first();

        if(is_null($costCenter)){
            return response(trans('data.notExist-costCenter'),404);
        }

        $this->validate($request,$this->rules(true,$costCenter),$this->messages());
        DB::transaction(function () use($request,$costCenter){
            $costCenter->update($request->all());
            $costCenter->accounts()->sync($request->input('accounts',[]));
        });


        return response(trans('forms.updated'),204)
        ;
    }


    protected function rules($is_update = false,$account = null){

        $rules =  [
            'name'=>'required',
            'code'=>'required|unique:cost_centers,code',
            'accounts'=>'required|exists:accounts,id',
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'code'=>'required|unique:cost_centers,code,'.$account->id,

            ]);
        }
        return $rules;


    }


    public function messages(){

        return  [
            'code.required'=> App::isLocale('en') ? 'Cost Center code is Required':'يجب ادخال الكود',
            'code.unique'=> App::isLocale('en') ? 'Code is already exists':'الكود مودجود مسبقا',

            'name.required'=> App::isLocale('en') ? 'Cost Center Name is Required':'يجب ادخال الاسم',

            'accounts.required'=> App::isLocale('en') ? 'Accounts is Required':'يجب اختيارالحسابات',
            'accounts.exists'=> App::isLocale('en') ? 'Accounts is Invaild':'  الحسابات غير صحيحة',
        ];
    }
}
