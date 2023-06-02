<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationsRequest;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Resources\Api\LiteListResource;
use App\Http\Resources\Api\LocationResource;
use App\Models\Locations;
use App\Models\SystemCode;
use App\Repositories\Eloquent\LocationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LocationsController extends Controller
{
    protected $repo;

    public function __construct(LocationRepository $repo)
    {
        $this->repo = $repo;
    }


    public function index(PaginateRequest $request)
    {
        $input = $this->repo->inputs($request->all());
        $model = new Locations();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->repo->whereOptions($input, $columns);

        }
        $data = $this->repo->Paginate($input, $wheres);

        return responseSuccess([
            'data' => LocationResource::collection($data),
            'meta' => [
                'total' => $data->count(),
                'currentPage' => $input["offset"],
                'lastPage' => $input["paginate"] != "false" ? $data->lastPage() : 1,
            ],
        ]);
    }

    public function create()
    {
        $cities = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', auth()->user()->company_group_id)->get();

        $data = [
            'cities' => LiteListResource::collection($cities),
        ];
        return responseSuccess($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Api\LocationsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LocationsRequest $request)
    {
        try {
            $user = auth()->user();
            $this->repo->create($request->validated() + [
                    'company_group_id' => $user->company_group_id ?? 14,
                    'company_id' => $user->company_id ?? 32,
                    'user_id' => $user->user_id,
                ]);
            return responseSuccess([], __('messages.added_successfully'));
        } catch (\Exception $e) {
            Log::error('location store', [$e]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    public function update(LocationsRequest $request, $id)
    {
        try {
            $record = $this->repo->findOrFail($id);
            $this->repo->update($request->validated(), $record);
            return responseSuccess([], __('messages.updated_successfully'));
        } catch (\Exception $e) {
            Log::error('location update', [$e]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    public function show($id)
    {
        $record = $this->repo->findOrFail($id);
        return responseSuccess(LocationResource::make($record));
    }
}
