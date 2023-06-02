<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Bank;
use Facades\App\Classes\Responder;

use App\Http\Resources\Accounting\BankResource;

use App;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $banks = Bank::orderBy('ar_name','DESC')
                       ->get();

        return Responder::setData(BankResource::collection($banks))->setStatus(200)->respond();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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

        $data['ar_name']=$request->ar_name;
        $data['en_name']=$request->en_name;
        $data['prefix']=$request->prefix;

        $bank = Bank::create($data);

        return Responder::setMessage(trans('forms.created'))->setStatus(201)->respond();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {

        return Responder::setData(new BankResource($bank))->setStatus(200)->respond();

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
        $bank=Bank::where('id',$id)->first();

        if(!$bank){
          return Responder::setMessage(trans('forms.notExistBank'))->setStatus(201)->respond();
        }
        $this->validate($request,$this->rules(true,$bank),$this->messages());
        $data['ar_name']=$request->ar_name;
        $data['en_name']=$request->en_name;
        $data['prefix']=$request->prefix;

        $bank->update($data);
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
    protected function rules($is_update = false,$bank = null){
       
        $rules =  [
            'ar_name'=>'required',
            'en_name'=>'required',
            'prefix'=>'required',

        ];
     
        return $rules;
        
        
    }

    public function messages(){
        return[
            'ar_name.required'=> App::isLocale('en') ? 'Arabic name is required':' ادخال رقم العملة ',
            'en_name.required'=> App::isLocale('en') ? 'English name is required':'يجب ادخال البنك',
            'prefix.required'=> App::isLocale('en') ? 'Prefix is required':'يجب ادخال الفرع',


        ];
    }
}
