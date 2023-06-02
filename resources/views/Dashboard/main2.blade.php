

@extends('Layouts.master')
@section('content')

<div class="section-body mt-3">



<div class="container-fluid">



    <div class="row">

    <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('employees') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-address-card"></i>
                                <span>@lang('home.human_resources')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="journal-entries" class="my_sort_cut text-muted">
                                <i class="fa fa-share-alt"></i>
                                <span>@lang('home.public_accounts')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('customers') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-users"></i>
                                <span>@lang('home.customers')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a  href="{{ route('invoices-acc') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-files-o"></i>
                                <span>@lang('home.invoices')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div  class="card-body ribbon">
                            <a href="{{ route('users') }}" class="my_sort_cut text-muted">
                                <i class="icon-users"></i>
                                <span>@lang('home.users')</span>
                            </a>
                        </div>
                    </div>
                </div>

        <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('home') }}"  class="my_sort_cut text-muted">
                                <i class="fa fa-share-square-o"></i>
                                <span>@lang('home.home')</span>
                            </a>
                        </div>
                    </div>
                </div>


    </div>
</div>
</div>

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="section-body">
                    <div class="container-fluid">

                        <div class="row clearfix row-deck">

                            {{--عدد بوالص حسب الشحن--}}
                            <div class="col-xl-6 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">   بوالص الشحن</h3>
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
                            <div class="col-xl-6 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"> ايرادات المبيعات </h3>
                                        <div class="card-options">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div id="chart-area-spline-sracked-1"
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
                            
                        </div>


                        <div class="row clearfix row-deck">

                        {{--عدد الاناث والذكور--}}
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"> {{__('home.male_female_count')}}</h3>
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
                                                <h6 class="mb-0">{{$emp_gen->columns[0][1]}}</h6>
                                                <small class="text-muted">{{__('home.male')}}</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$emp_gen->columns[1][1]}}</h6>
                                                <small class="text-muted">{{__('home.female')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{--سعودي وغير سعودي--}}
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">@lang('home.nationality')</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="chart-bar-stacked_1"
                                             style="height: 280px; max-height: 279.938px; position: relative;"
                                             class="c3">

                                             <svg width="200" height="240" style="overflow: hidden;">
                                               
                                              
                                               </svg>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$employees_saudi}}</h6>
                                                <small class="text-muted">{{__('home.saudis')}}</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$employees_non_saudi}}</h6>
                                                <small class="text-muted">{{__('home.none_saudis')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--الشاحنات  --}}
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">بيانات الشاحنات  </h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="GROWTHtruck" style="height: 240px; max-height: 240px; position: relative;"
                                             class="c3">
                                            <svg width="581.25" height="240" style="overflow: hidden;">
                                               
                                              
                                            </svg>
                                            <div class="c3-tooltip-container"
                                                 style="position: absolute; pointer-events: none; display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="row clearfix">
                                            <div class="col-3">
                                                <h6 class="mb-0">{{$truck_status->columns[0][1]}}</h6>
                                                <small class="text-muted">عامل </small>
                                            </div>
                                            
                                            <div class="col-3">
                                                <h6 class="mb-0">{{$truck_status->columns[1][1]}}</h6>
                                                <small class="text-muted">غير عامل </small>
                                            </div>
                                            <div class="col-3">
                                                <h6 class="mb-0">{{$truck_status->columns[2][1]}}</h6>
                                                <small class="text-muted">صيانه </small>
                                            </div>
                                            <div class="col-3">
                                                <h6 class="mb-0">{{$truck_status->columns[0][1] + $truck_status->columns[1][1] + $truck_status->columns[2][1]  }}</h6>
                                                <small class="text-muted">الاجمالي </small>
                                            </div>
                                        </div>
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
    <script>
    var data =
                {!! json_encode($data_sales_amount, JSON_HEX_TAG) !!}
var chart = c3.generate({
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
        ///////////////////الموظفين
        var data =
                {!! json_encode($data_sales, JSON_HEX_TAG) !!}

        var chart = c3.generate({
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


        // Gender
        var data_g =
                {!! json_encode($emp_gen, JSON_HEX_TAG) !!}
        var chart_g = c3.generate({
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
                {!! json_encode($truck_status, JSON_HEX_TAG) !!}
        var chart_g = c3.generate({
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


        // Employee Nationality
        var data_n =
                {!! json_encode($emp_nationality, JSON_HEX_TAG) !!}

        var chart_n = c3.generate({
                bindto: '#chart-bar-stacked_1', // id of chart wrapper
                data: data_n,
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep',
                            'Oct', 'Nov', 'Dec']
                    },
                },
                bar: {
                    width: 15
                },
                legend: {
                    show: false, //hide legend
                },
                padding: {
                    bottom: -20,
                    top: 0,
                    left: -6,
                },
            });


    </script>

@endsection