@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
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

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>اجمالي عدد الشاحنات </h6>
                                    <h3 class="pt-2"><span class="counter">{{$all_trucks - $sales_truck}}</span></h3>
                                    <span><span class="text-danger mr-2"><i
                                                    class="fa fa-car"></i> 100 %</span> الشاحنات </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>جاهزة </h6>
                                    <h3 class="pt-2"><span class="counter">{{$ready_truck}}</span></h3>
                                    <span><span class="text-success mr-2"><i
                                                    class="fa fa-thumbs-o-up"></i> {{$ready_truck_p}} %</span> الشاحنات</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6> محملة</h6>
                                    <h3 class="pt-2"><span class="counter">{{$loaded_truck}}</span></h3>
                                    <span><span class="text-success mr-2"><i
                                                    class="fa fa-paper-plane-o"></i> {{$loaded_truck_p}} %</span> الشاحنات</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6> صيانة</h6>
                                    <h3 class="pt-2"><span class="counter">{{$mntns_truck}}</span></h3>
                                    <span><span class="text-danger mr-2"><i class="fa fa-gears"></i> {{$mntns_truck_p}}
                                            %</span> الشاحنات</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-1 col-md-6">
                    <div class="card" style="background: #B0352F;">
                        <div class="card-body">
                            <h6 style="color: white">  مباع</h6>
                            <h3 style="color: white" class="pt-2"><span class="counter">{{$sales_truck}}</span></h3>
                            <span><span style="color: white" class="text mr-1"><i class="fa fa-users"></i>  </span>   </span>
                        </div>
                    </div>
                </div>
                    </div>


                    <div class="row mb-12">


                    </div>

                    <div class="row">

                        <div class="card">
                            <div class="card-body">
                                <form action="">
                                    <div class="font-25">
                                        @lang('trucks.truck')
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">

                                            {{--الشركات--}}
                                            <div class="col-md-3">
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
                                            <div class="col-md-3">

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


                                            {{-- حالة  --}}
                                            <div class="col-md-3">
                                                {{-- trips--}}
                                                <label> @lang('trucks.truck_status')</label>

                                                <select class="selectpicker" multiple data-live-search="true"
                                                        name="sys_codes_statuss[]" data-actions-box="true" required>
                                                    @foreach($sys_codes_status as $sys_codes_statuss)
                                                        <option value="{{ $sys_codes_statuss->system_code_id }}"
                                                                @if(request()->sys_codes_statuss)
                                                                @foreach(request()->sys_codes_statuss as $sys_codes_statuss_1)
                                                                @if($sys_codes_statuss->system_code_id == $sys_codes_statuss_1) selected @endif
                                                                @endforeach
                                                                @endif>
                                                            {{app()->getLocale()=='ar'
                                                            ? $sys_codes_statuss->system_code_name_ar
                                                            : $sys_codes_statuss->system_code_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- فرع--}}
                                            <div class="col-md-3">
                                                {{-- loc_from  --}}
                                                <label>@lang('trucks.loc_truck')</label>
                                                <select class="selectpicker" multiple data-live-search="true"
                                                        name="loc_branch[]" data-actions-box="true">
                                                    @foreach($branch as $loc_branch)
                                                        <option value="{{ $loc_branch->branch_id }}"
                                                                @if(request()->loc_branch)
                                                                @foreach(request()->loc_branch as $loc_branch_1)
                                                                @if($loc_branch->branch_id == $loc_branch_1) selected @endif
                                                                @endforeach
                                                                @endif>
                                                            {{app()->getLocale()=='ar'
                                                            ? $loc_branch->branch_name_ar
                                                            : $loc_branch->branch_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>

                                        <div class="row">


                                        </div>

                                        <div class="row">

                                            {{--رقم الناقله--}}

                                            <div class="col-md-2">
                                                <label>@lang('trucks.truck_no')</label>
                                                <input type="text" class="form-control" name="truck_code_no"
                                                       @if(request()->truck_code_no) value="{{request()->truck_code_no}}" @endif>
                                            </div>

                                            {{--رقم اللوحه--}}

                                            <div class="col-md-2">
                                                <label>@lang('trucks.car_plate')</label>
                                                <input type="text" class="form-control" name="truck_plate_no_1"
                                                       @if(request()->truck_plate_no_1) value="{{request()->truck_plate_no_1}}" @endif>
                                            </div>


                                            {{--السائق --}}
                                            <div class="col-md-2">
                                                {{-- truck_driver--}}
                                                <label>@lang('trucks.truck_driver')</label>
                                                <select class="selectpicker" multiple data-live-search="true"
                                                        name="employees[]" data-actions-box="true">
                                                    @foreach($employees as $employeess)
                                                        <option value="{{ $employeess->emp_id }}"
                                                                @if(request()->employeess)
                                                                @foreach(request()->employeess as $employeess_1)
                                                                @if($employeess->emp_id == $employeess_1) selected @endif
                                                                @endforeach
                                                                @endif>
                                                            {{app()->getLocale()=='ar'
                                                            ? $employeess->emp_name_full_ar
                                                            : $employeess->emp_name_full_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{--من فرع--}}
                                            <div class="col-md-2">
                                                {{-- loc_from  --}}
                                                <label>@lang('home.from')</label>
                                                <select class="selectpicker" multiple data-live-search="true"
                                                        name="loc_from[]" data-actions-box="true">
                                                    @foreach($branch as $loc_from)
                                                        <option value="{{ $loc_from->branch_id }}"
                                                                @if(request()->loc_from)
                                                                @foreach(request()->loc_from as $loc_from_1)
                                                                @if($loc_from->branch_id == $loc_from_1) selected @endif
                                                                @endforeach
                                                                @endif>
                                                            {{app()->getLocale()=='ar'
                                                            ? $loc_from->branch_name_ar
                                                            : $loc_from->branch_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{--الى فرع--}}
                                            <div class="col-md-2">
                                                {{-- loc_  to--}}
                                                <label>@lang('home.to')</label>
                                                <select class="selectpicker" multiple data-live-search="true"
                                                        name="loc_to[]" data-actions-box="true">
                                                    @foreach($branch as $loc_to)
                                                        <option value="{{ $loc_to->branch_id }}"
                                                                @if(request()->loc_to)
                                                                @foreach(request()->loc_to as $loc_to_1)
                                                                @if($loc_to->branch_id == $loc_to_1) selected @endif
                                                                @endforeach
                                                                @endif>
                                                            {{app()->getLocale()=='ar'
                                                            ? $loc_to->branch_name_ar
                                                            : $loc_to->branch_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                                    <i class="fa fa-search"></i></button>
                                            </div>
                                            @foreach($trucks_report as $trucks_report_a)


                                                <a href="{{config('app.telerik_server')}}?rpt={{$trucks_report_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&lang=ar&skinName=bootstrap"
                                                   title="{{trans('PRINT')}}" class="btn btn-primary  mt-4"
                                                   id="showReport" target="_blank">
                                                    @lang('home.print') <i class="fa fa-print"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="">
                        <div class="my-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">

                                <a href="{{route('Trucks.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus"></i>@lang('trucks.add_truck')
                                </a>


                            </button>
                        </div>

                        <div class="table-responsive">


                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7" style="font-size: 16px"
                                    style="font-style: inherit">

                                    <th>@lang('trucks.truck_no')</th>

                                    <th>@lang('trucks.truck_name')</th>
                                    <th>@lang('trucks.truck_type')</th>
                                    <th>@lang('trucks.truck_plate')</th>

                                    <th>@lang('trucks.truck_driver')</th>
                                    <th>@lang('trucks.driver_id')</th>
                                    <th>@lang('trucks.driver_mobil')</th>
                                    <th>@lang('home.from')</th>
                                    <th>@lang('home.to')</th>
                                    <th>@lang('trucks.last_status_date')</th>
                                    <th>@lang('trucks.truck_status')</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="width45">
                                        <label class="custom-control custom-checkbox mb-0">
                                            <input type="checkbox" class="custom-control-input" name="example-checkbox1"
                                                   value="option1" checked="">
                                            <span class="custom-control-label">&nbsp;</span>
                                        </label>
                                    </td>

                                    <td>
                                        <img src="" class="rounded" alt="">

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

            </div>
            @endsection
            @section('scripts')

                <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
                <script type="text/javascript">
                    $(function () {

                        var table = $('.yajra-datatable').DataTable({
                            language: {
                                search: "بحث",
                                processing: "جاري البحث....",
                                info: " ",
                                entries: " ",
                                infoEmpty: "no data ",
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


                                {data: 'truck_code', name: 'truck_code'},

                                {data: 'truck_name', name: 'truck_name'},
                                {data: 'truck_types', name: 'truck_types'},
                                {data: 'truck_plate_no', name: 'truck_plate_no'},

                                {data: 'driver_name', name: 'driver_name'},
                                {data: 'driver_id', name: 'driver_id'},
                                {data: 'driver_mobile', name: 'driver_mobile'},
                                {data: 'branch_truck_from', name: 'branch_truck_from'},
                                {data: 'branch_truck_to', name: 'branch_truck_to'},
                                {data: 'status_date', name: 'status_date'},
                                {data: 'status', name: 'status'},


                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: true,
                                    searchable: true
                                },
                            ]
                        });

                    });
                </script>

@endsection
