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
                <div class="col-6 col-md">
                    <div style="font-size: 16px ;font-weight: bold " class="card">
                        <a href="{{route('car-rent.customers.index')}}" class="text-black-50">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.all_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$customer_all_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-users"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6  col-md">
                    <div style="font-size: 16px ;font-weight: bold " class="card">
                        <a href="{{route('car-rent.customers.index')}}?customer_type[]={{$customer_individual_system_code->system_code_id}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.customer_individual_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$customer_individual_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-users"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6  col-md">
                    <div style="font-size: 16px ;font-weight: bold " class="card">
                        <a href="{{route('car-rent.customers.index')}}?customer_type[]={{$customer_company_system_code->system_code_id}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.customer_company_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$customer_company_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-users"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6  col-md">
                    <div style="font-size: 16px ;font-weight: bold " class="card">
                        <a href="{{route('car-rent.customers.index')}}?customer_type[]={{$customer_government_system_code->system_code_id}}"
                           class="text-black-50">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.customer_government_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$customer_government_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-users"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6  col-md">
                    <div class="card">
                        <div class="card-body">
                            <h6 style="font-size: 15px ;font-weight: bold ;color:red  ">{{__('home.customer_baned_count')}}</h6>
                            <h3 class="pt-2"><span class="counter">{{$customer_baned_count}}</span></h3>
                            <span class="text-danger mr-2"><i
                                    class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>
                <div style="font-size: 16px ;font-weight: bold " class="card">
                    <div class="card-header">
                        @include('Includes.form-errors')
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label> @lang('home.company_group')</label>
                                    @if(auth()->user()->user_type_id  == 1)
                                        <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                                             session('company_group')['company_group_en'] }}" readonly>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ auth()->user()->companyGroup->company_group_ar }} @else
                                {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </div>


                                <div class="col-md-3 mb-3">
                                    <label> @lang('home.name')</label>
                                    <input type="text" name="customer_name_full" class="form-control"
                                           placeholder="Name"
                                           @if(request()->customer_name_full)  value="{{request()->customer_name_full}}"
                                        @endif>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label> @lang('home.identity')</label>
                                    <input type="text" name="customer_identity" class="form-control"
                                           placeholder="Identity"
                                           @if(request()->customer_identity)  value="{{request()->customer_identity}}"
                                        @endif>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label> @lang('home.private_mobile')</label>
                                    <input type="number" name="customer_mobile" class="form-control"
                                           placeholder="Mobile"
                                           @if(request()->customer_mobile)  value="{{request()->customer_mobile}}"
                                        @endif>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>@lang('home.nationality')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customer_nationality[]" data-actions-box="true">

                                        @foreach($sys_codes_nationality_country as $sys_code_nationality)
                                            <option value="{{$sys_code_nationality->system_code_id}}"
                                                    @if(request()->customer_nationality)
                                                        @foreach(request()->customer_nationality as $sys_code_nationality_s)
                                                            @if($sys_code_nationality_s  ==  $sys_code_nationality->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_nationality->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_nationality->system_code_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>


                                <div class="col-md-3 mb-3">
                                    <label>@lang('home.customer_classification')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customer_classification[]" data-actions-box="true">

                                        @foreach($sys_code_classifications as $sys_code_classification)
                                            <option value="{{$sys_code_classification->system_code_id}}"
                                                    @if(request()->customer_classification)
                                                        @foreach(request()->customer_classification  as $sys_classification)
                                                            @if($sys_classification == $sys_code_classification->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_classification->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_classification->system_code_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>@lang('home.customer_type')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customer_type[]" data-actions-box="true">

                                        @foreach($sys_codes_type as $sys_code_type)
                                            <option value="{{$sys_code_type->system_code_id}}"
                                                    @if(request()->customer_type)
                                                        @foreach(request()->customer_type  as $sys_type)
                                                            @if($sys_type == $sys_code_type->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_type->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_type->system_code_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary " style="margin-top:42px"
                                            type="submit">@lang('home.search')
                                        <i class="fa fa-search"></i></button>
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
                            <a href="{{ route('car-rent.customers.all_create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_customer')
                            </a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7">
                                    <th class="sorting" style=" width: 10px; "></th>
                                    <th>@lang('customer.customer_no')</th>
                                    <th>@lang('customer.customer_name')</th>
                                    <th>@lang('home.customer_type')</th>
                                    <th>@lang('home.customer_classification')</th>
                                    <th>@lang('customer.customer_mobile')</th>
                                    <th>@lang('customer.customer_id')</th>
                                    <th>@lang('home.customer_birthday')</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td></td>
                                        <td>{{$customer->customer_id}}</td>
                                        <td>{{app()->getLocale() == 'ar'
                                ? $customer->customer_name_full_ar
                                : $customer->customer_name_full_en }}</td>
                                        <td>
                                            @if($customer->cus_type)
                                                {{app()->getLocale() == 'ar'
                                                    ? $customer->cus_type->system_code_name_ar
                                                    : $customer->cus_type->system_code_name_en}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer->classifications)
                                                {{app()->getLocale() == 'ar'
                                                    ? $customer->classifications->system_code_name_ar
                                                    : $customer->classifications->system_code_name_en}}
                                            @endif
                                        </td>
                                        <td>{{$customer->customer_mobile}}</td>
                                        <td>{{$customer->customer_identity}}</td>
                                        <td>{{$customer->customer_birthday}}</td>
                                        <td>
                                            <a href="{{route('car-rent.customers.edit' ,$customer->customer_id )}}"
                                               class="btn btn-info btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('car-rent.customers.block' ,$customer->customer_id )}}"
                                               class="btn btn-primary btn-sm"
                                               title="@lang('home.block')">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection
