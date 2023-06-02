<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\OpeningBalance;
use Facades\App\Classes\Responder;
use App\Http\Resources\Accounting\OpeningBalanceResource;
use App\Filters\OpeningBalance\IndexFilter;

use App;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OpeningBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pageination = OpeningBalance::filter(new IndexFilter(request()))
        ->with(['account'])
        ->orderBy('year','DESC')
        ->paginate(30);

        return $this->getPaginationData($pageination,OpeningBalance::class);
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
        //$this->validate($request,$this->rules(true),$this->messages());
        $balances = collect($request->input('balances',[]));
        DB::transaction(function () use($balances){
            $balances->each(function($item){
                OpeningBalance::create([
                    'year'=>$item['year'],
                    'account_id'=>$item['account_id'],
                    'debtor_funds'=>$item['debit'],
                    'creditor_funds'=>$item['credit'],
                ]);
            });
        });
        return response(trans('forms.created'),201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $balance = OpeningBalance::find($id);
        if(is_null($balance)){
            return response('Data Not Found',404);
        }
        return response([
            'id'=>$balance->id,
            'year'=>$balance->year,
            'account_id'=>$balance->account_id,
            'account_name'=>optional($balance->account)->getAccountCodeName() ?? '',
            'debit'=>$balance->debtor_funds ?? 0,
            'credit'=>$balance->creditor_funds ?? 0,
            

        ]);
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
    public function update(Request $request, $id)
    {

        $balance = OpeningBalance::find($id);


        if(!$balance){
            return response(trans('forms.notExistOpening'),404);
        }

        $this->validate($request,$this->rules(true,$balance),$this->messages());
        $balance->update([
            'year'=>$request->input('year'),
            'account_id'=>$request->input('account_id'),
            'debtor_funds'=>$request->input('debit',0),
            'creditor_funds'=>$request->input('credit',0),
        ]);
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

    protected function rules($is_update = false,$branch = null){

        $rules =  [
            'year'=>['required','digits:4','integer','min:'.Carbon::now()->year],
            'account_id'=>'required',
            // 'debtor_funds'=>['required_without:creditor_funds'],
            // 'creditor_funds'=>['required_without:debtor_funds'],

        ];

        return $rules;


    }

    public function messages(){
        return[
            'year.required'=> App::isLocale('en') ? 'Year is required':'يجب ادخال السنة ',
            'year.digits'=> App::isLocale('en') ? 'Year should be only 4 digits':'السنة يجب ان تكون 4 ارقام',
            'year.integer'=> App::isLocale('en') ? 'Year should be integer':'السنة يجب ان تكون رقم صحيح',
            'year.min'=> App::isLocale('en') ? 'Minimum year is '.Carbon::now()->year:' الحد الادني لسنة هو'.Carbon::now()->year,
            'account_id.required'=> App::isLocale('en') ? 'Account is required':'يجب ادخال الحساب',


        ];
    }
}
