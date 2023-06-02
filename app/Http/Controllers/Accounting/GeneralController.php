<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Facades\App\Classes\Responder;
//
use App\Permission;
use App\Models\Master\Country;
use App\Models\Master\Governorate;
use App\Models\Master\City;

// use App\Models\Master\RequestStatus;
use App\Models\Master\CustomerType;
use App\Models\Master\MainType;
use App\Models\Master\EntryStatus;
use App\Models\Master\ItemUnit;
use App\Models\Master\Currency;
use App\Http\Resources\Master\PermissionResource;
use App\Http\Resources\Master\CountryResource;
use App\Http\Resources\Master\GovernorateResource;
use App\Http\Resources\Master\CityResource;
// use App\Http\Resources\Master\RequestStatusResource;
use App\Http\Resources\Master\CustomerTypeResource;
use App\Http\Resources\Master\MainTypeResource;
use App\Http\Resources\Master\EntryStatusResource;
use App\Http\Resources\Master\ItemUnitResource;
use App\Http\Resources\Master\CurrencyResource;


class GeneralController extends Controller
{

    public function permissions(){


        $permissions=Permission::orderBy('description')->get()->groupBy('description');
        $result = [
            'items'=>[]
        ];
        $count = 0;
        foreach($permissions as $key=>$permission){
            $data = [];
            $data['name'] = $permission[0]->description;
            $data['key'] =  Str::snake($key);
            $data['items'] = $permission;
            $count += count($permission);
            $result['items'][] = $data;
        }
        $result['count'] = $count;


        return Responder::setData($result)->setStatus(200)->respond();

    }

    public function countries(){
        $country=Country::all();
        return Responder::setData(CountryResource::collection($country))->setStatus(200)->respond();
    }



    // public function requestStatus(){
    //     $requestStatus=RequestStatus::all();
    //     return Responder::setData(RequestStatusResource::collection($requestStatus))->setStatus(200)->respond();
    // }


    public function governorates()
    {
        $governorates = Governorate::orderBy('name_ar')
                        ->where('is_active',true)
                        ->with('cities')
                        ->get();
        return Responder::setData(GovernorateResource::collection($governorates))->setStatus(200)->respond();

    }

    public function cities()
    {
        $cities = City::orderBy('name_ar')
                        ->where('is_active',true);
        $govId = request()->input('gov_id');
        if(!is_null($govId)){
            $cities = $cities->where('gov_id',$govId);
        }
        $cities = $cities->get();
        return response(CityResource::collection($cities));
        // return Responder::setData($cities)->setStatus(200)->respond();

    }


    public function customerType(){
        $customerType=CustomerType::all();
        return Responder::setData(CustomerTypeResource::collection($customerType))->respond();
    }

    public function mainType(){
        $mainType=MainType::all();
        return Responder::setData(MainTypeResource::collection($mainType))->respond();
    }

    public function entryStatus(){
        $entryStatus=EntryStatus::all();
        return Responder::setData(EntryStatusResource::collection($entryStatus))->respond();
    }

    public function itemUnit(){
        $itemUnit=ItemUnit::all();
        return response(ItemUnitResource::collection($itemUnit));
    }

    public function currencies(){
        $currencies=Currency::where('is_active',true);
        if(!is_null(request()->input('code'))){
            $currencies->where('code',request()->input('code'));
        }
        return response(CurrencyResource::collection($currencies->get()));
    }
}
