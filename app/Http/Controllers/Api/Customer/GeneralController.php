<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationsRequest;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\RateRequest;
use App\Http\Requests\Api\WayBillRequest;
use App\Http\Resources\Api\LiteListAttachmentResource;
use App\Http\Resources\Api\LiteListResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\WayBillResource;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Locations;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\SystemCode;
use App\Models\WaybillHd;
use App\Repositories\Eloquent\WayBillRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GeneralController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function questions(): JsonResponse
    {
        $user = auth()->user();
        $records = Attachment::where('attachment_type', 11014)->get(); // ->where('transaction_id', config('mobile_company_id'))
        return responseSuccess(LiteListAttachmentResource::collection($records));
    }

    /**
     * @return JsonResponse
     */
    public function terms(): JsonResponse
    {
        $user = auth()->user();
        $records = Attachment::where('attachment_type', 11015)->get(); // ->where('transaction_id', config('mobile_company_id'))
        return responseSuccess(LiteListAttachmentResource::collection($records));
    }

    /**
     * @return JsonResponse
     */
    public function slider(): JsonResponse
    {
        $user = auth()->user();
        $records = Attachment::where('attachment_type', 110016)->get();
        $slider = [];
        foreach ($records as $record){
            $slider[] = $record->attachment_file;
        }
             // ->where('transaction_id', config('mobile_company_id'))
        return responseSuccess($slider);
    }


}
