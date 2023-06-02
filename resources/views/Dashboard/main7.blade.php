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

    <div class="section-body mt-3">


        <div class="container-fluid">


            <div class="row">

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('car-rent.customers.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-vcard"></i>
                                <span>@lang('home.add_customer')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('car-rent.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-tasks"></i>
                                <span>@lang('carrent.car_rent_contract')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('car-accident.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-code-fork"></i>
                                <span>@lang('carrent.car_rent_line')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('car-accident.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-chain"></i>
                                <span>@lang('carrent.car_rent_accedant')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('car-rent.invoices')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-files-o"></i>
                                <span>@lang('home.invoices')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('home') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-share-square-o"></i>
                                <span>@lang('home.home')</span>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
            <form action="">
                <div class="row mb-12">
                    <div hidden class="col-md-4">
                        <label>@lang('home.company_group')</label>
                        @if(auth()->user()->user_type_id  == 1)
                            <input type="text" class="form-control"
                                   value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                                        session('company_group')['company_group_en'] }}" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </div>

                    <div class="col-md-5">
                        <label>@lang('home.companies')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                name="company_id[]" data-actions-box="true" required>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if(request()->company_id)
                                            @foreach(request()->company_id  as $company_id)
                                                @if($company_id == $company->company_id) selected @endif
                                    @endforeach @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-5">
                        <label>@lang('home.branch')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                name="branch_id[]" data-actions-box="true">
                            @foreach($branches as $branch)
                                <option value="{{$branch->branch_id}}"
                                        @if(request()->branch_id)
                                            @foreach(request()->branch_id  as $branch_id)
                                                @if($branch_id == $branch->branch_id) selected @endif
                                    @endforeach @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$branch->branch_name_ar}}
                                    @else
                                        {{$branch->branch_name_en}}
                                    @endif
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-2">
                        <br>
                        <button type="submit" class="btn btn-info">@lang('home.search')</button>
                    </div>
                </div>
            </form>
            <div class="row mt-1">
            </div>
            <div class="row ">
                <div class="col-lg-2 col-md-6" style="width: 150px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #0d8f67 ;">
                        <div class="card-body w_sparkline">

                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.car_ready')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$ready_cars}}</h3>
                            </div>
                            <div class="w_chart">
                                <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.'&car_category_id[]='.$car_ready_code->system_code_id}}"
                                   class="my_sort_cut text-muted">
                                    <i class="fa fa-thumbs-o-up"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #79589c ;">
                        <div class="card-body w_sparkline">
                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.car_rent')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$rent_cars}}</h3>
                            </div>
                            <div class="w_chart">
                                <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.'&car_category_id[]='.$car_rent_code->system_code_id}}"
                                   class="my_sort_cut text-muted">
                                    <i class="fa fa-thumbs-o-up"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #edb052 ;">
                        <div class="card-body w_sparkline">
                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.car_lines')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$movements}}</h3>
                            </div>

                            <div class="w_chart">
                                <a href="{{route('movements.index')}}" class="my_sort_cut text-muted">
                                    <i class="fa fa-thumbs-o-up"></i>

                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #92aacc ;">
                        <div class="card-body w_sparkline">
                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.open_contract')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$open_conts}}</h3>
                            </div>

                            <div class="w_chart">
                                <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contract_status[]='.$con_open_code->system_code_id}}"
                                   class="my_sort_cut text-muted">
                                    <i class="fa fa-files-o"></i>

                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #d03104 ;">
                        <div class="card-body w_sparkline">
                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.late_contract')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$late_conts}}</h3>
                            </div>

                            <div class="w_chart">
                                <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contract_status[]='.$con_late_code->system_code_id}}"
                                   class="my_sort_cut text-muted">
                                    <i class="fa fa-hourglass-half"></i>

                                </a>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                    <div class="card" STYLE="height :100px ;border: 2px solid #ccc; background-color: #bda2b8 ;">
                        <div class="card-body w_sparkline">
                            <div class="details">
                                <span style=" font-weight: bold;color: white ">{{__('carrent.today_contract')}}</span>
                                <h3 class="mb-0 counter" style=" font-weight: bold">{{$today_conts}}</h3>
                            </div>

                            <div class="w_chart">
                                <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&closed_datetime_from='.date('Y-m-d')}}"
                                   class="my_sort_cut text-muted">
                                    <i class="fa fa-hand-paper-o" data-toggle="tooltip"></i>

                                </a>
                            </div>

                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <div class="row mt-3">

    </div>
    <div class="section-body">
        <div class="container-fluid">


            <div class="row clearfix row-deck">

                {{--عدد  --}}
                <div class="col-xl-3 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('carrent.contract_statuses')}}</h3>
                        </div>
                        <div class="card-body text-center">
                            <div id="GROWTH_1" style="height: 240px; max-height: 240px; position: relative;"
                                 class="c3">
                                <div class="c3-tooltip-container"
                                     style="position: absolute; pointer-events: none; display: none;"></div>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <div class="row clearfix">

                                <div class="col-6">
                                    <small class="text-muted">{{__('carrent.total_contracts')}}</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0">{{$open_conts}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--عقود  التاجير--}}
                <div class="col-xl-6 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('carrent.contracts')}}</h3>
                            <div class="card-options">
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="chart-bar-emp"
                                 style="height: 280; max-height: 280px; position: relative;"
                                 class="c3">
                                <svg width="1000.25" height="240" style="overflow: hidden;">


                                </svg>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>

                {{--السيارات  --}}
                <div class="col-xl-3 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('carrent.car_statuses')}}</h3>
                        </div>
                        <div class="card-body text-center">
                            <div id="GROWTHtruck" style="height: 240px; max-height: 240px; position: relative;"
                                 class="c3">
                                <svg width="200" height="240" style="overflow: hidden;">


                                </svg>
                                <div class="c3-tooltip-container"
                                     style="position: absolute; pointer-events: none; display: none;"></div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row clearfix">
                                <div class="col-6">
                                    <small class="text-muted">{{__('carrent.total_cars')}}</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0">{{\App\Models\CarRentCars::count()}}</h6>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        var data =
            {!! json_encode($data_contracts_amount, JSON_HEX_TAG) !!}
                var
        chart = c3.generate({
            bindto: '#chart-area-spline-sracked-1', // id of chart wrapper
            data: data,
            axis: {
                x: {
                    type: 'category',
                    // name of each category
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'August',
                        'Sep', 'Auc', 'Nov', 'Dec']
                },
                y: {
                    tick: {
                        format: d3.format("")
                    }
                }
            },
            bar: {
                width: 15
            },
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 0,
                top: 0
            },
        });

        ///////////////////العقود
        var data =
            {!! json_encode($data_contracts, JSON_HEX_TAG) !!}

                var
        chart = c3.generate({
            bindto: '#chart-bar-emp', // id of chart wrapper
            data: data,
            axis: {
                x: {
                    type: 'category',
                    // name of each category
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'August',
                        'Sep', 'Auc', 'Nov', 'Dec']
                },
                y: {
                    tick: {
                        format: d3.format("")
                    }
                }
            },
            bar: {
                width: 15
            },
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 0,
                top: 0
            },
        });


        {{--// Gender--}}
        var data_g =
            {!! json_encode($contract_status, JSON_HEX_TAG) !!}
                var
        chart_g = c3.generate({
            bindto: '#GROWTH_1', // id of chart wrapper
            data: data_g,
            axis: {},
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 20,
                top: 0
            },
        });

        // truck
        var data_g =
            {!! json_encode($car_status, JSON_HEX_TAG) !!}
                var
        chart_g = c3.generate({
            bindto: '#GROWTHtruck', // id of chart wrapper
            data: data_g,
            axis: {},
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 20,
                top: 0
            },
        });


        {{--// Employee Nationality--}}
        {{--var data_n =--}}
        {{--        {!! json_encode($emp_nationality, JSON_HEX_TAG) !!}--}}

        {{--var chart_n = c3.generate({--}}
        {{--        bindto: '#chart-bar-stacked_1', // id of chart wrapper--}}
        {{--        data: data_n,--}}
        {{--        axis: {--}}
        {{--            x: {--}}
        {{--                type: 'category',--}}
        {{--                // name of each category--}}
        {{--                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep',--}}
        {{--                    'Oct', 'Nov', 'Dec']--}}
        {{--            },--}}
        {{--        },--}}
        {{--        bar: {--}}
        {{--            width: 15--}}
        {{--        },--}}
        {{--        legend: {--}}
        {{--            show: false, //hide legend--}}
        {{--        },--}}
        {{--        padding: {--}}
        {{--            bottom: -20,--}}
        {{--            top: 0,--}}
        {{--            left: -6,--}}
        {{--        },--}}
        {{--    });--}}
    </script>

@endsection
