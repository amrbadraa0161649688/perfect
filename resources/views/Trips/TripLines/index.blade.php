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
    <div class="card-body">

        <div class="container-fluid">


            <div class="section-body mt-3" id="app">
                <div class="container-fluid">

                    @include('Includes.form-errors')

                    <div class="row mb-12">
                        <div class="col-md-6">
                        
                        </div>

                        <div hidden class="col-md-6">
                            <form action="">
                           
                                <select class="form-control" name="company_id"
                                        onchange="this.form.submit()">
                                    @if(auth()->user()->user_type_id == 1)
                                        @foreach($companies as $company)
                                            <option value="{{$company->company_id}}"
                                                    @if(request()->company_id == $company->company_id) selected @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company->company_name_ar}}
                                                @else
                                                    {{$company->company_name_en}}
                                                @endif
                                            </option>

                                        @endforeach
                                    @else
                                        @foreach(auth()->user()->companyGroup->companies as $company)
                                            <option value="{{$company->company_id}}"
                                                    @if(request()->company_id == $company->company_id)
                                                    selected
                                                    @elseif(auth()->user()->company->company_id == $company->company_id)
                                                    selected
                                                    @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company->company_name_ar}}
                                                @else
                                                    {{$company->company_name_en}}
                                                @endif
                                            </option>

                                        @endforeach
                                    @endif

                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="row">

