@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="section-body mt-3" id="app">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <a href="{{route('movements.index')}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6>{{__('home.all_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$records->total()}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <a href="{{route('movements.index').'?car_movement_end=close'}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6>{{__('home.movement_close_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$movement_close_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <a href="{{route('movements.index').'?car_movement_end=open'}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6>{{__('home.movement_open_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$movement_open_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        @include('Includes.form-errors')
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="col-md-12">
                                <div class="row">
                                    {{--car_movement_code--}}
                                    {{--                                    <div class="col-md-3  mb-3">--}}
                                    {{--                                        <label>@lang('home.car_movement_code')</label>--}}
                                    {{--                                        <input type="text" class="form-control" name="car_movement_code"--}}
                                    {{--                                               value="{{ request()->car_movement_code ? request()->car_movement_code : '' }}"--}}
                                    {{--                                               placeholder="@lang('home.car_movement_code')">--}}
                                    {{--                                    </div>--}}
                                    {{--driver_name--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.car_movement_driver_id')</label>
                                        <select class="selectpicker" multiple data-live-search="true" data-actions-box="true"
                                                name="car_movement_driver_id[]">
                                            @foreach($drivers as $driver)
                                                <option value="{{$driver->emp_id}}"
                                                        @if(request()->car_movement_driver_id) @foreach(request()->car_movement_driver_id as
                                                     $car_movement_driver_id) @if($driver->emp_id == $car_movement_driver_id) selected @endif @endforeach @endif>
                                                    {{$driver->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--driver_identify--}}
                                    {{--                                    <div class="col-md-3  mb-3">--}}
                                    {{--                                        <label>@lang('home.driver_identity')</label>--}}
                                    {{--                                        <input type="text" class="form-control" name="driver_identity"--}}
                                    {{--                                               value="{{ request()->driver_identity ? request()->driver_identity : '' }}"--}}
                                    {{--                                               placeholder="@lang('home.driver_identity')">--}}
                                    {{--                                    </div>--}}
                                    {{--car_model--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('carrent.car_model')</label>
                                        <select class="selectpicker" multiple data-live-search="true" data-actions-box="true"
                                                name="brand_ids[]">
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->brand_id}}"
                                                        @if(request()->brand_ids) @foreach(request()->brand_ids as
                                                     $brand_id) @if($brand->brand_id == $brand_id) selected @endif @endforeach @endif>
                                                    {{$brand->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--car_model_year--}}
                                    {{--                                    <div class="col-md-4  mb-3">--}}
                                    {{--                                        <label>@lang('carrent.car_model_year')</label>--}}
                                    {{--                                        <input type="text" class="form-control" name="car_model_year"--}}
                                    {{--                                               value="{{ request()->car_model_year ? request()->car_model_year : '' }}"--}}
                                    {{--                                               placeholder="@lang('carrent.car_model_year')">--}}
                                    {{--                                    </div>--}}
                                    {{--car_movement_type_id--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.car_movement_type_id')</label>
                                        <select class="selectpicker" multiple data-live-search="true" data-actions-box="true"
                                                name="car_movement_type_id[]">
                                            @foreach($types as $type)
                                                <option value="{{$type->system_code_id}}"
                                                        @if(request()->car_movement_type_id) @foreach(request()->car_movement_type_id as
                                                     $car_movement_type) @if($type->system_code_id == $car_movement_type) selected @endif @endforeach @endif>
                                                    {{$type->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--car_plate--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('carrent.car_plate')</label>
                                        <input type="text" class="form-control" name="car_plate"
                                               value="{{ request()->car_plate ? request()->car_plate : '' }}"
                                               placeholder="@lang('carrent.car_plate')">
                                    </div>
                                    {{--from_car_movement_start--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.from_car_movement_start')</label>
                                        <input type="date" class="form-control" name="from_car_movement_start"
                                               value="{{ request()->from_car_movement_start ? request()->from_car_movement_start : '' }}"
                                               placeholder="@lang('home.from_car_movement_start')">
                                    </div>
                                    {{--to_car_movement_start--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.to_car_movement_start')</label>
                                        <input type="date" class="form-control" name="to_car_movement_start"
                                               value="{{ request()->to_car_movement_start ? request()->to_car_movement_start : '' }}"
                                               placeholder="@lang('home.to_car_movement_start')">
                                    </div>
                                    {{--from_car_movement_end--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.from_car_movement_end')</label>
                                        <input type="date" class="form-control" name="from_car_movement_end"
                                               value="{{ request()->from_car_movement_end ? request()->from_car_movement_end : '' }}"
                                               placeholder="@lang('home.from_car_movement_end')">
                                    </div>
                                    {{--to_car_movement_end--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.to_car_movement_end')</label>
                                        <input type="date" class="form-control" name="to_car_movement_end"
                                               value="{{ request()->to_car_movement_end ? request()->to_car_movement_end : '' }}"
                                               placeholder="@lang('home.to_car_movement_end')">
                                    </div>
                                    {{--car_movement_branch_open--}}
                                    <div class="col-md-3  mb-3">
                                        {{-- branches  --}}
                                        <label>@lang('home.car_movement_branch_open')</label>
                                        <select class="selectpicker" multiple data-live-search="true" data-actions-box="true"
                                                name="car_movement_branch_opens[]">
                                            @foreach($branches as $branch)
                                                <option @if(request()->car_movement_branch_open) @foreach(request()->car_movement_branch_open as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                        @endif @endforeach @endif value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
                                                     $branch->branch_name_ar : $branch->branch_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--car_movement_branch_close--}}
                                    <div class="col-md-3  mb-3">
                                        {{-- branches  --}}
                                        <label>@lang('home.car_movement_branch_close')</label>
                                        <select class="selectpicker" multiple data-live-search="true" data-actions-box="true"
                                                name="car_movement_branch_close[]">
                                            @foreach($branches as $branch)
                                                <option @if(request()->car_movement_branch_close) @foreach(request()->car_movement_branch_close as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                        @endif @endforeach @endif value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
                                                     $branch->branch_name_ar : $branch->branch_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                            <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('movements.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_movement')
                            </a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7">
                                    <th class="sorting" style=" width: 10px; "></th>
                                    <th>@lang('home.car_movement_code')</th>
                                    <th>@lang('home.car_movement_type_id')</th>
                                    <th>@lang('carrent.car_model')</th>
                                    <th>@lang('carrent.car_color')</th>
                                    <th>@lang('carrent.car_plate')</th>
                                    <th>@lang('home.driver_name')</th>
                                    <th>@lang('home.car_movement_branch_open')</th>
                                    <th>@lang('home.car_movement_start')</th>
                                    <th>@lang('home.car_movement_branch_close')</th>
                                    <th>@lang('home.car_movement_end')</th>
                                    <th>@lang('home.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$record->car_movement_code}}</td>
                                        <td>{{$record->type?$record->type->name:''}}</td>
                                        <td>{{$record->car && $record->car->brand ?$record->car->brand->name:''}}</td>
                                        <td>{{$record->car?$record->car->car_color:''}}</td>
                                        <td>{{$record->car?$record->car->full_car_plate:''}}</td>
                                        <td>{{$record->driver?$record->driver->name:''}}</td>
                                        <td>{{$record->branchOpen?$record->branchOpen->name:''}}</td>
                                        <td>{{$record->car_movement_start}}</td>
                                        <td>{{$record->branchClose?$record->branchClose->name:''}}</td>
                                        <td>{{$record->car_movement_end}}</td>
                                        <td>
                                            <a href="{{route('movements.edit' ,$record->car_movement_id )}}"
                                               class="btn btn-primary btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection
