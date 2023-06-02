<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Classes\Responder;
use App\Models\Master\Bank;
use App\Models\Master\Company;
use App\Models\Master\Branch;
use App\Models\Master\Subsidiary;
use App\Models\Master\Currency;
use App\Models\Master\Treasury;
use App\Models\Master\Account;

use App\Http\Resources\Accounting\TreasuryResource;
use App\Http\Resources\Accounting\AccountResource;

use App;


class TreasuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $treasuries = Treasury::get();

        return response(TreasuryResource::collection($treasuries));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,$this->rules(),$this->messages());

        $data['company_id']= $request->company_id;
        $data['subsidiary_id']= $request->subsidiary_id;
        $data['branch_id']= $request->branch_id;
        $data['currency_id']= $request->currency_id;
        $data['account_id']= $request->account_id;

        Treasury::create($data);

        return response(trans('forms.created'),201);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Treasury $treasury)
    {
        $data = [
            'company_id'=>$treasury->company_id ?? '',
            'branch_id'=>$treasury->branch_id ?? '',
            'currency_id'=>$treasury->currency_id ?? '',
            'account_id'=>$treasury->account_id ?? '',
            'subsidiary_id'=>$treasury->subsidiary_id ?? '',
            'branch_name'=>optional($treasury->branch)->name ?? '',
            'currency_name'=>optional($treasury->currency)->name ?? '',
            'account_name'=>optional($treasury->account)->getAccountCodeName() ?? '',
            'subsidiary_name'=>optional($treasury->subsidiary)->name ?? '',

        ];
        return response($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Treasury $treasury)
    {

        return Responder::setData(new TreasuryResource($treasury))->setStatus(201)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $treasury=Treasury::find($id);

        if(is_null($treasury)){
          return response(trans('forms.notExistTreasury'),404);
        }

        $this->validate($request,$this->rules(true,$treasury),$this->messages());

        //$data['company_id']= $request->company_id;
        $data['subsidiary_id']= $request->subsidiary_id;
        $data['branch_id']= $request->branch_id;
        $data['currency_id']= $request->currency_id;
        $data['account_id']= $request->account_id;

        $treasury->update($data);

        return response(trans('forms.updated'),204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function assetsAccount()
    {
        $assets = Account::where('main_type_id',1)->get();

        return Responder::setData(AccountResource::collection($assets))->setStatus(200)->respond();

    }

    protected function rules($is_update = false,$bank = null){

        $rules =  [
            'company_id'=>'required',
           // 'subsidiary_id'=>'required',
            'branch_id'=>'required',
            'currency_id'=>'required',
            'account_id'=>'required',

        ];

        return $rules;


    }

    public function messages(){
        return[
            'company_id.required'=> App::isLocale('en') ? 'Company is required':' ادخال رقم الشركة ',
            'subsidiary_id.required'=> App::isLocale('en') ? 'Subsidiary is required':'يجب ادخال الشركة الفرعية',
            'branch_id.required'=> App::isLocale('en') ? 'Branch is required':'يجب ادخال الفرع',
            'currency_id.required'=> App::isLocale('en') ? 'Currency is required':'يجب ادخال العملة',
            'account_id.required'=> App::isLocale('en') ? 'Account is required':'يجب ادخال الحساب لربط بالشجرة',


        ];
    }
}