<div class="card">
    <div class="card-body">
        <form action="">
                                <div class="font-25">
                                        @lang('trucks.trip_lines')
                                    </div>
            <div class="col-md-12">
                <div class="row">

                    {{--الشركات--}}
                    <div class="col-md-4">
                        <label>@lang('home.companies')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                name="company_id[]" required>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if(request()->company_id) @foreach(request()->company_id as
                                     $company_id) @if($company->company_id == $company_id) selected @endif @endforeach @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>

                            @endforeach
                        </select>

                    </div>

                    {{--نوع الناقله--}}
                    <div class="col-md-4">
                        {{-- branches  --}}
                        <label>@lang('trucks.truck_type')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                    name="sys_truck_types[]" data-actions-box="true" required>
                                 @foreach($sys_truck_type as $sys_truck_types)
                                    <option value="{{ $sys_truck_types->system_code_id }}"
                                            @if(request()->sys_truck_types)
                                            @foreach(request()->sys_truck_types as $sys_truck_types_1)
                                            @if($sys_truck_types->system_code_id == $sys_truck_types_1) selected @endif
                                            @endforeach
                                            @endif>
                                        {{app()->getLocale()=='ar'
                                        ? $sys_truck_types->system_code_name_ar
                                        : $sys_truck_types->system_code_name_en }}</option>
                                @endforeach
                            </select>
                    </div>

                    {{--نوع خط السير--}}
                    <div class="col-md-4">
                        {{-- branches  --}}
                        <label>@lang('trucks.trip_line_type')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                    name="sys_line_types[]" data-actions-box="true" required>
                                 @foreach($sys_line_type as $sys_line_types)
                                    <option value="{{ $sys_line_types->system_code_id }}"
                                            @if(request()->sys_line_types)
                                            @foreach(request()->sys_line_types as $sys_line_types_1)
                                            @if($sys_line_types->system_code_id == $sys_line_types_1) selected @endif
                                            @endforeach
                                            @endif>
                                        {{app()->getLocale()=='ar'
                                        ? $sys_line_types->system_code_name_ar
                                        : $sys_line_types->system_code_name_en }}</option>
                                @endforeach
                            </select>
                    </div>

                </div>

                
                <div class="row">
                    
                                    {{--رقم الخط--}}

                                <div class="col-md-2">
                                    <label>@lang('trucks.trip_line_no')</label>
                                    <input type="text" class="form-control" name="trip_line_hd_code"
                                        @if(request()->trip_line_hd_code) value="{{request()->trip_line_hd_code}}" @endif>
                                </div>


                                                            {{-- حالة الخط --}}
                                                            <div class="col-md-2">
                                                                {{-- trips--}}
                                                                <label> @lang('trucks.truck_status')</label>

                                                                <select class="selectpicker" multiple data-live-search="true"
                                                                                                                name="statuses[]" required>

                                                                                                            <option value="1"
                                                                                                                    @if(request()->statuses) @foreach(request()->statuses as
                                                                                                                $status) @if($status== 1) selected @endif @endforeach @endif>
                                                                                                                @lang('home.active')</option>

                                                                                                            <option value="0"
                                                                                                                    @if(request()->statuses) @foreach(request()->statuses as
                                                                                                                $status) @if($status== 0) selected @endif @endforeach @endif>
                                                                                                                @lang('home.not_active')</option>

                                                                                                            

                                                                                                        </select>
                                                            </div>

                                                            {{--من فرع--}}
                                                            <div class="col-md-2">
                                                                {{-- loc_from  --}}
                                                                <label>@lang('home.from')</label>
                                                                <select class="selectpicker" multiple data-live-search="true"
                                                                        name="loc_from[]" data-actions-box="true">
                                                                    @foreach($sys_codes_location as $loc_from)
                                                                        <option value="{{ $loc_from->system_code_id }}"
                                                                                @if(request()->loc_from)
                                                                                @foreach(request()->loc_from as $loc_from_1)
                                                                                @if($loc_from->system_code_id == $loc_from_1) selected @endif
                                                                                @endforeach
                                                                                @endif>
                                                                            {{app()->getLocale()=='ar'
                                                                            ? $loc_from->system_code_name_ar
                                                                            : $loc_from->system_code_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{--الى فرع--}}
                                                            <div class="col-md-2">
                                                                {{-- loc_  to--}}
                                                                <label>@lang('home.to')</label>
                                                                <select class="selectpicker" multiple data-live-search="true"
                                                                        name="loc_to[]" data-actions-box="true">
                                                                    @foreach($sys_codes_location as $loc_to)
                                                                        <option value="{{ $loc_to->system_code_id }}"
                                                                                @if(request()->loc_to)
                                                                                @foreach(request()->loc_to as $loc_to_1)
                                                                                @if($loc_to->system_code_id == $loc_to_1) selected @endif
                                                                                @endforeach
                                                                                @endif>
                                                                            {{app()->getLocale()=='ar'
                                                                            ? $loc_to->system_code_name_ar
                                                                            : $loc_to->system_code_name_en }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                    
                                                            <div class="col-md-2">
                                                                <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                                                    <i class="fa fa-search"></i></button>
                                                                    </div>   
                                                            
                                                                    <div class="col-md-2">
                                                                    <a
                                                                        href="{{config('app.telerik_server')}}?rpt={{$trip_line_all}}&id={{$company_report->company_id}}&lang=ar&skinName=bootstrap"
                                                                        title="{{trans('PRINT')}}" class="btn btn-primary mt-4" id="showReport" target="_blank">
                                                                    @lang('waybill.trip_line_report')
                                                                    </a>
                                                                    
                                                                    </div>   
                                                    
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                </div>



                    <div class="">

                        <div class="col-md-3">

                        @if(auth()->user()->user_type_id != 1)
                                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 105 && $job_permission->permission_add)
                                    <button type="button" class="btn btn-primary">
                                <a href="{{route('TripLine.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('trucks.add_trip_line')
                                </a>

                            </button>

                                    @endif
                                @endforeach
                            @else
                            <button type="button" class="btn btn-primary">
                                <a href="{{route('TripLine.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('trucks.add_trip_line')
                                </a>

                            </button>

                            @endif


                           


                        </div>

                        <div class="table-responsive">

                     

                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7;font-size: 16px;font-style: inherit">
                                    <th class="sorting" style="width : 10px"></th>
                                    <th style="width: 130px">@lang('trucks.trip_line_no')</th>
                                    <th style="width: 350px">@lang('trucks.trip_line_desc')</th>
                                    <th>@lang('trucks.trip_line_destinct')</th>
                                    <th>@lang('trucks.trip_line_time')</th>
                                    <th>@lang('customer.diesel_expense')</th>
                                    <th>@lang('customer.road_bonus')</th>
                                   
                                    <th>@lang('customer.fess')</th>
                                    <th style="width: 130px">@lang('trucks.truck_type')</th>
                                    <th style="width: 130px">@lang('trucks.trip_line_type')</th>
                                    <th>@lang('customer.customer_status')</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                           
                                {{--@foreach($trip_lines as $trip_line)--}}
                                {{--<tr>--}}
                                    {{--<td></td>--}}
                                    {{--<td>{{$trip_line->trip_line_code}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_desc}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_distance}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_time}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_fess_1}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_fees_2}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_fees_3}}</td>--}}
                                    {{--<td>{{$trip_line->truck_type}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_type}}</td>--}}
                                    {{--<td>{{$trip_line->trip_line_status}}</td>--}}
                                {{--</tr>--}}
                                    {{--@endforeach--}}
                                </tbody>
                            </table>

                         
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

    <script type="text/javascript">
                        $(function () {
                            var table = $('.yajra-datatable').DataTable({
                                language: {
                                    search: "بحث",
                                    processing: "جاري البحث....",
                                    info: " ",
                                    infoEmpty: " ",
                                    paginate: {
                                        first: "الاول",
                                        previous: "السابق",
                                        next: "التالي",
                                        last: "الاخير"
                                    },
                                    aria: {
                                        sortAscending: ": activer pour trier la colonne par ordre croissant",
                                        sortDescending: ": activer pour trier la colonne par ordre décroissant"
                                    }
                                },
                                processing: true,
                                serverSide: true,
                                ajax: "",
                                columns: [ 
                                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                    {data: 'trip_line_code', name: 'trip_line_code'},
                                    {data: 'trip_line_desc', name: 'trip_line_desc'} ,
                                    {data: 'trip_line_distance', name: 'trip_line_distance'},
                                    {data: 'trip_line_time', name: 'trip_line_time'},
                                    {data: 'trip_line_fess_1', name: 'trip_line_fess_1'},
                                    {data: 'trip_line_fees_2', name: 'trip_line_fees_2'},
                                    {data: 'trip_line_fees_3', name: 'trip_line_fees_3'},
                                    {data: 'truck_type', name: 'truck_type'},
                                    {data: 'trip_line_type', name: 'trip_line_type'},
                                    {data: 'status', name: 'status'},
                                    {data: 'action', name: 'action'},
                                ]
                            });

                        });
    </script>
@endsection

