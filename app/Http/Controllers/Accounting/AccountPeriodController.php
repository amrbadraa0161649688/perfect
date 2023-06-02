<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\currency;
use App\Models\Master\AccountPeriod;
use Facades\App\Classes\Responder;
use App\Http\Resources\Accounting\AccountPeriodResource;
use App\Filters\AccountPeriod\IndexFilter;
use App;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $periods = AccountPeriod::filter(new IndexFilter(request()))
            ->with('rates.currency')
            ->orderBy('year','DESC')
            ->orderBy('month','DESC')
            ->get();

         return response(AccountPeriodResource::collection($periods));
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
        $this->validate($request,$this->rules(true),$this->messages());
        DB::transaction(function () use($request){
            $data['year']=$request->year;
            $data['month']=$request->month;
            $data['status']= $request->status;
            $data['is_active'] = $request->input('is_active');
            $accountPeriod = AccountPeriod::create($data);
            $rates = collect($request->input('rates'))->unique('currency_id')->map(function($item){
                return [
                    'currency_id'=>$item['currency_id'],
                    'rate'=>$item['rate']
                ];
            });
            $accountPeriod->rates()->createMany($rates->all());
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

        $period = AccountPeriod::find($id);
        if(is_null($period)){
            return response('',404);
        }
        return response(new AccountPeriodResource($period));

    }

    public function getActive(){
        $periods = AccountPeriod::where('is_active',1)
            ->orderBy('year','ASC')
            ->orderBy('month','ASC')
            ->get();
        $data = $periods->map(function($item){
            return [
                'id'=>$item->id,
                'year'=>$item->year,
                'month'=>$item->month,
                'name'=>"{$item->year}/{$item->month}"
            ];
        });
         return response($data);
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
        $period = AccountPeriod::where('id',$id)->first();
        // dd(auth::user());
        $this->validate($request,$this->rules(true,$period),$this->messages());
        if(!$period){
            return response(trans('forms.notExistperiod'),404);
        }
        DB::transaction(function () use($request,$period){
            $data['year']=$request->year;
            $data['month']=$request->month;
            $data['status']= $request->status;
            $data['is_active'] = $request->input('is_active');
            $period->update($data);
            $rates = collect($request->input('rates'))->unique('currency_id');
            $rates->each(function($item)use($period){
                if(is_null($item['id'])){
                    $period->rates()->create([
                        'currency_id'=>$item['currency_id'],
                        'rate'=>$item['rate']
                    ]);
                }else{
                    $period->rates()->where('id',$item['id'])->update(
                        [
                            'currency_id'=>$item['currency_id'],
                            'rate'=>$item['rate']
                        ]
                    );
                }
            });
           // $accountPeriod->rates()->createMany($rates->all());
        });

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

    protected function rules($is_update = false,$model = null){

        $rules =  [
            'year'=>[
                'required','unique'=>Rule::unique('periods')->where(function ($query){
                    return $query->where('month',request()->input('month'));
                })
            ],
            'month'=>[
                'required','unique'=>Rule::unique('periods')->where(function ($query){
                    return $query->where('year',request()->input('year'));
                })
            ],
            'status'=>'required',
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'year'=>[
                    'required','unique'=>Rule::unique('periods')->where(function ($query)use($model){
                        return $query->where('month',request()->input('month'));
                    })->ignore($model)
                ],
                'month'=>[
                    'required','unique'=>Rule::unique('periods')->where(function ($query)use($model){
                        return $query->where('year',request()->input('year'));
                    })->ignore($model)
                ]
            ]);
        }
        return $rules;


    }

    public function messages(){
        return[
            'year.required'=> App::isLocale('en') ? 'Year is required':'يجب ادخال السنة ',
            'year.digits'=> App::isLocale('en') ? 'Year should be only 4 digits':'السنة يجب ان تكون 4 ارقام',
            'year.integer'=> App::isLocale('en') ? 'Year should be integer':'السنة يجب ان تكون رقم صحيح',
            'month.required'=> App::isLocale('en') ? 'Month is required':'يجب ادخال الشهر ',
            'month.integer'=> App::isLocale('en') ? 'Month should be integer':'الشهر يجب ان يكون رقم صحيح',
            'month.min'=> App::isLocale('en') ? 'Minimum Month number is 1':'اقل شهر هو 1',
            'month.max'=> App::isLocale('en') ? 'Maximum Month number is 12':'اكبر شهر هو 12',
            'status_id.required'=> App::isLocale('en') ? 'Status is required':'يجب ادخال الحالة ',
            'currency_id.required'=> App::isLocale('en') ? 'Currency is required':'يجب ادخال العملة ',
            'price.required'=> App::isLocale('en') ? 'Price is required':'يجب ادخال السعر ',
            'price.numric'=> App::isLocale('en') ? 'Price should be numbers only':'يجب ادخال السعر ارقام فقط',





        ];
    }

}
