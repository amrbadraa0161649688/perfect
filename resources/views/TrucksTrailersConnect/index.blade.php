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

        <div class="section-body py-3">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">

                            <form action="" class="m-3">
                                <div class="row">
                                    {{--الفروع--}}
                                    <div class="col-md-4">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]" data-actions-box="true">
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company_id == $company->company_id)
                                                        selected @endif @endforeach @endif>
                                                    {{app()->getLocale()=='ar' ? $company->company_name_ar :
                                                    $company->company_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{__('trucks')}}</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="truck_id[]" data-actions-box="true">
                                            @foreach($trucks as $truck)
                                                <option value="{{$truck->truck_id}}"
                                                        @if(request()->truck_id) @foreach(request()->truck_id as
                                                     $truck_id) @if($truck_id == $truck->truck_id)
                                                        selected @endif @endforeach @endif>
                                                    {{ $truck->truck_name }} ==> {{ $truck->truck_code}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{__('trailers')}}</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="trailer_id[]" data-actions-box="true">
                                            @foreach($trailers as $trailer)
                                                <option value="{{$trailer->asset_id}}"
                                                        @if(request()->trailer_id) @foreach(request()->trailer_id as
                                                     $trailer_id) @if($trailer_id == $trailer->asset_id)
                                                        selected @endif @endforeach @endif>
                                                    {{ $trailer->asset_name_ar }} ==> {{ $trailer->asset_serial}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">{{__('drivers')}}</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="driver_id[]" data-actions-box="true">
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->emp_id}}"
                                                        @if(request()->driver_id) @foreach(request()->driver_id as
                                                     $driver_id) @if($driver_id == $employee->emp_id)
                                                        selected @endif @endforeach @endif>
                                                    {{ $employee->emp_name_full_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--تاريخ اانشاء من والي--}}
                                    <div class="col-md-3">
                                        <label>@lang('home.created_date_from')</label>
                                        <input type="date" class="form-control" name="created_date_from"
                                               @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                                @endif>
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('home.created_date_to')</label>
                                        <input type="date" class="form-control" name="created_date_to"
                                               @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                    </div>

                                    <div class="col-md-2">
                                        <br>
                                        <br>
                                        <button class="btn btn-primary" type="submit">{{__('search')}}</button>
                                    </div>


                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body py-3">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="{{route('TrucksTrailers.create')}}"
                                       class="btn btn-primary text-white">{{__('truck connect')}}</a>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('truck')}}</th>
                                            <th>{{__('trailer')}}</th>
                                            <th>{{__('driver')}}</th>
                                            <th>{{__('employee')}}</th>
                                            <th>{{__('status')}}</th>
                                            <th>{{__('date')}}</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($truck_to_trailers_hd  as $k=>$truck_to_trailer_hd)
                                            <tr>
                                                <th scope="row">{{$k+1}}</th>
                                                <td>
                                                    @if($truck_to_trailer_hd->truck)
                                                        {{$truck_to_trailer_hd->truck->truck_name}}
                                                        ==> {{$truck_to_trailer_hd->truck->truck_plate_no}}
                                                    @endif
                                                </td>

                                                <td>{{$truck_to_trailer_hd->trailer ? $truck_to_trailer_hd->trailer->asset_name_ar :''}}</td>

                                                <td>{{app()->getLocale() == 'ar' ? $truck_to_trailer_hd->driver->emp_name_full_ar
                                               : $truck_to_trailer_hd->driver->emp_name_full_en}}</td>

                                                <td>{{$truck_to_trailer_hd->user ? $truck_to_trailer_hd->user->user_name_ar :""}}</td>
                                                <td>{{$truck_to_trailer_hd->transactionType ? $truck_to_trailer_hd->transactionType->system_code_name_ar : ""}}</td>

                                                <td>{{ \Carbon\Carbon::parse($truck_to_trailer_hd->transaction_date)->format('d-m-Y')}}</td>
                                                <td>
                                                    <a href="{{route('TrucksTrailers.show',$truck_to_trailer_hd->id)}}"
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row w-100 mt-3">
                                    <div class="col-12">
                                        {{ $truck_to_trailers_hd->appends($data)->links() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection