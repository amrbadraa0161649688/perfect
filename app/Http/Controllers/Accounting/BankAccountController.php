<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Bank;
use App\Models\Master\BankAccount;
use Facades\App\Classes\Responder;

use App\Http\Resources\Accounting\BankAccountResource;

use App;


class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $bankAccounts = BankAccountResource::collection(BankAccount::with(['bank','currency','account'])->get());

        return response($bankAccounts);
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

        $data['account_no']=$request->account_no;
        $data['currency_id']=$request->currency_id;
        $data['bank_id']=$request->bank_id;
        $data['branch']=$request->branch_name;
        $data['account_id'] = $request->input('account_id');
        BankAccount::create($data);
        return response(trans('forms.created'),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        if(is_null($bankAccount)){
            return response(trans('data.notExist-account'),404);
        }
        return response(new BankAccountResource($bankAccount));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $bankAccount=BankAccount::where('id',$id)->first();

        if(!$bankAccount){
          return response(trans('forms.notExistBankAccount'),404);
        }
        $this->validate($request,$this->rules(true,$bankAccount),$this->messages());

        $data['account_no']=$request->account_no;
        $data['currency_id']=$request->currency_id;
        $data['bank_id']=$request->bank_id;
        $data['branch']=$request->input('branch_name');
        $data['account_id'] = $request->input('account_id');
        $bankAccount->update($data);
        return Responder::setMessage(trans('forms.updated'))->setStatus(201)->respond();
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

    protected function rules($is_update = false,$bankAccount = null){

        $rules =  [
            'account_no'=>'required|unique:bank_accounts,account_no',
            'currency_id'=>'required',
            'bank_id'=>'required',
            'branch_name'=>'required',

        ];
        if($is_update){
            $rules = array_merge($rules, [
                'account_no'=>'required|unique:bank_accounts,account_no,'.$bankAccount->id,

            ]);
        }
        return $rules;


    }

    public function messages(){
        return[
            'account_no.required'=> App::isLocale('en') ? 'Account number is required':'يجب ادخال رقم الحساب',
            'account_no.unique'=> App::isLocale('en') ? 'Account number is already exists':'رقم الحساب موجود مسبقا',
            'currency_id.required'=> App::isLocale('en') ? 'Currency is required':' ادخال رقم العملة ',
            'bank_id.required'=> App::isLocale('en') ? 'Bank is required':'يجب ادخال البنك',
            'branch.required'=> App::isLocale('en') ? 'Branch is required':'يجب ادخال الفرع',


        ];
    }
}
