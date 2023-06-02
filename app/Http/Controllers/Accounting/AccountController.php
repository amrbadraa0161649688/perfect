<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\App\Classes\Responder;
use App\Models\Master\Account;
use App\Http\Resources\Accounting\AccountResource;
use App\Http\Resources\Accounting\TreeAccountResource;
use App\Filters\Account\IndexFilter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function index(){
        $pageination = Account::filter(new IndexFilter(request()))
        ->with('mainType','parent')
        ->orderBy('code','ASC')
        ->paginate(30);
        $requestData = collect($pageination->items())->map(function($item, $index) use ($pageination){
            $page = $pageination->currentPage() - 1;
            $count =  ( $page * $pageination->perPage() ) + ($index +1) ;
            return [
                'page_count'=>$count,
                'id'=> $item->id,
                'name'=>$item->name ?? '',
                'code'=>$item->code ?? '',
                'level' => intval($item->level),
                'nature'=>$item->nature ?? '',
                'nature_name'=>trans('account.'.$item->nature),
                'main_type_id'=>$item->main_type_id ?? '',
                'main_type_name'=>optional($item->mainType)->name ?? '',
                'parent_id'=>$item->parent_id,
                'parent_name'=>optional($item->parent)->name ?? '',
                'appearance'=>$item->appearance ?? '',
                'appearance_name'=>$item->appearance == null ? '' : trans('account.'.$item->appearance),

            ];
        });
        $rsponse  = [
            'items' => $requestData,
            'lastPage'=> $pageination->lastPage(),
            'currentPage'=> $pageination->currentPage(),
            'onFirstPage' => $pageination->onFirstPage(),
            'onLastPage' => $pageination->hasMorePages(),
            'links' => $pageination->getOptions(),
            'hasPages'=>$pageination->hasPages(),
            'totalRecords'=>$pageination->total(),
            'p'=> $pageination

        ];
        return response($rsponse);

    }

    public function search(){
        $value = request()->input('q');
        if(is_null($value)){
            return [];
        }
        $data = Account::where('name','like',"%{$value}%")
        ->orWhere('code','like',"%{$value}%")
        ->get(['name','id','code'])->map(function($item){
            return [
                'label'=>$item->getAccountCodeName(),
                'value'=>$item->id
            ];
        });
        return response($data);

    }

    public function searchLastLevel(){
        $value = request()->input('q');
        if(is_null($value)){
            return [];
        }
        $maxLevel = Account::max('level');
        $data = Account::where('level',$maxLevel)
        ->whereNotNull('parent_id')
        ->where(function($q) use($value){
            return $q->where('name','like',"%{$value}%")
            ->orWhere('code','like',"%{$value}%");
        })->get(['name','id','code'])->map(function($item){
            return [
                'label'=>$item->getAccountCodeName(),
                'value'=>$item->id,
                'costCenters'=>$item->costCenters->map(function($costItem){
                    return [
                        'name'=>$costItem->name,
                        'id'=>$costItem->id
                    ];
                })
            ];
        });
        return response($data);
    }

    public function getAssetsAccount(){
        $maxLevel = Account::max('level');
        $assetId = Account::where('search_code','A')->value('id');
        $accounts = Account::where('level',$maxLevel)->where('main_type_id',$assetId)->get()->map(function($item){
            return [
                'id'=>$item->id,
                'code_name'=>$item->getAccountCodeName(),
            ];
        });
        return response($accounts);
    }


    public function show($id){


        $data = Account::find($id);
        if(is_null($data)){
             return response(trans('data.notExist-account'),404);
        }

        $res = new  AccountResource($data);
        return response($res);
    }


    public function store(Request $request){

        $this->validate($request,$this->rules(),$this->messages());
        $data = $request->only([
            'code','name','main_type_id','parent_id','nature','appearance'
        ]);
        $data['level'] = 1;
        if(!is_null($request->parent_id)){
            $data['level'] = $this->getLevel($request->parent_id);
        }
        DB::transaction(function () use($data){
            $account = Account::create($data);
            if($account->level == 1){
                $account->update(['main_type_id'=>$account->id]);
            }
        });

        return response(trans('forms.created'),201);
    }


    public function update(Request $request,$id){


        $account=Account::where('id',$id)->first();

        if(is_null($account)){
             return response(trans('data.notExist-account'),404);
        }


        // if( $account->childs()->count() > 0){
        //     if($account->nature !=$request->nature){
        //         return response(trans('data.notnatureupdated-account'),404);
        //     }
        //     if($account->main_type_id !=$request->main_type_id){
        //         return response(trans('data.notmain_type_idupdated-account'),404);
        //     }
        //     if($account->parent_id !=$request->parent_id){
        //         return response(trans('data.notparent_idupdated-account'),404);
        //     }
        // }

        $this->validate($request,$this->rules(true,$account),$this->messages());
        $data = $request->only([
            'code','name','main_type_id','parent_id','nature','appearance'
        ]);
        $data['level'] = 1;
        if(!is_null($request->parent_id)){
            $data['level'] = $this->getLevel($request->parent_id);
        }
        DB::transaction(function () use($data,$account){
            $account->update($data);
            if($account->level == 1){
                $account->update(['main_type_id'=>$account->id]);
            }
        });

        return response(trans('forms.updated'),204);
    }

    protected function getLevel($id){
        $accountLevel = Account::find($id);
        if(is_null($accountLevel)){
            return 1;
        }
        return $accountLevel->level +1;
    }


    public function getParentAccount(){

        // $account = ParentAccountResource::collection(
        //                 Account::where('main_type_id',\request()->main_type)
        //                     ->filter(new IndexFilter(request()))->get()
        //             );

        $accounts = Account::get()
        ->map(function($item){
            return [
                'id'=>$item->id,
                'code_name'=>$item->code.' '.$item->name,
                'name'=>$item->name,
                'code'=>$item->code,
                'parent_id'=>$item->parent_id,
                'main_type_id'=>$item->main_type_id
            ];
        });

        return response($accounts);

    }

    public function getMainTypeByNature(){
        $accounts = Account::whereNull('parent_id')
        ->orderBy('nature','DESC')
        ->get(['id','name','nature'])->map(function($item){
            return [
                'id'=>$item->id,
                'name'=>$item->name,
                'nature'=>$item->nature,
                'nature_name'=>trans('account.'.$item->nature)
            ];
        });

        return response($accounts);

    }

    public function getTreeAccounts(){

        $account = TreeAccountResource::collection(
                        Account::allMainAccounts()
                    );

        return Responder::setData($account)->respond();

    }


    protected function rules($is_update = false,$account = null){

        $rules =  [
            'code'=>'required|unique:accounts,code',
            'name'=>'required',
            'nature'=>'required|in:c,d',
            'appearance'=>'nullable|in:i,b',
           // 'main_type_id'=>'required',
            'parent_id'=>'nullable|exists:accounts,id',

        ];
        if($is_update){
            $rules = array_merge($rules, [
                'code'=>'required|unique:accounts,code,'.$account->id,

            ]);
        }
        return $rules;


    }


    public function messages(){

        return  [
            'code.required'=> App::isLocale('en') ? 'Account code is Required':'يجب ادخال الكود',
            'code.unique'=> App::isLocale('en') ? 'Code is already exists':'الكود مودجود مسبقا',

            'name.required'=> App::isLocale('en') ? 'Account Name is Required':'يجب ادخال الاسم',

            'nature.required'=> App::isLocale('en') ? 'Account Nature is Required':'يجب اختيار طبيعة الحساب',
            'nature.in'=> App::isLocale('en') ? 'Account Nature is Invaild':'طبيعة الحساب غير صحيحة',

            'appearance.required'=> App::isLocale('en') ? 'Account Appearance is Required':'يجب اختيار الظهور',
            'appearance.in'=> App::isLocale('en') ? 'Account appearance is Invaild':'الظهور غير صحيحة',

            'main_type_id.required'=> App::isLocale('en') ? 'Main Type is Required':'يجب اختيار النوع الرئيسي',
            'main_type_id.exists'=> App::isLocale('en') ? 'Main Type is Invaild':' النوع الرئيسي غير صحيحة',

            'required.required'=> App::isLocale('en') ? 'Parent Account is Required':'يجب اختيار الحساب ',
            'parent_id.exists'=> App::isLocale('en') ? 'Parent Account is Invaild':' الحساب غير صحيحة',

        ];
    }

}
