@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .dropdown-toggle {
            background-color: white !important;
        }
    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <form action="">
                        @if(session('company_group'))
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ session('company_group')['company_group_ar'] }} @else
                            {{ session('company_group')['company_group_en'] }} @endif" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="company_id" name="company_id" onchange="getData( $('#company_id').val())" disabled>
                            <option value="">@lang('sales_car.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                    @if( $user_data['company']->company_id == $company->company_id) selected @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="branch_id" name="branch_id" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('sales_car.choose')</option>
                            @foreach($branch_list as $branch)
                                <option value="{{$branch->branch_id}}" @if( $user_data['branch']->branch_id == $branch->branch_id) selected @endif>
                                    {{ $branch->getBranchName() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="warehouses_type" name="warehouses_type" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('sales_car.choose')</option>
                            @foreach($warehouses_type_lits as $warehouses_type)
                                <option value="{{$warehouses_type->system_code_id}}"  >
                                        {{ $warehouses_type->getSysCodeName() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
            </div>
            <br>
            <div class="row" id ="search_input">
                <div class="col-md-10 row">
                    <div class="col-md-4">
                        <select class="selectpicker show-tick form-control" data-live-search="true"  id="car_brand_s" name="car_brand_s" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('sales_car.choose')</option>
                            @foreach($car_brand_list as $brand)
                                <option value="{{$brand->brand_id}}">
                                    {{ $brand->getName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="selectpicker show-tick form-control"  data-live-search="true" id="car_sales_cars_brand_dt_id_s" name="car_sales_cars_brand_dt_id_s" onchange="getData( $('#company_id').val())">
                            <option value=""> @lang('sales_car.choose') </option>
                            @foreach($car_brand_dt_list as $brand_dt)
                                <option value="{{$brand_dt->brand_dt_id}}">
                                    {{ $brand_dt->getBrandName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="car_status_s" name="car_status_s" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('sales_car.choose')</option>
                            @foreach($car_status_list as $status)
                                <option value="{{$status->system_code_id}}">
                                    {{ $status->getSysCodeName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
                <div class="col-md-2 row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block" onclick="getData()">
                            @lang('sales_car.search_button')
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning btn-block" onclick="resetSearch()">
                            @lang('sales_car.clear_button')
                        </button>
                    </div>
                </div>
            </div>
            <br>
            <div id="showData"></div>
        </div>
    </div>

    
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
        
        $(function () {
            //company_id = {{ auth()->user()->company_id }};
            //getData(company_id);
            getData();
        });

        function getData(){
            //console.log($('#company_id').val());
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            search = { 
                company_id: $('#company_id').val(), 
                warehouses_type: $('#warehouses_type').val(), 
                branch_id: $('#branch_id').val()
            };
            $.ajax({
                type: 'get',
                url :"{{route('sales-car.data')}}",
                data:{
                    _token : "{{ csrf_token() }}",
                    company_id : company_id,
                    search : search,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function(data){
               
                if(data.success==false){
                    toastr.warning(data.msg);
                }
                $('#showData').html(data.view);
            });
        }

        function resetSearch()
        {
            $('#search_input').find('input:text').val('');    
        }
        
    </script>
@endsection

